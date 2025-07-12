<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Afficher le panier de l'utilisateur connecté
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();
        $cart = $user->getOrCreateActiveCart();
        
        // Charger les éléments du panier avec les informations des produits
        $cart->load(['items.product.category']);
        
        // Vérifier la disponibilité de tous les produits
        $unavailableItems = $cart->checkAvailability();
        
        return response()->json([
            'success' => true,
            'message' => 'Panier récupéré avec succès',
            'data' => [
                'cart' => $cart,
                'items' => $cart->items->map(function ($item) {
                    return $item->toDisplayArray();
                }),
                'unavailable_items' => $unavailableItems,
                'summary' => [
                    'total_items' => $cart->total_items,
                    'subtotal' => $cart->subtotal,
                    'tax_amount' => $cart->tax_amount,
                    'total' => $cart->total,
                    'tax_rate' => $cart->tax_rate
                ]
            ]
        ]);
    }

    /**
     * Ajouter un produit au panier
     */
    public function addProduct(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:100'
        ]);

        $user = Auth::user();
        $product = Product::findOrFail($request->product_id);
        
        try {
            DB::beginTransaction();
            
            $cart = $user->getOrCreateActiveCart();
            $cartItem = $cart->addProduct($product, $request->quantity);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Produit ajouté au panier avec succès',
                'data' => [
                    'cart_item' => $cartItem->toDisplayArray(),
                    'cart_summary' => [
                        'total_items' => $cart->fresh()->total_items,
                        'subtotal' => $cart->fresh()->subtotal,
                        'tax_amount' => $cart->fresh()->tax_amount,
                        'total' => $cart->fresh()->total
                    ]
                ]
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Mettre à jour la quantité d'un produit dans le panier
     */
    public function updateQuantity(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:100'
        ]);

        $user = Auth::user();
        $product = Product::findOrFail($request->product_id);
        
        try {
            DB::beginTransaction();
            
            $cart = $user->activeCart()->firstOrFail();
            $cartItem = $cart->updateProductQuantity($product, $request->quantity);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Quantité mise à jour avec succès',
                'data' => [
                    'cart_item' => $cartItem->toDisplayArray(),
                    'cart_summary' => [
                        'total_items' => $cart->fresh()->total_items,
                        'subtotal' => $cart->fresh()->subtotal,
                        'tax_amount' => $cart->fresh()->tax_amount,
                        'total' => $cart->fresh()->total
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Supprimer un produit du panier
     */
    public function removeProduct(Product $product): JsonResponse
    {
        $user = Auth::user();
        
        try {
            DB::beginTransaction();
            
            $cart = $user->activeCart()->firstOrFail();
            $removed = $cart->removeProduct($product);
            
            if (!$removed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produit non trouvé dans le panier'
                ], 404);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Produit supprimé du panier avec succès',
                'data' => [
                    'cart_summary' => [
                        'total_items' => $cart->fresh()->total_items,
                        'subtotal' => $cart->fresh()->subtotal,
                        'tax_amount' => $cart->fresh()->tax_amount,
                        'total' => $cart->fresh()->total
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Vider complètement le panier
     */
    public function clear(): JsonResponse
    {
        $user = Auth::user();
        
        try {
            DB::beginTransaction();
            
            $cart = $user->activeCart()->first();
            
            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun panier actif trouvé'
                ], 404);
            }
            
            $itemsCount = $cart->items()->count();
            $cart->clear();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Panier vidé avec succès ({$itemsCount} produits supprimés)",
                'data' => [
                    'removed_items' => $itemsCount,
                    'cart_summary' => [
                        'total_items' => 0,
                        'subtotal' => 0,
                        'tax_amount' => 0,
                        'total' => 0
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Vérifier la disponibilité des produits dans le panier
     */
    public function checkAvailability(): JsonResponse
    {
        $user = Auth::user();
        $cart = $user->activeCart()->first();
        
        if (!$cart) {
            return response()->json([
                'success' => true,
                'message' => 'Aucun panier actif',
                'data' => [
                    'all_available' => true,
                    'unavailable_items' => []
                ]
            ]);
        }
        
        $unavailableItems = $cart->checkAvailability();
        
        return response()->json([
            'success' => true,
            'message' => 'Vérification de disponibilité effectuée',
            'data' => [
                'all_available' => empty($unavailableItems),
                'unavailable_items' => $unavailableItems,
                'total_items' => $cart->items()->count(),
                'unavailable_count' => count($unavailableItems)
            ]
        ]);
    }

    /**
     * Obtenir le résumé du panier
     */
    public function summary(): JsonResponse
    {
        $user = Auth::user();
        $cart = $user->activeCart()->first();
        
        if (!$cart) {
            return response()->json([
                'success' => true,
                'message' => 'Aucun panier actif',
                'data' => [
                    'total_items' => 0,
                    'subtotal' => 0,
                    'tax_amount' => 0,
                    'total' => 0,
                    'is_empty' => true
                ]
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Résumé du panier récupéré',
            'data' => [
                'cart_id' => $cart->id,
                'total_items' => $cart->total_items,
                'subtotal' => $cart->subtotal,
                'tax_amount' => $cart->tax_amount,
                'total' => $cart->total,
                'tax_rate' => $cart->tax_rate,
                'is_empty' => $cart->isEmpty(),
                'created_at' => $cart->created_at,
                'expires_at' => $cart->expires_at
            ]
        ]);
    }

    /**
     * Préparer le panier pour la commande (vérifications finales)
     */
    public function prepareForCheckout(): JsonResponse
    {
        $user = Auth::user();
        $cart = $user->activeCart()->first();
        
        if (!$cart || $cart->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Le panier est vide'
            ], 400);
        }
        
        // Vérifier la disponibilité de tous les produits
        $unavailableItems = $cart->checkAvailability();
        
        if (!empty($unavailableItems)) {
            return response()->json([
                'success' => false,
                'message' => 'Certains produits ne sont plus disponibles',
                'data' => [
                    'unavailable_items' => $unavailableItems
                ]
            ], 400);
        }
        
        // Recalculer les totaux avec les prix actuels (optionnel)
        $cart->recalculateTotal();
        
        return response()->json([
            'success' => true,
            'message' => 'Panier prêt pour la commande',
            'data' => [
                'cart' => $cart,
                'items' => $cart->items->map(function ($item) {
                    return $item->toDisplayArray();
                }),
                'summary' => [
                    'total_items' => $cart->total_items,
                    'subtotal' => $cart->subtotal,
                    'tax_amount' => $cart->tax_amount,
                    'total' => $cart->total,
                    'tax_rate' => $cart->tax_rate
                ]
            ]
        ]);
    }

    // ==================== MÉTHODES ADMIN ====================

    /**
     * Statistiques des paniers (Admin seulement)
     */
    public function adminStats(): JsonResponse
    {
        // Statistiques générales
        $totalCarts = Cart::count();
        $activeCarts = Cart::where('status', 'active')->count();
        $convertedCarts = Cart::where('status', 'converted')->count();
        $abandonedCarts = Cart::where('status', 'abandoned')->count();
        
        // Valeur des paniers
        $totalCartValue = Cart::where('status', 'active')->sum('total');
        $avgCartValue = Cart::where('status', 'active')->avg('total');
        
        // Paniers par mois (6 derniers mois)
        $cartsPerMonth = Cart::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total) as total_value')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Taux de conversion
        $conversionRate = $totalCarts > 0 ? round(($convertedCarts / $totalCarts) * 100, 2) : 0;
        
        // Paniers abandonnés récemment
        $recentAbandonedCarts = Cart::where('status', 'abandoned')
            ->with(['user:id,username', 'items'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Statistiques des paniers récupérées',
            'data' => [
                'overview' => [
                    'total_carts' => $totalCarts,
                    'active_carts' => $activeCarts,
                    'converted_carts' => $convertedCarts,
                    'abandoned_carts' => $abandonedCarts,
                    'conversion_rate' => $conversionRate,
                    'total_cart_value' => round($totalCartValue, 2),
                    'avg_cart_value' => round($avgCartValue ?? 0, 2)
                ],
                'carts_per_month' => $cartsPerMonth,
                'recent_abandoned_carts' => $recentAbandonedCarts
            ]
        ]);
    }

    /**
     * Liste des paniers avec filtres (Admin seulement)
     */
    public function adminIndex(Request $request): JsonResponse
    {
        $query = Cart::with(['user:id,username,email', 'items']);

        // Filtres
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('min_total')) {
            $query->where('total', '>=', $request->min_total);
        }

        if ($request->has('max_total')) {
            $query->where('total', '<=', $request->max_total);
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $carts = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'message' => 'Paniers récupérés (admin)',
            'data' => $carts
        ]);
    }
}
