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
    public function handleSuccessfulPayment(string $paymentIntentId): bool
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            $metadata = $paymentIntent->metadata->toArray();

            if ($metadata['order_type'] === 'purchase') {
                $this->processSuccessfulPurchase((int)$metadata['order_id'], $paymentIntent);
            } elseif ($metadata['order_type'] === 'rental') {
                $this->processSuccessfulRental((int)$metadata['order_id'], $paymentIntent);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement d\'un paiement réussi', [
                'payment_intent_id' => $paymentIntentId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Traiter un achat réussi
     */
    private function processSuccessfulPurchase(int $orderId, PaymentIntent $paymentIntent): void
    {
        $order = Order::findOrFail($orderId);
        
        // Mettre à jour le statut de paiement
        $order->update([
            'payment_status' => 'paid',
            'status' => 'confirmed',
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
                    'rental_period' => $item->start_date . ' - ' . $item->end_date
                ]);
            }
        }

        Log::info('Location payée avec succès', [
            'order_location_id' => $orderLocation->id,
            'order_number' => $orderLocation->order_number,
            'amount' => $orderLocation->total_amount
        ]);
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
                config('services.stripe.webhook_secret')
            );

            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $this->handleSuccessfulPayment($event->data->object->id);
                    break;
                
                case 'payment_intent.payment_failed':
                    $this->handleFailedPayment($event->data->object->id);
                    break;
                
                default:
                    Log::info('Webhook Stripe non géré', ['type' => $event->type]);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement du webhook Stripe', [
                'error' => $e->getMessage()
            ]);
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
    private function convertToStripeAmount(float $amount): int
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
}
