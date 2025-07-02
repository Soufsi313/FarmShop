<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\Order;
use App\Models\User;

class StripeWebhookController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('stripe.secret_key'));
    }

    /**
     * Gérer les webhooks Stripe
     */
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
            
            Log::info('Stripe webhook received', [
                'type' => $event->type,
                'id' => $event->id
            ]);

            // Traiter l'événement selon son type
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $this->handlePaymentIntentSucceeded($event->data->object);
                    break;
                    
                case 'payment_intent.payment_failed':
                    $this->handlePaymentIntentFailed($event->data->object);
                    break;
                    
                case 'payment_method.attached':
                    $this->handlePaymentMethodAttached($event->data->object);
                    break;
                    
                case 'customer.subscription.created':
                    $this->handleSubscriptionCreated($event->data->object);
                    break;
                    
                case 'customer.subscription.updated':
                    $this->handleSubscriptionUpdated($event->data->object);
                    break;
                    
                case 'customer.subscription.deleted':
                    $this->handleSubscriptionDeleted($event->data->object);
                    break;
                    
                case 'invoice.payment_succeeded':
                    $this->handleInvoicePaymentSucceeded($event->data->object);
                    break;
                    
                case 'invoice.payment_failed':
                    $this->handleInvoicePaymentFailed($event->data->object);
                    break;
                    
                default:
                    Log::info('Unhandled Stripe webhook event', ['type' => $event->type]);
            }

            return response()->json(['status' => 'success'], 200);

        } catch (\UnexpectedValueException $e) {
            // Signature invalide
            Log::error('Invalid Stripe webhook signature', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
            
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Signature invalide
            Log::error('Stripe signature verification failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
            
        } catch (\Exception $e) {
            Log::error('Stripe webhook error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Webhook error'], 500);
        }
    }

    /**
     * Gérer le succès d'un PaymentIntent
     */
    private function handlePaymentIntentSucceeded($paymentIntent)
    {
        Log::info('Payment intent succeeded', [
            'payment_intent_id' => $paymentIntent->id,
            'amount' => $paymentIntent->amount,
            'currency' => $paymentIntent->currency
        ]);

        // Trouver la commande correspondante
        $order = Order::where('payment_intent_id', $paymentIntent->id)->first();
        
        if ($order) {
            // Mettre à jour le statut de la commande
            $order->update([
                'status' => 'paid',
                'paid_at' => now(),
                'payment_confirmed' => true,
            ]);
            
            Log::info('Order status updated to paid', ['order_id' => $order->id]);
            
            // Envoyer une notification à l'utilisateur (optionnel)
            // $this->sendPaymentConfirmationNotification($order);
        } else {
            Log::warning('Order not found for payment intent', [
                'payment_intent_id' => $paymentIntent->id
            ]);
        }
    }

    /**
     * Gérer l'échec d'un PaymentIntent
     */
    private function handlePaymentIntentFailed($paymentIntent)
    {
        Log::warning('Payment intent failed', [
            'payment_intent_id' => $paymentIntent->id,
            'amount' => $paymentIntent->amount,
            'currency' => $paymentIntent->currency,
            'last_payment_error' => $paymentIntent->last_payment_error
        ]);

        // Trouver la commande correspondante
        $order = Order::where('payment_intent_id', $paymentIntent->id)->first();
        
        if ($order) {
            // Mettre à jour le statut de la commande
            $order->update([
                'status' => 'payment_failed',
                'payment_error' => $paymentIntent->last_payment_error->message ?? 'Payment failed',
            ]);
            
            Log::info('Order status updated to payment_failed', ['order_id' => $order->id]);
            
            // Envoyer une notification à l'utilisateur (optionnel)
            // $this->sendPaymentFailedNotification($order);
        }
    }

    /**
     * Gérer l'attachement d'une méthode de paiement
     */
    private function handlePaymentMethodAttached($paymentMethod)
    {
        Log::info('Payment method attached', [
            'payment_method_id' => $paymentMethod->id,
            'customer_id' => $paymentMethod->customer,
            'type' => $paymentMethod->type
        ]);

        // Optionnel: sauvegarder les détails de la méthode de paiement
        if ($paymentMethod->customer) {
            $user = User::where('stripe_customer_id', $paymentMethod->customer)->first();
            if ($user) {
                // Mettre à jour les informations de paiement de l'utilisateur
                Log::info('Payment method attached to user', [
                    'user_id' => $user->id,
                    'payment_method_id' => $paymentMethod->id
                ]);
            }
        }
    }

    /**
     * Gérer la création d'un abonnement
     */
    private function handleSubscriptionCreated($subscription)
    {
        Log::info('Subscription created', [
            'subscription_id' => $subscription->id,
            'customer_id' => $subscription->customer,
            'status' => $subscription->status
        ]);

        // Logique pour gérer les abonnements de location récurrente
        // À implémenter selon les besoins
    }

    /**
     * Gérer la mise à jour d'un abonnement
     */
    private function handleSubscriptionUpdated($subscription)
    {
        Log::info('Subscription updated', [
            'subscription_id' => $subscription->id,
            'customer_id' => $subscription->customer,
            'status' => $subscription->status
        ]);

        // Logique pour gérer les mises à jour d'abonnements
        // À implémenter selon les besoins
    }

    /**
     * Gérer la suppression d'un abonnement
     */
    private function handleSubscriptionDeleted($subscription)
    {
        Log::info('Subscription deleted', [
            'subscription_id' => $subscription->id,
            'customer_id' => $subscription->customer,
            'status' => $subscription->status
        ]);

        // Logique pour gérer l'annulation d'abonnements
        // À implémenter selon les besoins
    }

    /**
     * Gérer le succès d'un paiement de facture
     */
    private function handleInvoicePaymentSucceeded($invoice)
    {
        Log::info('Invoice payment succeeded', [
            'invoice_id' => $invoice->id,
            'customer_id' => $invoice->customer,
            'amount_paid' => $invoice->amount_paid,
            'subscription_id' => $invoice->subscription
        ]);

        // Logique pour les paiements récurrents de location
        // À implémenter selon les besoins
    }

    /**
     * Gérer l'échec d'un paiement de facture
     */
    private function handleInvoicePaymentFailed($invoice)
    {
        Log::warning('Invoice payment failed', [
            'invoice_id' => $invoice->id,
            'customer_id' => $invoice->customer,
            'amount_due' => $invoice->amount_due,
            'subscription_id' => $invoice->subscription
        ]);

        // Logique pour gérer les échecs de paiement récurrents
        // À implémenter selon les besoins
    }

    /**
     * Envoyer une notification de confirmation de paiement
     */
    private function sendPaymentConfirmationNotification($order)
    {
        // Implémentation de l'envoi de notification
        // Par exemple: envoi d'email, notification push, etc.
        Log::info('Payment confirmation notification sent', ['order_id' => $order->id]);
    }

    /**
     * Envoyer une notification d'échec de paiement
     */
    private function sendPaymentFailedNotification($order)
    {
        // Implémentation de l'envoi de notification d'échec
        Log::info('Payment failed notification sent', ['order_id' => $order->id]);
    }
}
