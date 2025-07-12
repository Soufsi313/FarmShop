<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use App\Models\Order;
use App\Models\OrderLocation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StripePaymentController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Créer une intention de paiement pour un achat
     */
    public function createPaymentIntentForPurchase(Request $request, Order $order): JsonResponse
    {
        try {
            // Vérifier que la commande appartient à l'utilisateur connecté
            if ($order->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Commande non autorisée'
                ], 403);
            }

            // Vérifier que la commande est en attente
            if ($order->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette commande ne peut plus être payée'
                ], 400);
            }

            $paymentIntent = $this->stripeService->createPaymentIntentForOrder($order);

            return response()->json([
                'success' => true,
                'message' => 'Intention de paiement créée avec succès',
                'data' => [
                    'client_secret' => $paymentIntent->client_secret,
                    'payment_intent_id' => $paymentIntent->id,
                    'amount' => $this->stripeService->convertFromStripeAmount($paymentIntent->amount),
                    'currency' => $paymentIntent->currency,
                    'order' => [
                        'id' => $order->id,
                        'order_number' => $order->order_number,
                        'total_amount' => $order->total_amount,
                        'items_count' => $order->items->count()
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de l\'intention de paiement pour achat', [
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du paiement'
            ], 500);
        }
    }

    /**
     * Créer une intention de paiement pour une location
     */
    public function createPaymentIntentForRental(Request $request, OrderLocation $orderLocation): JsonResponse
    {
        try {
            // Vérifier que la commande appartient à l'utilisateur connecté
            if ($orderLocation->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Commande non autorisée'
                ], 403);
            }

            // Vérifier que la commande est en attente
            if ($orderLocation->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette commande ne peut plus être payée'
                ], 400);
            }

            $paymentIntent = $this->stripeService->createPaymentIntentForRental($orderLocation);

            return response()->json([
                'success' => true,
                'message' => 'Intention de paiement créée avec succès',
                'data' => [
                    'client_secret' => $paymentIntent->client_secret,
                    'payment_intent_id' => $paymentIntent->id,
                    'amount' => $this->stripeService->convertFromStripeAmount($paymentIntent->amount),
                    'currency' => $paymentIntent->currency,
                    'order' => [
                        'id' => $orderLocation->id,
                        'order_number' => $orderLocation->order_number,
                        'total_amount' => $orderLocation->total_amount,
                        'deposit_amount' => $orderLocation->deposit_amount,
                        'total_to_pay' => $orderLocation->total_amount + $orderLocation->deposit_amount,
                        'start_date' => $orderLocation->start_date,
                        'end_date' => $orderLocation->end_date,
                        'items_count' => $orderLocation->items->count()
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de l\'intention de paiement pour location', [
                'order_location_id' => $orderLocation->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du paiement'
            ], 500);
        }
    }

    /**
     * Confirmer le paiement
     */
    public function confirmPayment(Request $request): JsonResponse
    {
        $request->validate([
            'payment_intent_id' => 'required|string'
        ]);

        try {
            $success = $this->stripeService->handleSuccessfulPayment($request->payment_intent_id);

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Paiement confirmé avec succès'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la confirmation du paiement'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de la confirmation du paiement', [
                'payment_intent_id' => $request->payment_intent_id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la confirmation du paiement'
            ], 500);
        }
    }

    /**
     * Webhook Stripe
     */
    public function webhook(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        if (!$signature) {
            Log::warning('Webhook Stripe sans signature');
            return response()->json(['error' => 'No signature'], 400);
        }

        $success = $this->stripeService->handleWebhook($payload, $signature);

        if ($success) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['error' => 'Webhook handling failed'], 400);
        }
    }

    /**
     * Annuler une commande d'achat
     */
    public function cancelPurchaseOrder(Order $order): JsonResponse
    {
        try {
            // Vérifier que la commande appartient à l'utilisateur connecté
            if ($order->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Commande non autorisée'
                ], 403);
            }

            // Vérifier que la commande peut être annulée
            if (!in_array($order->status, ['pending', 'confirmed'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette commande ne peut pas être annulée'
                ], 400);
            }

            $success = $this->stripeService->cancelOrderAndRefundStock($order);

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Commande annulée avec succès, stock restauré'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'annulation de la commande'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'annulation de commande d\'achat', [
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'annulation de la commande'
            ], 500);
        }
    }

    /**
     * Annuler une commande de location
     */
    public function cancelRentalOrder(OrderLocation $orderLocation): JsonResponse
    {
        try {
            // Vérifier que la commande appartient à l'utilisateur connecté
            if ($orderLocation->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Commande non autorisée'
                ], 403);
            }

            // Vérifier que la commande peut être annulée
            if (!in_array($orderLocation->status, ['pending', 'confirmed'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette commande ne peut pas être annulée'
                ], 400);
            }

            $success = $this->stripeService->cancelRentalAndRefundStock($orderLocation);

            if ($success) {
                // Si un paiement Stripe existe, traiter le remboursement
                if ($orderLocation->stripe_payment_intent_id) {
                    try {
                        $refund = \Stripe\Refund::create([
                            'payment_intent' => $orderLocation->stripe_payment_intent_id,
                            'reason' => 'requested_by_customer',
                            'metadata' => [
                                'order_location_id' => $orderLocation->id,
                                'order_number' => $orderLocation->order_number,
                                'cancelled_before_start' => now()->lt($orderLocation->start_date) ? 'true' : 'false'
                            ]
                        ]);

                        $orderLocation->update([
                            'payment_status' => 'refunded',
                            'refund_id' => $refund->id
                        ]);

                        Log::info('Remboursement Stripe traité pour annulation de location', [
                            'order_location_id' => $orderLocation->id,
                            'refund_id' => $refund->id,
                            'amount' => $refund->amount
                        ]);

                    } catch (\Exception $e) {
                        Log::error('Erreur lors du remboursement Stripe', [
                            'order_location_id' => $orderLocation->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }

                $canCancel = now()->lt($orderLocation->start_date);
                $message = $canCancel 
                    ? 'Commande de location annulée avec succès, stock restauré' 
                    : 'Commande de location annulée avec succès';

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'stock_restored' => $canCancel
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'annulation de la commande'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'annulation de commande de location', [
                'order_location_id' => $orderLocation->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'annulation de la commande'
            ], 500);
        }
    }

    /**
     * Marquer une location comme retournée
     */
    public function markRentalAsReturned(OrderLocation $orderLocation): JsonResponse
    {
        try {
            // Cette action est généralement réservée aux admins
            if (!Auth::user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Action non autorisée'
                ], 403);
            }

            // Vérifier que la location est en cours
            if ($orderLocation->status !== 'confirmed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette location ne peut pas être marquée comme retournée'
                ], 400);
            }

            $success = $this->stripeService->processRentalReturn($orderLocation);

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Location marquée comme retournée, stock restauré'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du traitement du retour'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors du marquage de retour de location', [
                'order_location_id' => $orderLocation->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du traitement du retour'
            ], 500);
        }
    }

    /**
     * Obtenir les informations de paiement Stripe pour une commande
     */
    public function getPaymentInfo(Request $request): JsonResponse
    {
        $request->validate([
            'order_type' => 'required|in:purchase,rental',
            'order_id' => 'required|integer'
        ]);

        try {
            if ($request->order_type === 'purchase') {
                $order = Order::findOrFail($request->order_id);
                
                // Vérifier l'autorisation
                if ($order->user_id !== Auth::id()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Commande non autorisée'
                    ], 403);
                }

                return response()->json([
                    'success' => true,
                    'data' => [
                        'order_type' => 'purchase',
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'amount' => $order->total_amount,
                        'currency' => 'EUR',
                        'stripe_publishable_key' => config('services.stripe.key'),
                        'payment_status' => $order->payment_status,
                        'payment_intent_id' => $order->stripe_payment_intent_id
                    ]
                ]);

            } else { // rental
                $orderLocation = OrderLocation::findOrFail($request->order_id);
                
                // Vérifier l'autorisation
                if ($orderLocation->user_id !== Auth::id()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Commande non autorisée'
                    ], 403);
                }

                return response()->json([
                    'success' => true,
                    'data' => [
                        'order_type' => 'rental',
                        'order_id' => $orderLocation->id,
                        'order_number' => $orderLocation->order_number,
                        'rental_amount' => $orderLocation->total_amount,
                        'deposit_amount' => $orderLocation->deposit_amount,
                        'total_amount' => $orderLocation->total_amount + $orderLocation->deposit_amount,
                        'currency' => 'EUR',
                        'stripe_publishable_key' => config('services.stripe.key'),
                        'payment_status' => $orderLocation->payment_status,
                        'payment_intent_id' => $orderLocation->stripe_payment_intent_id
                    ]
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des informations de paiement', [
                'order_type' => $request->order_type,
                'order_id' => $request->order_id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des informations'
            ], 500);
        }
    }
}
