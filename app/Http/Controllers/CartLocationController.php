<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartLocation;
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
        $cartTotal = CartLocation::getCartTotal($user->id);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Panier de location récupéré avec succès.',
                'data' => $cartTotal
            ]);
        }

        return view('cart-location.index', [
            'cartItems' => $cartTotal['items'],
            'cartTotal' => $cartTotal
        ]);
    }

    /**
     * Ajouter un produit au panier de location.
     */
    public function store(AddToCartLocationRequest $request)
    {
        $user = auth()->user();
        $startDate = Carbon::parse($request->rental_start_date);

        $cartLocation = CartLocation::addToCart(
            $user->id,
            $request->product_id,
            $request->quantity,
            $request->rental_duration_days,
            $startDate,
            $request->deposit_amount
        );

        if (!$cartLocation) {
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
                'data' => $cartLocation->load('product')
            ], 201);
        }

        return redirect()->route('cart-location.index')
            ->with('success', 'Produit ajouté au panier de location avec succès.');
    }

    /**
     * Afficher un article du panier de location.
     */
    public function show(CartLocation $cartLocation)
    {
        // Vérifier que l'utilisateur est propriétaire
        if ($cartLocation->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé.');
        }

        $cartLocation->load('product');

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $cartLocation
            ]);
        }

        return view('cart-location.show', compact('cartLocation'));
    }

    /**
     * Modifier un article du panier de location.
     */
    public function update(UpdateCartLocationRequest $request, CartLocation $cartLocation)
    {
        $updated = false;

        // Mettre à jour la quantité si fournie
        if ($request->has('quantity') && $request->quantity != $cartLocation->quantity) {
            if (!$cartLocation->updateQuantity($request->quantity)) {
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

        // Mettre à jour la durée et/ou la date de début si fournies
        if ($request->has('rental_duration_days') || $request->has('rental_start_date')) {
            $newDuration = $request->rental_duration_days ?? $cartLocation->rental_duration_days;
            $newStartDate = $request->rental_start_date ? 
                Carbon::parse($request->rental_start_date) : 
                $cartLocation->rental_start_date;

            if (!$cartLocation->updateDuration($newDuration, $newStartDate)) {
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
            $cartLocation->deposit_amount = $request->deposit_amount;
            $cartLocation->save();
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

        $cartLocation->refresh()->load('product');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Article du panier de location modifié avec succès.',
                'data' => $cartLocation
            ]);
        }

        return redirect()->route('cart-location.index')
            ->with('success', 'Article du panier de location modifié avec succès.');
    }

    /**
     * Supprimer un article du panier de location.
     */
    public function destroy(CartLocation $cartLocation)
    {
        // Vérifier que l'utilisateur est propriétaire et que le statut permet la suppression
        if ($cartLocation->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé.');
        }

        if ($cartLocation->status !== CartLocation::STATUS_PENDING) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer cet article (statut: ' . $cartLocation->status . ').'
                ], 400);
            }

            return redirect()->back()
                ->with('error', 'Impossible de supprimer cet article.');
        }

        $productName = $cartLocation->product_name;
        $cartLocation->delete();

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
        $deletedCount = CartLocation::clearCartForUser($user->id);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Panier de location vidé ({$deletedCount} articles supprimés).",
                'data' => ['deleted_count' => $deletedCount]
            ]);
        }

        return redirect()->route('cart-location.index')
            ->with('success', "Panier de location vidé ({$deletedCount} articles supprimés).");
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

        $cartLocation = CartLocation::addToCart(
            auth()->id(),
            $product->id,
            $quantity,
            $duration,
            $startDate
        );

        if (!$cartLocation) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible d\'ajouter ce produit au panier de location.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Produit ajouté au panier de location avec succès.',
            'data' => $cartLocation->load('product')
        ]);
    }

    /**
     * Obtenir le nombre d'articles dans le panier de location.
     */
    public function getCartCount(Request $request)
    {
        $user = auth()->user();
        $count = CartLocation::forUser($user->id)->pending()->count();

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
        $cartTotal = CartLocation::getCartTotal($user->id);

        return response()->json([
            'success' => true,
            'data' => $cartTotal
        ]);
    }

    /**
     * Synchroniser le panier de location avec les données côté client.
     */
    public function sync(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.id' => 'required|integer|exists:cart_locations,id',
            'items.*.quantity' => 'required|integer|min:1|max:100',
            'items.*.rental_duration_days' => 'required|integer|min:1|max:365'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données de synchronisation invalides.',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth()->user();
        $syncedItems = [];
        $errors = [];

        foreach ($request->items as $itemData) {
            $cartLocation = CartLocation::where('id', $itemData['id'])
                ->where('user_id', $user->id)
                ->where('status', CartLocation::STATUS_PENDING)
                ->first();

            if (!$cartLocation) {
                $errors[] = "Article {$itemData['id']} non trouvé ou non modifiable.";
                continue;
            }

            // Tenter de mettre à jour la quantité
            if (!$cartLocation->updateQuantity($itemData['quantity'])) {
                $errors[] = "Impossible de mettre à jour la quantité pour {$cartLocation->product_name}.";
                continue;
            }

            // Tenter de mettre à jour la durée
            if (!$cartLocation->updateDuration($itemData['rental_duration_days'])) {
                $errors[] = "Impossible de mettre à jour la durée pour {$cartLocation->product_name}.";
                continue;
            }

            $syncedItems[] = $cartLocation->refresh();
        }

        return response()->json([
            'success' => empty($errors),
            'message' => empty($errors) ? 'Synchronisation réussie.' : 'Synchronisation partielle.',
            'data' => [
                'synced_items' => $syncedItems,
                'errors' => $errors
            ]
        ]);
    }

    /**
     * Valider le panier de location avant commande.
     */
    public function validateCart(Request $request)
    {
        $user = auth()->user();
        $issues = CartLocation::validateCart($user->id);

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

        return redirect()->route('checkout-location.index'); // Redirection vers la page de commande de location
    }

    /**
     * Prolonger une location.
     */
    public function extend(Request $request, CartLocation $cartLocation)
    {
        // Vérifier que l'utilisateur est propriétaire
        if ($cartLocation->user_id !== auth()->id()) {
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

        if (!$cartLocation->extendRental($request->additional_days)) {
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
                'data' => $cartLocation->refresh()
            ]);
        }

        return redirect()->back()
            ->with('success', "Location prolongée de {$request->additional_days} jours.");
    }
}
