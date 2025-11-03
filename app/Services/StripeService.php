<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Customer;
use Stripe\Webhook;
use App\Models\Order;
use App\Models\OrderLocation;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Créer un PaymentIntent pour un achat
     */
    public function createPaymentIntentForOrder(Order $order): PaymentIntent
    {
        $paymentIntent = PaymentIntent::create([
            'amount' => $this->convertToStripeAmount($order->total_amount),
            'currency' => 'eur',
            'description' => "Commande d'achat #{$order->order_number}",
            'metadata' => [
                'order_id' => $order->id,
                'order_type' => 'purchase',
                'order_number' => $order->order_number,
                'user_id' => $order->user_id,
                'user_email' => $order->user->email ?? ''
            ],
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);

        // Sauvegarder l'ID du PaymentIntent
        $order->update([
            'stripe_payment_intent_id' => $paymentIntent->id,
            'payment_status' => 'pending'
        ]);

        return $paymentIntent;
    }

    /**
     * Créer un PaymentIntent pour une location (PAIEMENT IMMÉDIAT)
     */
    public function createPaymentIntentForRental(OrderLocation $orderLocation): array
    {
        // ÉTAPE 1 : Paiement immédiat de la location (sans caution)
        $paymentIntent = PaymentIntent::create([
            'amount' => $this->convertToStripeAmount($orderLocation->total_amount),
            'currency' => 'eur',
            'description' => "Location #{$orderLocation->order_number} - Paiement location",
            'metadata' => [
                'order_id' => $orderLocation->id,
                'order_type' => 'rental',
                'payment_type' => 'rental_payment',
                'order_number' => $orderLocation->order_number,
                'user_id' => $orderLocation->user_id,
                'user_email' => $orderLocation->user->email ?? ''
            ],
            'automatic_payment_methods' => [
                'enabled' => true,
                'allow_redirects' => 'never'
            ],
        ]);

        // ÉTAPE 2 : Préautorisation de la caution (si > 0)
        $depositAuthorization = null;
        $depositAuthorizationId = null;
        if ($orderLocation->deposit_amount > 0) {
            $depositAuthorization = $this->createDepositAuthorization($orderLocation);
            $depositAuthorizationId = $depositAuthorization ? $depositAuthorization->id : null;
        }

        // Sauvegarder les IDs des PaymentIntents
        $orderLocation->update([
            'stripe_payment_intent_id' => $paymentIntent->id,
            'stripe_deposit_authorization_id' => $depositAuthorizationId,
            'payment_status' => 'pending'
        ]);

        // Retourner les informations pour le frontend
        return [
            'success' => true,
            'rental_payment_intent_id' => $paymentIntent->id,
            'rental_client_secret' => $paymentIntent->client_secret,
            'rental_amount' => $this->convertFromStripeAmount($paymentIntent->amount),
            'deposit_authorization_id' => $depositAuthorizationId,
            'deposit_client_secret' => $depositAuthorization ? $depositAuthorization->client_secret : null,
            'deposit_amount' => $orderLocation->deposit_amount,
            'order_location_id' => $orderLocation->id,
            'order_number' => $orderLocation->order_number
        ];
    }

    /**
     * Créer une préautorisation pour la caution (CAPTURE MANUELLE)
     */
    public function createDepositAuthorization(OrderLocation $orderLocation): ?PaymentIntent
    {
        if ($orderLocation->deposit_amount <= 0) {
            return null;
        }

        try {
            $authorizationIntent = PaymentIntent::create([
                'amount' => $this->convertToStripeAmount($orderLocation->deposit_amount),
                'currency' => 'eur',
                'capture_method' => 'manual', // 🔑 PRÉAUTORISATION !
                'description' => "Caution #{$orderLocation->order_number} - Préautorisation",
                'metadata' => [
                    'order_id' => $orderLocation->id,
                    'order_type' => 'rental',
                    'payment_type' => 'deposit_authorization',
                    'order_number' => $orderLocation->order_number,
                    'user_id' => $orderLocation->user_id,
                    'user_email' => $orderLocation->user->email ?? '',
                    'deposit_amount' => $orderLocation->deposit_amount
                ],
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never'
                ],
            ]);

            Log::info('Préautorisation caution créée', [
                'order_location_id' => $orderLocation->id,
                'deposit_authorization_id' => $authorizationIntent->id,
                'deposit_amount' => $orderLocation->deposit_amount
            ]);

            return $authorizationIntent;
        } catch (\Exception $e) {
            Log::error('Erreur création préautorisation caution', [
                'order_location_id' => $orderLocation->id,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Gérer un paiement réussi
     */
    public function handleSuccessfulPayment(string $paymentIntentId): array
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            $metadata = $paymentIntent->metadata->toArray();

            if ($metadata['order_type'] === 'purchase') {
                $orderId = (int)$metadata['order_id'];
                $this->processSuccessfulPurchase($orderId, $paymentIntent);
                
                $order = \App\Models\Order::find($orderId);
                return [
                    'success' => true,
                    'order_type' => 'purchase',
                    'order_id' => $orderId,
                    'order_number' => $order?->order_number,
                    'redirect_url' => route('orders.confirmation', $orderId)
                ];
            } elseif ($metadata['order_type'] === 'rental') {
                $orderLocationId = (int)$metadata['order_id'];
                
                // Déterminer le type de paiement (location ou caution)
                $paymentType = $metadata['payment_type'] ?? 'rental_payment';
                
                if ($paymentType === 'rental_payment') {
                    // Paiement de la location confirmé
                    $this->processSuccessfulRental($orderLocationId, $paymentIntent);
                } elseif ($paymentType === 'deposit_authorization') {
                    // Préautorisation de caution confirmée
                    $this->processSuccessfulDepositAuthorization($orderLocationId, $paymentIntent);
                }
                
                $orderLocation = \App\Models\OrderLocation::find($orderLocationId);
                return [
                    'success' => true,
                    'order_type' => 'rental',
                    'order_id' => $orderLocationId,
                    'order_number' => $orderLocation?->order_number,
                    'redirect_url' => route('rental.payment.success', $orderLocationId),
                    'payment_type' => $paymentType
                ];
            }

            return ['success' => false, 'error' => 'Type de commande inconnu'];
        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement d\'un paiement réussi', [
                'payment_intent_id' => $paymentIntentId,
                'error' => $e->getMessage()
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Traiter un achat réussi
     */
    private function processSuccessfulPurchase(int $orderId, PaymentIntent $paymentIntent): void
    {
        $order = Order::findOrFail($orderId);
        
        // Mettre à jour les informations de paiement
        $order->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
            'stripe_payment_intent_id' => $paymentIntent->id,
            'payment_method' => 'stripe',
            'payment_details' => [
                'stripe_payment_intent_id' => $paymentIntent->id,
                'amount_paid' => $this->convertFromStripeAmount($paymentIntent->amount),
                'currency' => $paymentIntent->currency,
                'paid_at' => now()->toISOString()
            ]
        ]);

        // ✅ NOUVEAU SYSTÈME NON-BLOQUANT : Progression via le modèle Order
        Log::info("Démarrage progression non-bloquante pour commande {$order->order_number}");
        
        // Passer à confirmed et déclencher les transitions automatiques
        $order->updateStatus('confirmed');
        
        // 🚀 Démarrer automatiquement le worker de queue si nécessaire
        \App\Services\QueueWorkerService::ensureWorkerIsRunning();
        
        Log::info("Commande {$order->order_number} confirmée - Transitions automatiques démarrées");

        // Décrémenter le stock des produits
        foreach ($order->items as $item) {
            $product = $item->product;
            if ($product && $product->quantity >= $item->quantity) {
                $newQuantity = $product->quantity - $item->quantity;
                $product->update(['quantity' => $newQuantity]);
                
                Log::info('Stock décrémenté après paiement', [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'previous_quantity' => $product->quantity + $item->quantity,
                    'new_quantity' => $newQuantity,
                    'decremented_by' => $item->quantity,
                    'order_id' => $order->id
                ]);
            }
        }

        Log::info('Commande d\'achat payée avec succès', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'amount' => $order->total_amount
        ]);
    }

    /**
     * Traiter une location réussie - gère les deux types de paiement (location et caution)
     */
    private function processSuccessfulRental(int $orderLocationId, PaymentIntent $paymentIntent): void
    {
        $orderLocation = OrderLocation::findOrFail($orderLocationId);
        $metadata = $paymentIntent->metadata ?? [];
        $paymentType = $metadata['payment_type'] ?? 'rental_payment';
        
        if ($paymentType === 'rental_payment') {
            // Traitement du paiement de location
            $orderLocation->update([
                'payment_status' => 'paid',
                'status' => 'confirmed',
                'confirmed_at' => now(),
                'paid_at' => now(),
                'stripe_payment_intent_id' => $paymentIntent->id,
                'payment_method' => 'stripe',
                'payment_details' => [
                    'stripe_payment_intent_id' => $paymentIntent->id,
                    'amount_paid' => $this->convertFromStripeAmount($paymentIntent->amount),
                    'currency' => $paymentIntent->currency,
                    'paid_at' => now()->toISOString()
                ]
            ]);

            // ⚠️ IMPORTANT: Le stock sera décrémenté SEULEMENT quand l'utilisateur confirmera côté frontend
            // Cela évite le décrément prématuré si l'utilisateur quitte avant la page de succès
            Log::info('Paiement webhook traité - Stock sera décrémenté lors de la confirmation frontend', [
                'order_location_id' => $orderLocation->id,
                'payment_confirmed' => true,
                'frontend_confirmed' => $orderLocation->frontend_confirmed ?? false,
                'note' => 'Stock NON décrémenté ici - attente confirmation frontend'
            ]);

            // Programmer les tâches automatiques pour cette location
            $this->scheduleRentalTasks($orderLocation);
            
            Log::info("Paiement de location traité avec succès", [
                'order_location_id' => $orderLocationId,
                'payment_intent_id' => $paymentIntent->id,
                'amount' => $paymentIntent->amount / 100
            ]);
            
        } elseif ($paymentType === 'deposit_authorization') {
            // Traitement de la préautorisation de caution
            $orderLocation->update([
                'stripe_deposit_authorization_id' => $paymentIntent->id,
                'deposit_status' => 'authorized'
            ]);
            
            Log::info("Préautorisation de caution traitée avec succès", [
                'order_location_id' => $orderLocationId,
                'authorization_id' => $paymentIntent->id,
                'amount' => $paymentIntent->amount / 100
            ]);
        }

        Log::info('Location payée avec succès', [
            'order_location_id' => $orderLocation->id,
            'order_number' => $orderLocation->order_number,
            'amount' => $orderLocation->total_amount,
            'rental_period' => $orderLocation->start_date->format('Y-m-d') . ' - ' . $orderLocation->end_date->format('Y-m-d')
        ]);
    }

    /**
     * Programmer les tâches automatiques pour une location
     */
    private function scheduleRentalTasks(OrderLocation $orderLocation): void
    {
        try {
            // Programmer les notifications selon les dates de location
            $this->scheduleRentalNotifications($orderLocation);

            Log::info('Tâches de location programmées (email géré par le listener)', [
                'order_location_id' => $orderLocation->id,
                'user_email' => $orderLocation->user->email
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la programmation des tâches de location', [
                'order_location_id' => $orderLocation->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Programmer les notifications automatiques de location
     */
    private function scheduleRentalNotifications(OrderLocation $orderLocation): void
    {
        $startDate = $orderLocation->start_date;
        $endDate = $orderLocation->end_date;
        $now = now();

        // Notification de début de location (si la location commence dans le futur)
        if ($startDate->isAfter($now)) {
            // Job pour marquer la location comme "en cours" au début
            \App\Jobs\StartRentalJob::dispatch($orderLocation)->delay($startDate);
            
            Log::info('Job de début de location programmé', [
                'order_location_id' => $orderLocation->id,
                'scheduled_for' => $startDate->toISOString()
            ]);
        } elseif ($startDate->isToday() || $startDate->isPast()) {
            // Si la location commence aujourd'hui ou était censée commencer avant, la marquer comme en cours
            $orderLocation->update([
                'status' => 'active',
                'started_at' => $startDate->isPast() ? $startDate : now()
            ]);
        }

        // Notification de rappel 1 jour avant la fin
        $reminderDate = $endDate->copy()->subDay();
        if ($reminderDate->isAfter($now)) {
            \App\Jobs\RentalEndReminderJob::dispatch($orderLocation)->delay($reminderDate);
            
            Log::info('Job de rappel de fin de location programmé', [
                'order_location_id' => $orderLocation->id,
                'scheduled_for' => $reminderDate->toISOString()
            ]);
        }

        // Notification de fin de location et demande de retour
        if ($endDate->isAfter($now)) {
            \App\Jobs\EndRentalJob::dispatch($orderLocation)->delay($endDate);
            
            Log::info('Job de fin de location programmé', [
                'order_location_id' => $orderLocation->id,
                'scheduled_for' => $endDate->toISOString()
            ]);
        }

        // Notification de retard si applicable (1 jour après la fin)
        $overdueDate = $endDate->copy()->addDay();
        if ($overdueDate->isAfter($now)) {
            \App\Jobs\RentalOverdueJob::dispatch($orderLocation)->delay($overdueDate);
            
            Log::info('Job de retard de location programmé', [
                'order_location_id' => $orderLocation->id,
                'scheduled_for' => $overdueDate->toISOString()
            ]);
        }
    }

    /**
     * Annuler une commande et rembourser le stock
     */
    public function cancelOrderAndRefundStock(Order $order): bool
    {
        try {
            DB::beginTransaction();

            // Ne restaurer le stock QUE si la commande était confirmée/payée 
            // (et donc le stock avait été prélevé)
            $shouldRestoreStock = in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered']);
            
            if ($shouldRestoreStock) {
                Log::info('Restoration du stock car commande était confirmée', [
                    'order_id' => $order->id,
                    'order_status' => $order->status
                ]);
                
                // Restaurer le stock pour chaque item
                foreach ($order->items as $item) {
                    $product = $item->product;
                    if ($product) {
                        $product->increment('quantity', $item->quantity);
                        
                        Log::info('Stock restauré lors de l\'annulation', [
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'previous_quantity' => $product->quantity - $item->quantity,
                            'new_quantity' => $product->quantity,
                            'restored_by' => $item->quantity,
                            'order_id' => $order->id
                        ]);
                    }
                }
            } else {
                Log::info('Pas de restoration de stock car commande était en attente', [
                    'order_id' => $order->id,
                    'order_status' => $order->status,
                    'note' => 'Le stock n\'avait jamais été prélevé'
                ]);
            }

            // Mettre à jour le statut de la commande
            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now()
            ]);

            DB::commit();
            
            Log::info('Commande annulée avec succès', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'stock_restored' => $shouldRestoreStock
            ]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de l\'annulation de la commande', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Traiter le retour d'une location
     */
    public function processRentalReturn(OrderLocation $orderLocation): bool
    {
        try {
            DB::beginTransaction();

            // Restaurer le stock pour chaque item de location
            foreach ($orderLocation->items as $item) {
                $product = $item->product;
                if ($product) {
                    $product->increment('quantity', $item->quantity);
                    
                    Log::info('Stock restauré lors du retour de location', [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'previous_quantity' => $product->quantity - $item->quantity,
                        'new_quantity' => $product->quantity,
                        'restored_by' => $item->quantity,
                        'order_location_id' => $orderLocation->id
                    ]);
                }
            }

            // Mettre à jour le statut de la location
            $orderLocation->update([
                'status' => 'finished',
                'returned_at' => now()
            ]);

            DB::commit();
            
            Log::info('Location retournée avec succès', [
                'order_location_id' => $orderLocation->id,
                'order_number' => $orderLocation->order_number
            ]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors du retour de location', [
                'order_location_id' => $orderLocation->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Gérer les webhooks Stripe
     */
    public function handleWebhook(string $payload, string $signature): bool
    {
        try {
            // Log détaillé pour debug
            Log::info('🔍 DEBUG Webhook', [
                'webhook_secret_from_config' => config('services.stripe.webhook.secret'),
                'webhook_secret_from_env' => env('STRIPE_WEBHOOK_SECRET'),
                'signature_header' => $signature,
                'payload_preview' => substr($payload, 0, 100)
            ]);

            $event = Webhook::constructEvent(
                $payload,
                $signature,
                config('services.stripe.webhook.secret')
            );

            // Log de TOUS les événements reçus
            error_log("🎯 WEBHOOK REÇU: " . $event->type . " - ID: " . $event->id);
            Log::info('Webhook Stripe reçu', [
                'event_type' => $event->type,
                'event_id' => $event->id,
                'payment_intent_id' => $event->data->object->id ?? 'N/A',
                'metadata' => $event->data->object->metadata ?? []
            ]);

            switch ($event->type) {
                case 'payment_intent.succeeded':
                    error_log("💰 TRAITEMENT payment_intent.succeeded - ID: " . $event->data->object->id);
                    Log::info('🔥 PROCESSING payment_intent.succeeded', [
                        'payment_intent_id' => $event->data->object->id,
                        'metadata' => $event->data->object->metadata->toArray()
                    ]);
                    $this->handleSuccessfulPayment($event->data->object->id);
                    break;
                
                case 'payment_intent.payment_failed':
                    error_log("❌ TRAITEMENT payment_intent.payment_failed");
                    $this->handleFailedPayment($event->data->object->id);
                    break;
                
                case 'payment_intent.created':
                    error_log("📝 TRAITEMENT payment_intent.created - PAS D'ACTION");
                    break;
                
                default:
                    error_log("❓ WEBHOOK NON GÉRÉ: " . $event->type);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('💥 ERREUR WEBHOOK DÉTAILLÉE', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
            error_log("💥 ERREUR WEBHOOK: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Gérer un paiement échoué
     */
    private function handleFailedPayment(string $paymentIntentId): void
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            $metadata = $paymentIntent->metadata->toArray();

            if ($metadata['order_type'] === 'purchase') {
                $order = Order::find($metadata['order_id']);
                if ($order) {
                    $order->update(['payment_status' => 'failed']);
                }
            } elseif ($metadata['order_type'] === 'rental') {
                $orderLocation = OrderLocation::find($metadata['order_id']);
                if ($orderLocation) {
                    $orderLocation->update(['payment_status' => 'failed']);
                }
            }

            Log::warning('Paiement Stripe échoué', [
                'payment_intent_id' => $paymentIntentId,
                'order_type' => $metadata['order_type'] ?? 'unknown',
                'order_id' => $metadata['order_id'] ?? 'unknown'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement d\'un paiement échoué', [
                'payment_intent_id' => $paymentIntentId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Convertir un montant en centimes pour Stripe
     */
    public function convertToStripeAmount(float $amount): int
    {
        return (int) round($amount * 100);
    }

    /**
     * Convertir un montant Stripe en euros
     */
    public function convertFromStripeAmount(int $stripeAmount): float
    {
        return $stripeAmount / 100;
    }

    /**
     * Annuler une location et rembourser le stock
     */
    public function cancelRentalAndRefundStock(OrderLocation $orderLocation): bool
    {
        try {
            DB::beginTransaction();

            // Vérifier si la location peut être annulée (avant le début)
            $canCancel = now()->lt($orderLocation->start_date);
            
            $cancellationReason = $canCancel ? 'cancelled_before_start' : 'cancelled_during_rental';
            
            // Utiliser la méthode cancel du modèle qui gère correctement le stock
            $orderLocation->cancel($cancellationReason);

            DB::commit();
            
            Log::info('Location annulée avec succès', [
                'order_location_id' => $orderLocation->id,
                'order_number' => $orderLocation->order_number,
                'cancelled_before_start' => $canCancel,
                'stock_restored' => $canCancel
            ]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de l\'annulation de location', [
                'order_location_id' => $orderLocation->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Traiter un remboursement automatique pour une commande retournée
     */
    public function processAutomaticRefund(Order $order): bool
    {
        try {
            if (!$order->stripe_payment_intent_id) {
                Log::error('Impossible de rembourser: aucun PaymentIntent trouvé', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number
                ]);
                return false;
            }

            // Créer le remboursement via Stripe
            $refund = \Stripe\Refund::create([
                'payment_intent' => $order->stripe_payment_intent_id,
                'amount' => $this->convertToStripeAmount($order->total_amount),
                'reason' => 'requested_by_customer',
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'refund_type' => 'automatic_return',
                    'user_id' => $order->user_id,
                    'return_reason' => $order->return_reason ?? 'Retour automatique'
                ]
            ]);

            // Mettre à jour la commande
            $order->update([
                'refund_processed' => true,
                'refund_amount' => $this->convertFromStripeAmount($refund->amount),
                'refund_id' => $refund->id,
                'refunded_at' => now()
            ]);

            Log::info('Remboursement automatique traité', [
                'order_id' => $order->id,
                'refund_id' => $refund->id,
                'amount' => $refund->amount / 100
            ]);

            return true;
                
        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement du remboursement automatique', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Capturer une préautorisation de caution (en cas de dommages)
     */
    public function captureDepositAuthorization(OrderLocation $orderLocation, float $amount = null): bool
    {
        try {
            if (!$orderLocation->stripe_deposit_authorization_id) {
                Log::error('Impossible de capturer: aucune préautorisation trouvée', [
                    'order_location_id' => $orderLocation->id,
                    'order_number' => $orderLocation->order_number
                ]);
                return false;
            }

            // Récupérer le PaymentIntent de préautorisation
            $paymentIntent = PaymentIntent::retrieve($orderLocation->stripe_deposit_authorization_id);
            
            // Si pas de montant spécifié, capturer le montant total autorisé
            if ($amount === null) {
                $captureAmount = $paymentIntent->amount;
            } else {
                $captureAmount = $this->convertToStripeAmount($amount);
                // S'assurer que le montant ne dépasse pas l'autorisation
                if ($captureAmount > $paymentIntent->amount) {
                    $captureAmount = $paymentIntent->amount;
                }
            }

            // Capturer le paiement
            $capturedPayment = $paymentIntent->capture([
                'amount_to_capture' => $captureAmount
            ]);

            // Mettre à jour la location
            $orderLocation->update([
                'deposit_status' => 'captured',
                'deposit_captured_amount' => $this->convertFromStripeAmount($captureAmount),
                'deposit_captured_at' => now()
            ]);

            Log::info('Préautorisation de caution capturée', [
                'order_location_id' => $orderLocation->id,
                'authorization_id' => $orderLocation->stripe_deposit_authorization_id,
                'captured_amount' => $captureAmount / 100
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Erreur lors de la capture de préautorisation', [
                'order_location_id' => $orderLocation->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Annuler une préautorisation de caution (retour sans dommages)
     */
    public function cancelDepositAuthorization(OrderLocation $orderLocation): bool
    {
        try {
            if (!$orderLocation->stripe_deposit_authorization_id) {
                Log::error('Impossible d\'annuler: aucune préautorisation trouvée', [
                    'order_location_id' => $orderLocation->id,
                    'order_number' => $orderLocation->order_number
                ]);
                return false;
            }

            // Récupérer et annuler le PaymentIntent
            $paymentIntent = PaymentIntent::retrieve($orderLocation->stripe_deposit_authorization_id);
            $cancelledPayment = $paymentIntent->cancel();

            // Mettre à jour la location
            $orderLocation->update([
                'deposit_status' => 'cancelled',
                'deposit_cancelled_at' => now()
            ]);

            Log::info('Préautorisation de caution annulée', [
                'order_location_id' => $orderLocation->id,
                'authorization_id' => $orderLocation->stripe_deposit_authorization_id
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'annulation de préautorisation', [
                'order_location_id' => $orderLocation->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Décrémenter le stock quand l'utilisateur confirme côté frontend
     */
    public function decrementStockOnFrontendConfirmation(OrderLocation $orderLocation): bool
    {
        if ($orderLocation->frontend_confirmed) {
            Log::info('Stock déjà décrémenté pour cette commande', [
                'order_location_id' => $orderLocation->id,
                'order_number' => $orderLocation->order_number
            ]);
            return true;
        }

        $success = true;
        
        // Décrémenter le stock de location des produits
        foreach ($orderLocation->items as $item) {
            $product = $item->product;
            if ($product && $product->rental_stock >= $item->quantity) {
                $newRentalStock = $product->rental_stock - $item->quantity;
                $product->update(['rental_stock' => $newRentalStock]);
                
                Log::info('Stock de location décrémenté (confirmation frontend)', [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'previous_rental_stock' => $product->rental_stock + $item->quantity,
                    'new_rental_stock' => $newRentalStock,
                    'decremented_by' => $item->quantity,
                    'order_location_id' => $orderLocation->id,
                    'rental_period' => $orderLocation->start_date->format('Y-m-d') . ' - ' . $orderLocation->end_date->format('Y-m-d')
                ]);
            } else {
                Log::warning('Stock de location insuffisant lors de la confirmation frontend', [
                    'product_id' => $product?->id,
                    'product_name' => $product?->name,
                    'available_rental_stock' => $product?->rental_stock,
                    'requested_quantity' => $item->quantity
                ]);
                $success = false;
            }
        }

        if ($success) {
            // Marquer comme confirmé côté frontend
            $orderLocation->update([
                'frontend_confirmed' => true,
                'frontend_confirmed_at' => now()
            ]);
            
            Log::info('Commande confirmée côté frontend avec décrément de stock', [
                'order_location_id' => $orderLocation->id,
                'order_number' => $orderLocation->order_number
            ]);
        }

        return $success;
    }
}
