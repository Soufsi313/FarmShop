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
     * Créer un PaymentIntent pour une location
     */
    public function createPaymentIntentForRental(OrderLocation $orderLocation): PaymentIntent
    {
        $paymentIntent = PaymentIntent::create([
            'amount' => $this->convertToStripeAmount($orderLocation->total_amount),
            'currency' => 'eur',
            'description' => "Location #{$orderLocation->order_number}",
            'metadata' => [
                'order_id' => $orderLocation->id,
                'order_type' => 'rental',
                'order_number' => $orderLocation->order_number,
                'user_id' => $orderLocation->user_id,
                'user_email' => $orderLocation->user->email ?? ''
            ],
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);

        // Sauvegarder l'ID du PaymentIntent
        $orderLocation->update([
            'stripe_payment_intent_id' => $paymentIntent->id,
            'payment_status' => 'pending'
        ]);

        return $paymentIntent;
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
                $this->processSuccessfulRental($orderLocationId, $paymentIntent);
                
                return [
                    'success' => true,
                    'order_type' => 'rental',
                    'order_id' => $orderLocationId,
                    'redirect_url' => route('rentals.confirmation', $orderLocationId)
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
     * Traiter une location réussie
     */
    private function processSuccessfulRental(int $orderLocationId, PaymentIntent $paymentIntent): void
    {
        $orderLocation = OrderLocation::findOrFail($orderLocationId);
        
        // Mettre à jour le statut de paiement
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

        // Décrémenter le stock des produits pour la période de location
        foreach ($orderLocation->items as $item) {
            $product = $item->product;
            if ($product && $product->quantity >= $item->quantity) {
                $newQuantity = $product->quantity - $item->quantity;
                $product->update(['quantity' => $newQuantity]);
                
                Log::info('Stock décrémenté pour location', [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'previous_quantity' => $product->quantity + $item->quantity,
                    'new_quantity' => $newQuantity,
                    'decremented_by' => $item->quantity,
                    'order_location_id' => $orderLocation->id,
                    'rental_period' => $orderLocation->start_date->format('Y-m-d') . ' - ' . $orderLocation->end_date->format('Y-m-d')
                ]);
            }
        }

        // Programmer les tâches automatiques pour cette location
        $this->scheduleRentalTasks($orderLocation);

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
            // 1. Envoyer l'email de confirmation immédiatement
            \Mail::to($orderLocation->user->email)->send(new \App\Mail\RentalConfirmationMail($orderLocation));
            
            Log::info('Email de confirmation de location envoyé', [
                'order_location_id' => $orderLocation->id,
                'user_email' => $orderLocation->user->email
            ]);

            // 2. Programmer les notifications selon les dates de location
            $this->scheduleRentalNotifications($orderLocation);

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
                'status' => 'in_progress',
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

            // Mettre à jour le statut de la commande
            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now()
            ]);

            DB::commit();
            
            Log::info('Commande annulée avec succès', [
                'order_id' => $order->id,
                'order_number' => $order->order_number
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
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                config('services.stripe.webhook.secret')
            );

            // Log de TOUS les événements reçus
            error_log("🎯 WEBHOOK REÇU: " . $event->type . " - ID: " . $event->id);

            switch ($event->type) {
                case 'payment_intent.succeeded':
                    error_log("💰 TRAITEMENT payment_intent.succeeded - ID: " . $event->data->object->id);
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
            
            if ($canCancel) {
                // Restaurer le stock pour chaque item de location
                foreach ($orderLocation->items as $item) {
                    $product = $item->product;
                    if ($product) {
                        $product->increment('quantity', $item->quantity);
                        
                        Log::info('Stock restauré lors de l\'annulation de location', [
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'previous_quantity' => $product->quantity - $item->quantity,
                            'new_quantity' => $product->quantity,
                            'restored_by' => $item->quantity,
                            'order_location_id' => $orderLocation->id,
                            'cancelled_before_start' => true
                        ]);
                    }
                }
            }

            // Mettre à jour le statut de la location
            $orderLocation->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $canCancel ? 'cancelled_before_start' : 'cancelled_during_rental'
            ]);

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
                'refund_processed_at' => now(),
                'refund_stripe_id' => $refund->id
            ]);

            Log::info('Remboursement automatique traité avec succès', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'refund_id' => $refund->id,
                'amount' => $order->total_amount
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Erreur lors du remboursement automatique', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
