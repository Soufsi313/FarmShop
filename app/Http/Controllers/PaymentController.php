<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Customer;
use Stripe\Exception\CardException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Exception\AuthenticationException;
use Stripe\Exception\ApiConnectionException;
use Stripe\Exception\ApiErrorException;
use App\Models\Order;
use App\Models\CartItem;
use App\Models\Product;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Stripe::setApiKey(config('stripe.secret_key'));
    }

    /**
     * Afficher la page de paiement
     */
    public function showPaymentForm(Request $request)
    {
        $user = Auth::user();
        
        // Récupérer les articles du panier
        $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        // Calculer le total avec les offres spéciales
        $total = 0;
        $totalSavings = 0;
        
        foreach ($cartItems as $item) {
            $itemTotal = $item->getTotalPrice();
            $total += $itemTotal;
            
            // Calculer les économies
            $originalPrice = $item->product->price * $item->quantity;
            $savings = $originalPrice - $itemTotal;
            $totalSavings += $savings;
        }

        // Convertir en centimes pour Stripe
        $amountInCents = intval($total * 100);

        return view('payment.form', compact('cartItems', 'total', 'totalSavings', 'amountInCents'));
    }

    /**
     * Créer un PaymentIntent pour le paiement
     */
    public function createPaymentIntent(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Récupérer les articles du panier
            $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();
            
            if ($cartItems->isEmpty()) {
                return response()->json(['error' => 'Panier vide'], 400);
            }

            // Calculer le total
            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item->getTotalPrice();
            }

            // Convertir en centimes
            $amountInCents = intval($total * 100);

            // Créer ou récupérer le client Stripe
            $stripeCustomer = $this->getOrCreateStripeCustomer($user);

            // Créer le PaymentIntent
            $paymentIntent = PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => config('stripe.currency'),
                'customer' => $stripeCustomer->id,
                'payment_method_types' => ['card'],
                'metadata' => [
                    'user_id' => $user->id,
                    'cart_items_count' => $cartItems->count(),
                    'total_amount' => $total,
                ],
                'description' => 'Commande FarmShop - ' . $cartItems->count() . ' article(s)',
            ]);

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'publishable_key' => config('stripe.publishable_key'),
            ]);

        } catch (CardException $e) {
            Log::error('Stripe Card Error: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur de carte: ' . $e->getMessage()], 400);
        } catch (InvalidRequestException $e) {
            Log::error('Stripe Invalid Request: ' . $e->getMessage());
            return response()->json(['error' => 'Requête invalide: ' . $e->getMessage()], 400);
        } catch (AuthenticationException $e) {
            Log::error('Stripe Authentication Error: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur d\'authentification Stripe'], 500);
        } catch (ApiConnectionException $e) {
            Log::error('Stripe API Connection Error: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur de connexion à Stripe'], 500);
        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur API Stripe: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            Log::error('Payment Intent Creation Error: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la création du paiement'], 500);
        }
    }

    /**
     * Confirmer le paiement et créer la commande
     */
    public function confirmPayment(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
        ]);

        try {
            $user = Auth::user();
            
            // Récupérer le PaymentIntent depuis Stripe
            $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);
            
            if ($paymentIntent->status !== 'succeeded') {
                return response()->json(['error' => 'Le paiement n\'a pas été confirmé'], 400);
            }

            // Vérifier que le paiement appartient bien à l'utilisateur
            if ($paymentIntent->metadata->user_id != $user->id) {
                return response()->json(['error' => 'Paiement invalide'], 403);
            }

            // Récupérer les articles du panier
            $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();
            
            if ($cartItems->isEmpty()) {
                return response()->json(['error' => 'Panier vide'], 400);
            }

            // Créer la commande
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $paymentIntent->metadata->total_amount,
                'status' => 'paid',
                'payment_method' => 'stripe',
                'payment_intent_id' => $paymentIntent->id,
                'stripe_customer_id' => $paymentIntent->customer,
            ]);

            // Ajouter les articles à la commande
            foreach ($cartItems as $cartItem) {
                $order->items()->create([
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                    'total_price' => $cartItem->getTotalPrice(),
                ]);

                // Décrémenter le stock si c'est un achat
                if ($cartItem->product->type === 'sale') {
                    $cartItem->product->decrement('stock', $cartItem->quantity);
                }
            }

            // Vider le panier
            CartItem::where('user_id', $user->id)->delete();

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'redirect_url' => route('orders.show', $order->id),
            ]);

        } catch (\Exception $e) {
            Log::error('Payment Confirmation Error: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la confirmation du paiement'], 500);
        }
    }

    /**
     * Gérer les paiements pour les locations
     */
    public function createRentalPayment(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rental_duration' => 'required|integer|min:1',
            'rental_type' => 'required|in:daily,weekly,monthly',
        ]);

        try {
            $user = Auth::user();
            $product = Product::findOrFail($request->product_id);
            
            if ($product->type !== 'rental') {
                return response()->json(['error' => 'Ce produit n\'est pas disponible à la location'], 400);
            }

            // Calculer le prix de la location
            $dailyRate = $product->rental_price ?? $product->price;
            $totalAmount = $this->calculateRentalAmount($dailyRate, $request->rental_duration, $request->rental_type);
            
            // Convertir en centimes
            $amountInCents = intval($totalAmount * 100);

            // Créer le client Stripe
            $stripeCustomer = $this->getOrCreateStripeCustomer($user);

            // Créer le PaymentIntent pour la location
            $paymentIntent = PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => config('stripe.currency'),
                'customer' => $stripeCustomer->id,
                'payment_method_types' => ['card'],
                'metadata' => [
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'rental_duration' => $request->rental_duration,
                    'rental_type' => $request->rental_type,
                    'total_amount' => $totalAmount,
                    'order_type' => 'rental',
                ],
                'description' => 'Location FarmShop - ' . $product->name . ' (' . $request->rental_duration . ' ' . $request->rental_type . ')',
            ]);

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'publishable_key' => config('stripe.publishable_key'),
                'amount' => $totalAmount,
            ]);

        } catch (\Exception $e) {
            Log::error('Rental Payment Creation Error: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la création du paiement de location'], 500);
        }
    }

    /**
     * Créer ou récupérer un client Stripe
     */
    private function getOrCreateStripeCustomer($user)
    {
        if ($user->stripe_customer_id) {
            try {
                return Customer::retrieve($user->stripe_customer_id);
            } catch (\Exception $e) {
                // Le client n'existe plus, on en crée un nouveau
                Log::warning('Stripe customer not found, creating new one: ' . $e->getMessage());
            }
        }

        // Créer un nouveau client
        $customer = Customer::create([
            'email' => $user->email,
            'name' => $user->name,
            'metadata' => [
                'user_id' => $user->id,
            ],
        ]);

        // Sauvegarder l'ID du client
        $user->update(['stripe_customer_id' => $customer->id]);

        return $customer;
    }

    /**
     * Calculer le montant de la location
     */
    private function calculateRentalAmount($dailyRate, $duration, $type)
    {
        switch ($type) {
            case 'daily':
                return $dailyRate * $duration;
            case 'weekly':
                return $dailyRate * $duration * 7;
            case 'monthly':
                return $dailyRate * $duration * 30;
            default:
                return $dailyRate * $duration;
        }
    }

    /**
     * Afficher la page de test des cartes Stripe
     */
    public function testCards()
    {
        $testCards = config('stripe.test_cards');
        return view('payment.test-cards', compact('testCards'));
    }
}
