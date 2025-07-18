<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductLike;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductLikeController extends Controller
{
    /**
     * Afficher les produits les plus likés (accessible à tous)
     */
    public function index(Request $request): JsonResponse
    {
        $products = Product::with(['category'])
            ->withCount('likes')
            ->where('is_active', true)
            ->orderBy('likes_count', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Produits les plus likés récupérés avec succès',
            'data' => $products
        ]);
    }

    /**
     * Afficher les likes d'un produit spécifique (accessible à tous)
     */
    public function show(Product $product, Request $request): JsonResponse
    {
        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Produit non disponible'
            ], 404);
        }

        $likes = $product->likes()
            ->with(['user:id,username'])
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));

        $likesCount = $product->likes()->count();

        return response()->json([
            'success' => true,
            'message' => 'Likes du produit récupérés avec succès',
            'data' => [
                'product' => $product->load('category'),
                'likes' => $likes,
                'total_likes' => $likesCount
            ]
        ]);
    }

    /**
     * Liker un produit (utilisateurs connectés seulement)
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

        // Vérifier si l'utilisateur a déjà liké ce produit
        $existingLike = ProductLike::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingLike) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà liké ce produit'
            ], 409);
        }

        // Créer le like
        $like = ProductLike::create([
            'user_id' => $user->id,
            'product_id' => $product->id
        ]);

        $likesCount = $product->likes()->count();

        return response()->json([
            'success' => true,
            'message' => 'Produit liké avec succès',
            'data' => [
                'like' => $like,
                'likes_count' => $likesCount,
                'product' => $product->load('category')
            ]
        ], 201);
    }

    /**
     * Retirer le like d'un produit (utilisateurs connectés seulement)
     */
    public function destroy(Product $product): JsonResponse
    {
        $user = Auth::user();

        // Chercher le like de l'utilisateur pour ce produit
        $like = ProductLike::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if (!$like) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'avez pas liké ce produit'
            ], 404);
        }

        // Supprimer le like
        $like->delete();

        $likesCount = $product->likes()->count();

        return response()->json([
            'success' => true,
            'message' => 'Like retiré avec succès',
            'data' => [
                'likes_count' => $likesCount,
                'product' => $product->load('category')
            ]
        ]);
    }

    /**
     * Toggle like sur un produit (utilisateurs connectés seulement)
     */
    public function toggle(Product $product): JsonResponse
    {
        // Utiliser explicitement le guard web
        $user = Auth::guard('web')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être connecté pour effectuer cette action'
            ], 401);
        }

        // Vérifier que le produit est actif
        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit n\'est plus disponible'
            ], 400);
        }

        // Chercher le like existant
        $existingLike = ProductLike::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingLike) {
            // Retirer le like
            $existingLike->delete();
            $message = 'Like retiré avec succès';
            $liked = false;
        } else {
            // Ajouter le like
            ProductLike::create([
                'user_id' => $user->id,
                'product_id' => $product->id
            ]);
            $message = 'Produit liké avec succès';
            $liked = true;
        }

        $likesCount = $product->likes()->count();

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'is_liked' => $liked,
                'liked' => $liked, // Alias pour compatibilité
                'likes_count' => $likesCount,
                'product' => $product->load('category')
            ]
        ]);
    }

    /**
     * Récupérer les produits likés par l'utilisateur connecté
     */
    public function getUserLikes(Request $request): JsonResponse
    {
        $user = Auth::user();

        $likedProducts = ProductLike::where('user_id', $user->id)
            ->with(['product' => function ($query) {
                $query->with('category')->where('is_active', true);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Produits likés récupérés avec succès',
            'data' => $likedProducts
        ]);
    }

    /**
     * Vérifier si l'utilisateur a liké un produit spécifique
     */
    public function check(Product $product): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => true,
                'message' => 'Statut du like',
                'data' => [
                    'liked' => false,
                    'requires_auth' => true
                ]
            ]);
        }

        $user = Auth::user();
        $liked = ProductLike::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->exists();

        return response()->json([
            'success' => true,
            'message' => 'Statut du like vérifié',
            'data' => [
                'liked' => $liked,
                'product_id' => $product->id
            ]
        ]);
    }

    // ==================== MÉTHODES ADMIN ====================

    /**
     * Dashboard admin - Statistiques des likes
     */
    public function adminStats(): JsonResponse
    {
        // Statistiques générales
        $totalLikes = ProductLike::count();
        $totalUniqueUsers = ProductLike::distinct('user_id')->count();
        $totalLikedProducts = ProductLike::distinct('product_id')->count();

        // Top 10 des produits les plus likés
        $topLikedProducts = Product::withCount('likes')
            ->with('category')
            ->orderBy('likes_count', 'desc')
            ->limit(10)
            ->get();

        // Statistiques par mois (6 derniers mois)
        $likesPerMonth = ProductLike::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Utilisateurs les plus actifs (qui likent le plus)
        $topActiveUsers = ProductLike::select('user_id', DB::raw('COUNT(*) as likes_count'))
            ->with(['user:id,username'])
            ->groupBy('user_id')
            ->orderBy('likes_count', 'desc')
            ->limit(10)
            ->get();

        // Produits récemment likés
        $recentLikes = ProductLike::with(['product.category', 'user:id,username'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Statistiques des likes récupérées',
            'data' => [
                'overview' => [
                    'total_likes' => $totalLikes,
                    'unique_users' => $totalUniqueUsers,
                    'liked_products' => $totalLikedProducts,
                    'avg_likes_per_product' => $totalLikedProducts > 0 ? round($totalLikes / $totalLikedProducts, 2) : 0
                ],
                'top_liked_products' => $topLikedProducts,
                'likes_per_month' => $likesPerMonth,
                'top_active_users' => $topActiveUsers,
                'recent_likes' => $recentLikes
            ]
        ]);
    }

    /**
     * Gestion admin des likes - Liste complète
     */
    public function adminIndex(Request $request): JsonResponse
    {
        $query = ProductLike::with(['product.category', 'user:id,username']);

        // Filtres
        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $likes = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'message' => 'Likes récupérés (admin)',
            'data' => $likes
        ]);
    }

    /**
     * Supprimer un like spécifique (Admin)
     */
    public function adminDestroy(ProductLike $like): JsonResponse
    {
        $productName = $like->product->name;
        $userName = $like->user->username;
        
        $like->delete();

        return response()->json([
            'success' => true,
            'message' => "Like de {$userName} sur {$productName} supprimé avec succès"
        ]);
    }
}
