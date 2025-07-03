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
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Rental;
use App\Models\RentalItem;
use Illuminate\Support\Facades\DB;
use App\Notifications\OrderConfirmation;
use Carbon\Carbon;

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

        // Calculer le subtotal avec les offres spéciales
        $subtotal = 0;
        $totalSavings = 0;
        
        foreach ($cartItems as $item) {
            $itemTotal = $item->total_price; // Utiliser l'accessor via la propriété
            $subtotal += $itemTotal;
            
            // Calculer les économies
            $originalPrice = $item->product->price * $item->quantity;
            $savings = $originalPrice - $itemTotal;
            $totalSavings += $savings;
        }

        // Calculer les frais de livraison (gratuit si subtotal >= 25€)
        $shippingCost = $subtotal < 25 ? 2.50 : 0;
        
        // Calculer le total final incluant les frais de livraison
        $total = $subtotal + $shippingCost;

        // Stocker les données de commande en session pour les achats via panier
        session()->put('order_data', [
            'subtotal' => $subtotal,
            'tax_amount' => 0, // Pas de TVA pour l'instant
            'shipping_cost' => $shippingCost,
            'total_amount' => $total,
            'shipping_address' => $user->address ?? [], // Utilisez l'adresse de l'utilisateur
            'notes' => null,
            'order_type' => 'purchase', // Marquer comme achat
        ]);

        // Convertir en centimes pour Stripe
        $amountInCents = intval($total * 100);

        return view('payment.form', compact('cartItems', 'subtotal', 'shippingCost', 'total', 'totalSavings', 'amountInCents'));
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

            // Calculer le subtotal
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += $item->total_price; // Utiliser l'accessor via la propriété
            }

            // Calculer les frais de livraison (gratuit si subtotal >= 25€)
            $shippingCost = $subtotal < 25 ? 2.50 : 0;
            
            // Calculer le total final incluant les frais de livraison
            $total = $subtotal + $shippingCost;

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
                    'subtotal' => $subtotal,
                    'shipping_cost' => $shippingCost,
                    'total_amount' => $total,
                    'order_type' => 'purchase', // Marquer comme achat
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

            // Le paiement est confirmé, rediriger vers la finalisation de commande
            return response()->json([
                'success' => true,
                'redirect_url' => route('payment.finalize-order', ['payment_intent_id' => $paymentIntent->id])
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
     * Finaliser la commande/location après un paiement réussi
     */
    public function finalizeOrder(Request $request)
    {
        $user = Auth::user();
        $paymentIntentId = $request->input('payment_intent_id');
        
        try {
            // Récupérer le PaymentIntent depuis Stripe pour vérifier le type
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            
            // Vérifier si c'est une location ou un achat via les metadata
            $orderType = $paymentIntent->metadata->order_type ?? 'purchase';
            
            if ($orderType === 'rental') {
                return $this->finalizeRental($request, $paymentIntent);
            } else {
                return $this->finalizePurchase($request, $paymentIntent);
            }
            
        } catch (\Exception $e) {
            Log::error('Payment Finalization Error: ' . $e->getMessage());
            return redirect()->route('cart.index')
                ->with('error', 'Erreur lors de la finalisation du paiement: ' . $e->getMessage());
        }
    }

    /**
     * Finaliser un achat
     */
    private function finalizePurchase(Request $request, $paymentIntent)
    {
        $user = Auth::user();
        
        // Récupérer les données de commande depuis la session
        $orderData = session()->get('order_data');
        
        if (!$orderData) {
            return redirect()->route('cart.index')->with('error', 'Session expirée. Veuillez recommencer votre commande.');
        }
        
        // Récupérer les articles du panier
        $cartItems = $user->cartItems()->with(['product'])->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }
        
        DB::beginTransaction();
        
        try {
            // Créer la commande
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => Order::generateOrderNumber(),
                'status' => Order::STATUS_CONFIRMED,
                'subtotal' => $orderData['subtotal'],
                'tax_amount' => $orderData['tax_amount'] ?? 0,
                'shipping_cost' => $orderData['shipping_cost'],
                'total_amount' => $orderData['total_amount'],
                'shipping_address' => json_encode($orderData['shipping_address']),
                'billing_address' => json_encode($orderData['shipping_address']),
                'payment_method' => 'stripe',
                'payment_status' => Order::PAYMENT_PAID,
                'notes' => $orderData['notes'] ?? null,
                'stripe_payment_intent_id' => $paymentIntent->id,
                'paid_at' => now(),
            ]);
            
            // Créer les articles de commande et décrémenter le stock
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;
                
                // Vérifier le stock pour les produits non-locations
                if ($product->type !== 'rental' && $product->quantity < $cartItem->quantity) {
                    throw new \Exception("Stock insuffisant pour le produit {$product->name}");
                }
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_description' => $product->description,
                    'unit_price' => $cartItem->unit_price,
                    'quantity' => $cartItem->quantity,
                    'total_price' => $cartItem->total_price,
                    'status' => OrderItem::STATUS_PENDING,
                ]);
                
                // Décrémenter le stock uniquement pour les produits vendus (pas les locations)
                if ($product->type !== 'rental') {
                    $product->decrement('quantity', $cartItem->quantity);
                }
            }
            
            // Vider le panier
            $user->cartItems()->delete();
            
            // Nettoyer la session
            session()->forget('order_data');
            
            // Envoyer notification email de confirmation
            $order->user->notify(new OrderConfirmation($order));
            
            DB::commit();
            
            return redirect()->route('orders.user.show', $order)
                ->with('success', 'Votre commande a été créée avec succès et le paiement a été confirmé !');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Purchase Finalization Error: ' . $e->getMessage());
            
            return redirect()->route('cart.index')
                ->with('error', 'Erreur lors de la finalisation de la commande: ' . $e->getMessage());
        }
    }

    /**
     * Finaliser une location
     */
    private function finalizeRental(Request $request, $paymentIntent)
    {
        $user = Auth::user();
        
        DB::beginTransaction();
        
        try {
            // Récupérer les données de location depuis les metadata du PaymentIntent
            $productId = $paymentIntent->metadata->product_id;
            $rentalDuration = $paymentIntent->metadata->rental_duration;
            $rentalType = $paymentIntent->metadata->rental_type;
            $totalAmount = $paymentIntent->metadata->total_amount;
            
            $product = Product::findOrFail($productId);
            
            // Calculer les dates de location
            $startDate = now();
            $endDate = $this->calculateEndDate($startDate, $rentalDuration, $rentalType);
            
            // Créer la location
            $rental = Rental::create([
                'user_id' => $user->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => Rental::STATUS_CONFIRMED,
                'total_rental_amount' => $totalAmount,
                'total_deposit_amount' => 0, // À ajuster selon votre logique
                'payment_status' => Rental::PAYMENT_PAID,
                'billing_address' => json_encode($user->address ?? []),
            ]);
            
            // Créer l'article de location
            RentalItem::create([
                'rental_id' => $rental->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'rental_price_per_day' => $product->rental_price ?? $product->price,
                'deposit_amount_per_item' => 0, // À ajuster selon votre logique
                'total_rental_amount' => $totalAmount,
                'total_deposit_amount' => 0,
                'condition_at_pickup' => RentalItem::CONDITION_EXCELLENT,
                'return_status' => RentalItem::RETURN_NOT_RETURNED,
                'returned_quantity' => 0,
            ]);
            
            // Pour les locations, décrémenter la quantité disponible temporairement
            // (elle sera ré-incrémentée au retour)
            $product->decrement('quantity', 1);
            
            // Envoyer notification email de confirmation
            $rental->user->notify(new \App\Notifications\RentalConfirmation($rental));
            
            DB::commit();
            
            // Rediriger vers l'historique des locations (on créera cette route plus tard)
            return redirect()->route('rentals.user.show', $rental)
                ->with('success', 'Votre location a été confirmée avec succès !');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Rental Finalization Error: ' . $e->getMessage());
            
            // En cas d'erreur, rediriger vers les commandes en attendant la création des routes locations
            return redirect()->route('orders.user.index')
                ->with('error', 'Erreur lors de la finalisation de la location: ' . $e->getMessage());
        }
    }

    /**
     * Calculer la date de fin de location
     */
    private function calculateEndDate($startDate, $duration, $type)
    {
        $start = Carbon::parse($startDate);
        
        switch ($type) {
            case 'daily':
                return $start->addDays($duration);
            case 'weekly':
                return $start->addWeeks($duration);
            case 'monthly':
                return $start->addMonths($duration);
            default:
                return $start->addDays($duration);
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
