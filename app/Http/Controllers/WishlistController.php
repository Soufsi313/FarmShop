<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WishlistController extends Controller
{
    /**
     * Afficher la wishlist de l'utilisateur connecté
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        $wishlist = $user->wishlists()
            ->with(['product' => function ($query) {
                $query->with('category')
                      ->where('is_active', true);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        // Filtrer les produits qui ne sont plus actifs
        $wishlist->getCollection()->transform(function ($wishlistItem) {
            if (!$wishlistItem->product || !$wishlistItem->product->is_active) {
                return null;
            }
            return $wishlistItem;
        })->filter();

        return response()->json([
            'success' => true,
            'message' => 'Wishlist récupérée avec succès',
            'data' => $wishlist,
            'total_items' => $user->wishlists()->count()
        ]);
    }

    /**
     * Ajouter un produit à la wishlist
     */
    public function store(Product $product): JsonResponse
    {
        $user = Auth::user();

        // Vérifier que le produit est actif
        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit n\'est plus disponible'
            ], 400);
        }

        // Vérifier si le produit est déjà dans la wishlist
        $existingWishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingWishlist) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit est déjà dans votre wishlist'
            ], 409);
        }

        // Ajouter le produit à la wishlist
        $wishlistItem = Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id
        ]);

        $wishlistItem->load(['product.category']);

        return response()->json([
            'success' => true,
            'message' => 'Produit ajouté à la wishlist avec succès',
            'data' => $wishlistItem,
            'total_items' => $user->wishlists()->count()
        ], 201);
    }

    /**
     * Retirer un produit de la wishlist
     */
    public function destroy(Product $product): JsonResponse
    {
        $user = Auth::user();

        // Chercher l'élément dans la wishlist
        $wishlistItem = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if (!$wishlistItem) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit n\'est pas dans votre wishlist'
            ], 404);
        }

        // Supprimer l'élément de la wishlist
        $wishlistItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produit retiré de la wishlist avec succès',
            'total_items' => $user->wishlists()->count()
        ]);
    }

    /**
     * Toggle - Ajouter ou retirer un produit de la wishlist
     */
    public function toggle(Product $product): JsonResponse
    {
        $user = Auth::user();

        // Vérifier que le produit est actif
        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit n\'est plus disponible'
            ], 400);
        }

        // Chercher l'élément dans la wishlist
        $existingWishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingWishlist) {
            // Retirer de la wishlist
            $existingWishlist->delete();
            $message = 'Produit retiré de la wishlist';
            $inWishlist = false;
            $wishlistItem = null;
        } else {
            // Ajouter à la wishlist
            $wishlistItem = Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $product->id
            ]);
            $wishlistItem->load(['product.category']);
            $message = 'Produit ajouté à la wishlist';
            $inWishlist = true;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'in_wishlist' => $inWishlist,
                'wishlist_item' => $wishlistItem,
                'total_items' => $user->wishlists()->count()
            ]
        ]);
    }

    /**
     * Vider complètement la wishlist
     */
    public function clear(): JsonResponse
    {
        $user = Auth::user();
        
        $deletedCount = $user->wishlists()->delete();

        return response()->json([
            'success' => true,
            'message' => "Wishlist vidée avec succès ({$deletedCount} produits supprimés)",
            'deleted_count' => $deletedCount,
            'total_items' => 0
        ]);
    }

    /**
     * Obtenir le nombre d'éléments dans la wishlist
     */
    public function count(): JsonResponse
    {
        $user = Auth::user();
        $count = $user->wishlists()->count();

        return response()->json([
            'success' => true,
            'message' => 'Nombre d\'éléments dans la wishlist',
            'data' => [
                'count' => $count
            ]
        ]);
    }

    /**
     * Vérifier si un produit spécifique est dans la wishlist
     */
    public function check(Product $product): JsonResponse
    {
        $user = Auth::user();
        
        $inWishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->exists();

        return response()->json([
            'success' => true,
            'message' => 'Statut du produit dans la wishlist',
            'data' => [
                'in_wishlist' => $inWishlist,
                'product_id' => $product->id
            ]
        ]);
    }

    // ==================== MÉTHODES ADMIN ====================

    /**
     * Statistiques des wishlists (Admin seulement)
     */
    public function adminStats(): JsonResponse
    {
        // Statistiques générales
        $totalWishlists = Wishlist::count();
        $totalUniqueUsers = Wishlist::distinct('user_id')->count();
        $totalWishlistedProducts = Wishlist::distinct('product_id')->count();
        $avgItemsPerUser = $totalUniqueUsers > 0 ? round($totalWishlists / $totalUniqueUsers, 2) : 0;

        // Top 10 des produits les plus ajoutés en wishlist
        $topWishlistedProducts = Product::withCount('wishlists')
            ->with('category')
            ->orderBy('wishlists_count', 'desc')
            ->limit(10)
            ->get();

        // Top 10 des utilisateurs avec le plus d'éléments en wishlist
        $topActiveUsers = Wishlist::select('user_id', DB::raw('COUNT(*) as wishlist_count'))
            ->with(['user:id,username,email'])
            ->groupBy('user_id')
            ->orderBy('wishlist_count', 'desc')
            ->limit(10)
            ->get();

        // Évolution des ajouts en wishlist par mois (6 derniers mois)
        $wishlistsPerMonth = Wishlist::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Top 10 des catégories les plus wishlistées
        $topWishlistedCategories = DB::table('wishlists')
            ->join('products', 'wishlists.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.id', 'categories.name', DB::raw('COUNT(*) as wishlist_count'))
            ->where('products.is_active', true)
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('wishlist_count', 'desc')
            ->limit(10)
            ->get();

        // Produits récemment ajoutés en wishlist
        $recentWishlists = Wishlist::with(['product.category', 'user:id,username'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Valeur totale des produits en wishlist (estimation)
        $totalWishlistValue = DB::table('wishlists')
            ->join('products', 'wishlists.product_id', '=', 'products.id')
            ->where('products.is_active', true)
            ->sum('products.price');

        // Taux de conversion wishlist -> like (approximatif)
        $productsInWishlist = Wishlist::distinct('product_id')->count();
        $productsLiked = DB::table('product_likes')->distinct('product_id')->count();
        $conversionRate = $productsInWishlist > 0 ? round(($productsLiked / $productsInWishlist) * 100, 2) : 0;

        // Analyse par type de produit en wishlist
        $wishlistsByProductType = DB::table('wishlists')
            ->join('products', 'wishlists.product_id', '=', 'products.id')
            ->select('products.type', DB::raw('COUNT(*) as count'))
            ->where('products.is_active', true)
            ->groupBy('products.type')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Statistiques des wishlists récupérées',
            'data' => [
                'overview' => [
                    'total_wishlists' => $totalWishlists,
                    'unique_users' => $totalUniqueUsers,
                    'wishlisted_products' => $totalWishlistedProducts,
                    'avg_items_per_user' => $avgItemsPerUser,
                    'total_wishlist_value' => round($totalWishlistValue, 2),
                    'conversion_rate_to_likes' => $conversionRate
                ],
                'top_wishlisted_products' => $topWishlistedProducts,
                'top_active_users' => $topActiveUsers,
                'wishlists_per_month' => $wishlistsPerMonth,
                'top_wishlisted_categories' => $topWishlistedCategories,
                'recent_wishlists' => $recentWishlists,
                'wishlists_by_product_type' => $wishlistsByProductType
            ]
        ]);
    }

    /**
     * Gestion admin des wishlists - Liste complète
     */
    public function adminIndex(Request $request): JsonResponse
    {
        $query = Wishlist::with(['product.category', 'user:id,username,email']);

        // Filtres
        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('category_id')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        if ($request->has('product_type')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('type', $request->product_type);
            });
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSorts = ['created_at', 'product_id', 'user_id'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $wishlists = $query->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'message' => 'Wishlists récupérées (admin)',
            'data' => $wishlists
        ]);
    }

    /**
     * Supprimer un élément de wishlist spécifique (Admin)
     */
    public function adminDestroy(Wishlist $wishlist): JsonResponse
    {
        $productName = $wishlist->product->name;
        $userName = $wishlist->user->username;
        
        $wishlist->delete();

        return response()->json([
            'success' => true,
            'message' => "Élément de wishlist de {$userName} pour {$productName} supprimé avec succès"
        ]);
    }

    /**
     * Analyse détaillée d'un utilisateur spécifique (Admin)
     */
    public function adminUserAnalysis($userId): JsonResponse
    {
        $user = \App\Models\User::findOrFail($userId);

        // Wishlist de l'utilisateur
        $userWishlists = Wishlist::where('user_id', $userId)
            ->with(['product.category'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistiques de l'utilisateur
        $totalItems = $userWishlists->count();
        $totalValue = $userWishlists->sum(function ($wishlist) {
            return $wishlist->product->price ?? 0;
        });

        // Catégories préférées
        $preferredCategories = $userWishlists->groupBy('product.category.name')
            ->map(function ($items, $categoryName) {
                return [
                    'category' => $categoryName,
                    'count' => $items->count(),
                    'percentage' => round(($items->count() / max(1, $items->count())) * 100, 2)
                ];
            })
            ->sortByDesc('count')
            ->take(5)
            ->values();

        // Types de produits préférés
        $preferredTypes = $userWishlists->groupBy('product.type')
            ->map(function ($items, $type) {
                return [
                    'type' => $type,
                    'count' => $items->count()
                ];
            })
            ->sortByDesc('count')
            ->values();

        return response()->json([
            'success' => true,
            'message' => 'Analyse de l\'utilisateur récupérée',
            'data' => [
                'user' => $user->only(['id', 'username', 'email']),
                'overview' => [
                    'total_items' => $totalItems,
                    'total_value' => round($totalValue, 2),
                    'first_wishlist' => $userWishlists->last()?->created_at,
                    'last_wishlist' => $userWishlists->first()?->created_at
                ],
                'preferred_categories' => $preferredCategories,
                'preferred_types' => $preferredTypes,
                'wishlist_items' => $userWishlists
            ]
        ]);
    }

    /**
     * Afficher la page web de la wishlist
     */
    public function showPage(Request $request)
    {
        $user = Auth::user();
        
        $wishlist = $user->wishlists()
            ->with(['product' => function ($query) {
                $query->with('category')
                      ->where('is_active', true);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 12));

        // Filtrer les produits qui ne sont plus actifs
        $wishlist->getCollection()->transform(function ($wishlistItem) {
            if (!$wishlistItem->product || !$wishlistItem->product->is_active) {
                return null;
            }
            return $wishlistItem;
        })->filter();

        return view('wishlist.index', compact('wishlist'));
    }
}
