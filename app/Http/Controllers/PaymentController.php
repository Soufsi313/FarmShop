<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Afficher la page de paiement Stripe après checkout
     */
    public function showPayment(Order $order)
    {
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cette commande');
        }

        // Vérifier que la commande est en attente de paiement
        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Cette commande ne peut plus être payée');
        }

        // Charger les items avec les produits et leurs offres spéciales
        $order->load(['items.product.specialOffers']);

        // Calculer le détail complet de la commande
        $orderDetails = $this->calculateOrderDetails($order);

        return view('payment.stripe', compact('order', 'orderDetails'));
    }

    /**
     * Traiter le paiement Stripe
     */
    public function processPayment(Request $request, Order $order)
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

            // Créer l'intention de paiement Stripe
            $paymentIntent = $this->stripeService->createPaymentIntentForOrder($order);

            return response()->json([
                'success' => true,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement du paiement', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du traitement du paiement'
            ], 500);
        }
    }

    /**
     * Page de confirmation après paiement réussi
     */
    public function paymentSuccess(Order $order)
    {
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cette commande');
        }

        // Vérifier que la commande a été payée
        if ($order->payment_status !== 'paid') {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Le paiement de cette commande n\'a pas été confirmé');
        }

        return view('payment.success', compact('order'));
    }

    /**
     * Page d'annulation de paiement
     */
    public function paymentCancel(Order $order)
    {
        return view('payment.cancel', compact('order'));
    }

    /**
     * Calculer les détails complets de la commande avec offres spéciales
     */
    private function calculateOrderDetails(Order $order)
    {
        $subtotal = 0;
        $totalDiscount = 0;
        $itemsDetails = [];

        foreach ($order->items as $item) {
            $product = $item->product;
            $unitPrice = $item->unit_price;
            $quantity = $item->quantity;
            $lineTotal = $unitPrice * $quantity;

            // Calculer les remises d'offres spéciales appliquées
            $discount = 0;
            $appliedOffers = [];

            if ($product->specialOffers) {
                foreach ($product->specialOffers as $offer) {
                    if ($offer->is_active && $offer->isValidNow()) {
                        $offerDiscount = $offer->calculateDiscount($lineTotal);
                        $discount += $offerDiscount;
                        $appliedOffers[] = [
                            'name' => $offer->name,
                            'discount' => $offerDiscount
                        ];
                    }
                }
            }

            $lineTotalAfterDiscount = $lineTotal - $discount;

            $itemsDetails[] = [
                'product_name' => $product->name,
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'line_total' => $lineTotal,
                'discount' => $discount,
                'line_total_after_discount' => $lineTotalAfterDiscount,
                'applied_offers' => $appliedOffers
            ];

            $subtotal += $lineTotal;
            $totalDiscount += $discount;
        }

        $totalHTC = $subtotal - $totalDiscount;
        
        // Calculer les frais de livraison
        $shippingCost = 0;
        if ($totalHTC < 25) {
            $shippingCost = 5; // 5€ de frais de livraison si commande < 25€
        }
        
        $totalHTC_withShipping = $totalHTC + $shippingCost;
        $taxRate = 0.21; // 21% TVA (ajustez selon vos besoins)
        $taxAmount = $totalHTC_withShipping * $taxRate;
        $totalTTC = $totalHTC_withShipping + $taxAmount;

        return [
            'items' => $itemsDetails,
            'subtotal' => $subtotal,
            'total_discount' => $totalDiscount,
            'total_htc' => $totalHTC,
            'shipping_cost' => $shippingCost,
            'total_htc_with_shipping' => $totalHTC_withShipping,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total_ttc' => $totalTTC
        ];
    }
}
