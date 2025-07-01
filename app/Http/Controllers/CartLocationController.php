<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartLocation;
use App\Models\CartItemLocation;
use App\Models\Product;
use App\Http\Requests\AddToCartLocationRequest;
use App\Http\Requests\UpdateCartLocationRequest;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CartLocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher le panier de location de l'utilisateur.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $cart = CartLocation::getActiveCartForUser($user->id);
        $cart->load('items.product');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Panier de location récupéré avec succès.',
                'data' => [
                    'cart' => $cart,
                    'items' => $cart->items,
                    'total_items' => $cart->total_items,
                    'total_amount' => $cart->total_amount,
                    'total_deposit' => $cart->total_deposit,
                    'grand_total' => $cart->grand_total
                ]
            ]);
        }

        return view('cart-location.index', [
            'cart' => $cart,
            'cartItems' => $cart->items,
            'cartTotal' => [
                'total_price' => $cart->total_amount,
                'total_deposit' => $cart->total_deposit,
                'total_amount' => $cart->grand_total,
                'item_count' => $cart->total_items,
                'items' => $cart->items
            ]
        ]);
    }

    /**
     * Ajouter un produit au panier de location.
     */
    public function store(AddToCartLocationRequest $request)
    {
        $user = auth()->user();
        $cart = CartLocation::getActiveCartForUser($user->id);
        $startDate = Carbon::parse($request->rental_start_date);

        $cartItem = $cart->addItem(
            $request->product_id,
            $request->quantity,
            $request->rental_duration_days,
            $startDate,
            $request->deposit_amount
        );

        if (!$cartItem) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible d\'ajouter ce produit au panier de location.'
                ], 400);
            }

            return redirect()->back()
                ->with('error', 'Impossible d\'ajouter ce produit au panier de location.');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produit ajouté au panier de location avec succès.',
                'data' => $cartItem->load('product')
            ], 201);
        }

        return redirect()->route('cart-location.index')
            ->with('success', 'Produit ajouté au panier de location avec succès.');
    }

    /**
     * Afficher un article du panier de location.
     */
    public function show(CartItemLocation $cartItemLocation)
    {
        // Vérifier que l'utilisateur est propriétaire
        if ($cartItemLocation->cartLocation->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé.');
        }

        $cartItemLocation->load('product', 'cartLocation');

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $cartItemLocation
            ]);
        }

        return view('cart-location.show', compact('cartItemLocation'));
    }

    /**
     * Modifier un article du panier de location.
     */
    public function update(Request $request, CartItemLocation $cartItemLocation)
    {
        // Vérifier que l'utilisateur est propriétaire
        if ($cartItemLocation->cartLocation->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé.');
        }

        // Validation simple pour cette méthode
        $tomorrow = now()->addDay()->format('Y-m-d');
        
        $validator = Validator::make($request->all(), [
            'quantity' => 'sometimes|integer|min:1|max:100',
            'rental_duration_days' => 'sometimes|integer|min:1|max:365',
            'rental_start_date' => 'sometimes|date|after_or_equal:' . $tomorrow,
            'start_date' => 'sometimes|date|after_or_equal:' . $tomorrow,
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'deposit_amount' => 'sometimes|numeric|min:0|max:10000',
        ], [
            'start_date.after_or_equal' => 'La date de début doit être au minimum demain.',
            'rental_start_date.after_or_equal' => 'La date de début doit être au minimum demain.',
            'end_date.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données de validation incorrectes.',
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Erreur de validation.');
        }

        $updated = false;

        // Si start_date et end_date sont fournis (format simple du frontend)
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            
            // Vérification supplémentaire : pas de location le jour même
            if ($startDate->isToday() || $startDate->isPast()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'La location ne peut pas commencer aujourd\'hui ou dans le passé. Choisissez une date à partir de demain.'
                    ], 400);
                }
                return redirect()->back()->with('error', 'La location ne peut pas commencer aujourd\'hui ou dans le passé.');
            }
            
            $newDuration = $startDate->diffInDays($endDate) + 1; // +1 pour inclure le jour de début

            // Vérifier les contraintes du produit
            $product = $cartItemLocation->product;
            if ($product->min_rental_days && $newDuration < $product->min_rental_days) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => "La durée minimum de location est de {$product->min_rental_days} jour(s)."
                    ], 400);
                }
                return redirect()->back()->with('error', "La durée minimum de location est de {$product->min_rental_days} jour(s).");
            }

            if ($product->max_rental_days && $newDuration > $product->max_rental_days) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => "La durée maximum de location est de {$product->max_rental_days} jours."
                    ], 400);
                }
                return redirect()->back()->with('error', "La durée maximum de location est de {$product->max_rental_days} jours.");
            }

            if (!$cartItemLocation->updateDuration($newDuration, $startDate)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Impossible de mettre à jour la durée de location.'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Impossible de mettre à jour la durée de location.');
            }
            $updated = true;
        }

        // Mettre à jour la quantité si fournie
        if ($request->has('quantity') && $request->quantity != $cartItemLocation->quantity) {
            if (!$cartItemLocation->updateQuantity($request->quantity)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Impossible de mettre à jour la quantité.'
                    ], 400);
                }

                return redirect()->back()
                    ->with('error', 'Impossible de mettre à jour la quantité.');
            }
            $updated = true;
        }

        // Mettre à jour la durée et/ou la date de début si fournies (format ancien)
        if ($request->has('rental_duration_days') || $request->has('rental_start_date')) {
            $newDuration = $request->rental_duration_days ?? $cartItemLocation->rental_duration_days;
            $newStartDate = $request->rental_start_date ? 
                Carbon::parse($request->rental_start_date) : 
                $cartItemLocation->rental_start_date;

            if (!$cartItemLocation->updateDuration($newDuration, $newStartDate)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Impossible de mettre à jour la durée de location.'
                    ], 400);
                }

                return redirect()->back()
                    ->with('error', 'Impossible de mettre à jour la durée de location.');
            }
            $updated = true;
        }

        // Mettre à jour la caution si fournie
        if ($request->has('deposit_amount') && $request->deposit_amount !== null) {
            $cartItemLocation->deposit_amount = $request->deposit_amount;
            $cartItemLocation->save();
            $updated = true;
        }

        if (!$updated) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune modification fournie.'
                ], 400);
            }

            return redirect()->back()
                ->with('warning', 'Aucune modification fournie.');
        }

        $cartItemLocation->refresh()->load('product');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Article du panier de location modifié avec succès.',
                'data' => $cartItemLocation
            ]);
        }

        return redirect()->route('cart-location.index')
            ->with('success', 'Article du panier de location modifié avec succès.');
    }

    /**
     * Supprimer un article du panier de location.
     */
    public function destroy(CartItemLocation $cartItemLocation)
    {
        // Vérifier que l'utilisateur est propriétaire et que le statut permet la suppression
        if ($cartItemLocation->cartLocation->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé.');
        }

        if ($cartItemLocation->status !== CartItemLocation::STATUS_PENDING) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer cet article (statut: ' . $cartItemLocation->status . ').'
                ], 400);
            }

            return redirect()->back()
                ->with('error', 'Impossible de supprimer cet article.');
        }

        $productName = $cartItemLocation->product_name;
        $cart = $cartItemLocation->cartLocation;
        
        $cart->removeItem($cartItemLocation->id);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Produit '{$productName}' supprimé du panier de location."
            ]);
        }

        return redirect()->route('cart-location.index')
            ->with('success', "Produit '{$productName}' supprimé du panier de location.");
    }

    /**
     * Vider complètement le panier de location.
     */
    public function clear(Request $request)
    {
        $user = auth()->user();
        $cart = CartLocation::getActiveCartForUser($user->id);
        $itemCount = $cart->items()->count();
        
        $cart->clear();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Panier de location vidé ({$itemCount} articles supprimés).",
                'data' => ['deleted_count' => $itemCount]
            ]);
        }

        return redirect()->route('cart-location.index')
            ->with('success', "Panier de location vidé ({$itemCount} articles supprimés).");
    }

    /**
     * Ajout rapide d'un produit au panier de location (AJAX).
     */
    public function quickAdd(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'integer|min:1|max:10',
            'rental_duration_days' => 'integer|min:1|max:30',
            'rental_start_date' => 'date|after_or_equal:today'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides.',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!$product->isAvailableForRental()) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit n\'est pas disponible pour la location.'
            ], 400);
        }

        $quantity = $request->input('quantity', 1);
        $duration = $request->input('rental_duration_days', $product->min_rental_days);
        $startDate = $request->rental_start_date ? 
            Carbon::parse($request->rental_start_date) : 
            Carbon::tomorrow();

        $cart = CartLocation::getActiveCartForUser(auth()->id());
        $cartItem = $cart->addItem(
            $product->id,
            $quantity,
            $duration,
            $startDate
        );

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible d\'ajouter ce produit au panier de location.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Produit ajouté au panier de location avec succès.',
            'data' => $cartItem->load('product')
        ]);
    }

    /**
     * Obtenir le nombre d'articles dans le panier de location.
     */
    public function getCartCount(Request $request)
    {
        $user = auth()->user();
        $cart = CartLocation::getActiveCartForUser($user->id);
        $count = $cart->total_items;

        return response()->json([
            'success' => true,
            'data' => ['count' => $count]
        ]);
    }

    /**
     * Obtenir le total du panier de location.
     */
    public function getCartTotal(Request $request)
    {
        $user = auth()->user();
        $cart = CartLocation::getActiveCartForUser($user->id);

        return response()->json([
            'success' => true,
            'data' => [
                'total_price' => $cart->total_amount,
                'total_deposit' => $cart->total_deposit,
                'total_amount' => $cart->grand_total,
                'item_count' => $cart->total_items,
                'items' => $cart->items
            ]
        ]);
    }

    /**
     * Valider le panier de location avant commande.
     */
    public function validateCart(Request $request)
    {
        $user = auth()->user();
        $cart = CartLocation::getActiveCartForUser($user->id);
        $issues = $cart->validate();

        if (!empty($issues)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Des problèmes ont été détectés dans votre panier de location.',
                    'issues' => $issues
                ], 400);
            }

            return redirect()->route('cart-location.index')
                ->with('error', 'Des problèmes ont été détectés dans votre panier de location.')
                ->with('issues', $issues);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Panier de location valide, prêt pour la commande.'
            ]);
        }

        return redirect()->route('checkout-location.index');
    }

    /**
     * Soumettre le panier (passer de draft à pending)
     */
    public function submit(Request $request)
    {
        $user = auth()->user();
        $cart = CartLocation::getActiveCartForUser($user->id);

        if (!$cart->submit()) {
            $issues = $cart->validate();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de soumettre le panier de location.',
                    'issues' => $issues
                ], 400);
            }

            return redirect()->back()
                ->with('error', 'Impossible de soumettre le panier de location.')
                ->with('issues', $issues);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Panier de location soumis avec succès.',
                'data' => $cart->refresh()
            ]);
        }

        return redirect()->route('checkout-location.index')
            ->with('success', 'Panier de location soumis avec succès.');
    }

    /**
     * Prolonger une location.
     */
    public function extend(Request $request, CartItemLocation $cartItemLocation)
    {
        // Vérifier que l'utilisateur est propriétaire
        if ($cartItemLocation->cartLocation->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé.');
        }

        $validator = Validator::make($request->all(), [
            'additional_days' => 'required|integer|min:1|max:180'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données invalides.',
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()->back()->withErrors($validator);
        }

        if (!$cartItemLocation->extendRental($request->additional_days)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de prolonger cette location.'
                ], 400);
            }

            return redirect()->back()
                ->with('error', 'Impossible de prolonger cette location.');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Location prolongée de {$request->additional_days} jours.",
                'data' => $cartItemLocation->refresh()
            ]);
        }

        return redirect()->back()
            ->with('success', "Location prolongée de {$request->additional_days} jours.");
    }

    /**
     * Ajouter un produit au panier de location (version simple avec start_date/end_date).
     */
    public function addSimple(Request $request)
    {
        // Validation simple pour cette méthode
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], [
            'product_id.required' => 'Le produit est obligatoire.',
            'product_id.exists' => 'Le produit sélectionné n\'existe pas.',
            'start_date.required' => 'La date de début est obligatoire.',
            'start_date.after' => 'La date de début doit être dans le futur.',
            'end_date.required' => 'La date de fin est obligatoire.',
            'end_date.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données de validation incorrectes.',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth()->user();
        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produit non trouvé.'
            ], 404);
        }

        // Vérifier que le produit est disponible pour la location
        if (!$product->is_rentable) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit n\'est pas disponible pour la location.'
            ], 400);
        }

        // Calculer la durée en jours
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $durationDays = $startDate->diffInDays($endDate) + 1; // +1 pour inclure le jour de début

        // Vérifier les contraintes de durée
        if ($product->min_rental_days && $durationDays < $product->min_rental_days) {
            return response()->json([
                'success' => false,
                'message' => "La durée minimum de location est de {$product->min_rental_days} jour(s)."
            ], 400);
        }

        if ($product->max_rental_days && $durationDays > $product->max_rental_days) {
            return response()->json([
                'success' => false,
                'message' => "La durée maximum de location est de {$product->max_rental_days} jours."
            ], 400);
        }

        // Vérifier le stock
        if (!$product->hasStock(1)) {
            return response()->json([
                'success' => false,
                'message' => 'Stock insuffisant pour ce produit.'
            ], 400);
        }

        try {
            $cart = CartLocation::getActiveCartForUser($user->id);

            $cartItem = $cart->addItem(
                $product->id,
                1, // quantité par défaut
                $durationDays,
                $startDate,
                $product->deposit_amount ?? 0
            );

            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible d\'ajouter ce produit au panier de location.'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Produit ajouté au panier de location avec succès.',
                'data' => [
                    'cart_item' => $cartItem->load('product'),
                    'cart_count' => $cart->fresh()->total_items
                ]
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'ajout au panier de location: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur interne du serveur lors de l\'ajout au panier.'
            ], 500);
        }
    }
}
