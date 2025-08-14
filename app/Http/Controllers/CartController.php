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
     * @OA\Get(
     *     path="/api/cart",
     *     tags={"Cart"},
     *     summary="Récupérer le panier de l'utilisateur",
     *     description="Affiche le contenu du panier de l'utilisateur connecté avec vérification de disponibilité",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Panier récupéré avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Panier récupéré avec succès"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="cart", ref="#/components/schemas/Cart"),
     *                 @OA\Property(
     *                     property="items",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/CartItem")
     *                 ),
     *                 @OA\Property(
     *                     property="unavailable_items",
     *                     type="array",
     *                     @OA\Items(type="object")
     *                 ),
     *                 @OA\Property(
     *                     property="summary",
     *                     type="object",
     *                     @OA\Property(property="subtotal", type="number", format="float", example=125.50),
     *                     @OA\Property(property="shipping", type="number", format="float", example=5.99),
     *                     @OA\Property(property="total", type="number", format="float", example=131.49)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     * 
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
                'summary' => $cart->getCostSummary() // 🚚 Inclut automatiquement les frais de livraison
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/cart/products/{product}",
     *     tags={"Cart"},
     *     summary="Ajouter un produit au panier",
     *     description="Ajoute un produit au panier de l'utilisateur connecté avec vérification de stock",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         description="ID du produit à ajouter",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"quantity"},
     *             @OA\Property(property="quantity", type="integer", minimum=1, maximum=100, example=2, description="Quantité à ajouter")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Produit ajouté au panier avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Produit ajouté au panier avec succès"),
     *             @OA\Property(property="data", ref="#/components/schemas/CartItem")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Stock insuffisant",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Données invalides",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     * 
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
     * @OA\Put(
     *     path="/api/cart/update-quantity",
     *     tags={"Cart"},
     *     summary="Mettre à jour la quantité d'un produit",
     *     description="Met à jour la quantité d'un produit spécifique dans le panier",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id", "quantity"},
     *             @OA\Property(property="product_id", type="integer", example=1, description="ID du produit"),
     *             @OA\Property(property="quantity", type="integer", minimum=1, maximum=100, example=3, description="Nouvelle quantité")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quantité mise à jour avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Quantité mise à jour avec succès"),
     *             @OA\Property(property="data", ref="#/components/schemas/CartItem")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Stock insuffisant",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produit non trouvé dans le panier",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Données invalides",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     * 
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
     * @OA\Delete(
     *     path="/api/cart/remove/{product}",
     *     tags={"Cart"},
     *     summary="Supprimer un produit du panier",
     *     description="Supprime complètement un produit du panier de l'utilisateur",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         description="ID du produit à supprimer",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produit supprimé du panier avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Produit supprimé du panier avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produit non trouvé dans le panier",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     * 
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
     * @OA\Delete(
     *     path="/api/cart/clear",
     *     tags={"Cart"},
     *     summary="Vider le panier",
     *     description="Supprime tous les produits du panier de l'utilisateur",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Panier vidé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Panier vidé avec succès"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="items_removed", type="integer", example=5)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Aucun panier actif trouvé",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     * 
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
     * @OA\Get(
     *     path="/api/cart/check-availability",
     *     tags={"Cart"},
     *     summary="Vérifier la disponibilité des produits",
     *     description="Vérifie la disponibilité et le stock de tous les produits dans le panier",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Vérification de disponibilité effectuée",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Vérification de disponibilité effectuée"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="all_available", type="boolean", example=true),
     *                 @OA\Property(property="available_items", type="integer", example=3),
     *                 @OA\Property(property="unavailable_items", type="integer", example=0),
     *                 @OA\Property(
     *                     property="issues",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="product_id", type="integer", example=1),
     *                         @OA\Property(property="product_name", type="string", example="Tomates Bio"),
     *                         @OA\Property(property="requested_quantity", type="integer", example=5),
     *                         @OA\Property(property="available_quantity", type="integer", example=2),
     *                         @OA\Property(property="issue", type="string", example="Stock insuffisant")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Aucun panier actif trouvé",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     * 
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
     * @OA\Get(
     *     path="/api/cart/summary",
     *     tags={"Cart"},
     *     summary="Résumé du panier",
     *     description="Obtient un résumé détaillé du panier avec totaux et calculs",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Résumé du panier récupéré avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Résumé du panier récupéré"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="total_items", type="integer", example=3),
     *                 @OA\Property(property="subtotal", type="number", format="float", example=125.50),
     *                 @OA\Property(property="tax_amount", type="number", format="float", example=25.10),
     *                 @OA\Property(property="shipping_cost", type="number", format="float", example=5.99),
     *                 @OA\Property(property="total", type="number", format="float", example=156.59),
     *                 @OA\Property(property="is_empty", type="boolean", example=false),
     *                 @OA\Property(property="currency", type="string", example="EUR")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     * 
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
            'data' => array_merge([
                'cart_id' => $cart->id,
                'total_items' => $cart->total_items,
                'is_empty' => $cart->isEmpty(),
                'created_at' => $cart->created_at,
                'expires_at' => $cart->expires_at
            ], $cart->getCostSummary()) // 🚚 Inclut les frais de livraison
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/cart/prepare-checkout",
     *     tags={"Cart", "Checkout"},
     *     summary="Préparer le panier pour commande",
     *     description="Effectue toutes les vérifications nécessaires avant de procéder au checkout",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Panier prêt pour la commande",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Panier prêt pour la commande"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="cart_id", type="integer", example=1),
     *                 @OA\Property(property="ready_for_checkout", type="boolean", example=true),
     *                 @OA\Property(property="total_items", type="integer", example=3),
     *                 @OA\Property(property="final_total", type="number", format="float", example=156.59),
     *                 @OA\Property(
     *                     property="validation_results",
     *                     type="object",
     *                     @OA\Property(property="stock_available", type="boolean", example=true),
     *                     @OA\Property(property="prices_valid", type="boolean", example=true),
     *                     @OA\Property(property="delivery_possible", type="boolean", example=true)
     *                 ),
     *                 @OA\Property(
     *                     property="warnings",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     example={}
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Le panier n'est pas prêt pour la commande",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Aucun panier actif trouvé",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     * 
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
                'summary' => $cart->getCostSummary() // 🚚 Inclut les frais de livraison
            ]
        ]);
    }

    // ==================== MÉTHODES ADMIN ====================

    /**
     * @OA\Get(
     *     path="/api/admin/carts/stats",
     *     tags={"Admin", "Cart", "Statistics"},
     *     summary="Statistiques des paniers",
     *     description="Récupère les statistiques détaillées des paniers pour l'administration",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Statistiques des paniers récupérées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Statistiques des paniers récupérées"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="overview",
     *                     type="object",
     *                     @OA\Property(property="total_carts", type="integer", example=1250),
     *                     @OA\Property(property="active_carts", type="integer", example=85),
     *                     @OA\Property(property="converted_carts", type="integer", example=950),
     *                     @OA\Property(property="abandoned_carts", type="integer", example=215),
     *                     @OA\Property(property="conversion_rate", type="number", format="float", example=76.0)
     *                 ),
     *                 @OA\Property(
     *                     property="financial",
     *                     type="object",
     *                     @OA\Property(property="total_cart_value", type="number", format="float", example=12500.50),
     *                     @OA\Property(property="average_cart_value", type="number", format="float", example=147.06),
     *                     @OA\Property(property="potential_revenue", type="number", format="float", example=1850.75)
     *                 ),
     *                 @OA\Property(
     *                     property="trends",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="period", type="string", example="2024-01"),
     *                         @OA\Property(property="cart_count", type="integer", example=120),
     *                         @OA\Property(property="total_value", type="number", format="float", example=2500.00)
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès refusé",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     * 
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
     * @OA\Get(
     *     path="/api/admin/carts",
     *     tags={"Admin", "Cart"},
     *     summary="Liste des paniers avec filtres",
     *     description="Récupère une liste paginée de tous les paniers avec options de filtrage",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filtrer par statut du panier",
     *         required=false,
     *         @OA\Schema(type="string", enum={"active", "converted", "abandoned"}, example="active")
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="Filtrer par ID utilisateur",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="min_total",
     *         in="query",
     *         description="Montant minimum du panier",
     *         required=false,
     *         @OA\Schema(type="number", format="float", example=50.00)
     *     ),
     *     @OA\Parameter(
     *         name="max_total",
     *         in="query",
     *         description="Montant maximum du panier",
     *         required=false,
     *         @OA\Schema(type="number", format="float", example=500.00)
     *     ),
     *     @OA\Parameter(
     *         name="date_from",
     *         in="query",
     *         description="Date de création à partir de",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2024-01-01")
     *     ),
     *     @OA\Parameter(
     *         name="date_to",
     *         in="query",
     *         description="Date de création jusqu'à",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2024-12-31")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Nombre d'éléments par page",
     *         required=false,
     *         @OA\Schema(type="integer", example=20)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des paniers récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Paniers récupérés (admin)"),
     *             @OA\Property(property="data", ref="#/components/schemas/PaginatedResponse")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès refusé",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     * 
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
