<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductLikeController extends Controller
{
    /**
     * Constructor - require authentication for most methods.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'mostLiked', 'statistics']);
    }

    /**
     * Display all likes for the authenticated user.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        if (!$userId) {
            // Pour les invités, afficher les produits les plus likés
            return $this->mostLiked($request);
        }

        $query = ProductLike::where('user_id', $userId)
            ->with(['product' => function ($q) {
                $q->select('id', 'name', 'slug', 'price', 'main_image', 'is_active', 'quantity')
                  ->where('is_active', true);
            }])
            ->latest();

        // Filter by search term
        if ($request->filled('search')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->expectsJson()) {
            $likes = $query->paginate(20);
            return response()->json([
                'success' => true,
                'data' => $likes,
                'message' => 'Produits likés récupérés avec succès'
            ]);
        }

        $likes = $query->paginate(20);
        return view('likes.index', compact('likes'));
    }

    /**
     * Like or unlike a product.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id'
        ]);

        $productId = $request->product_id;
        $userId = Auth::id();

        // Check if product is active
        $product = Product::where('id', $productId)
            ->where('is_active', true)
            ->first();

        if (!$product) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produit non disponible'
                ], 404);
            }

            return back()->with('error', 'Produit non disponible');
        }

        try {
            DB::beginTransaction();

            $existingLike = ProductLike::where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();

            if ($existingLike) {
                // Unlike - supprimer le like
                $existingLike->delete();
                
                // Décrémenter le compteur sur le produit
                $product->decrement('likes_count');
                
                $action = 'unliked';
                $message = 'Like retiré du produit';
            } else {
                // Like - ajouter le like
                ProductLike::create([
                    'user_id' => $userId,
                    'product_id' => $productId
                ]);
                
                // Incrémenter le compteur sur le produit
                $product->increment('likes_count');
                
                $action = 'liked';
                $message = 'Produit liké avec succès';
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'action' => $action,
                        'liked' => $action === 'liked',
                        'likes_count' => $product->fresh()->likes_count
                    ],
                    'message' => $message
                ]);
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la modification du like'
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la modification du like');
        }
    }

    /**
     * Remove a like (alternative to store method).
     */
    public function destroy(Request $request, $productId)
    {
        $userId = Auth::id();

        $like = ProductLike::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if (!$like) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Like non trouvé'
                ], 404);
            }

            return back()->with('error', 'Like non trouvé');
        }

        try {
            DB::beginTransaction();

            $product = $like->product;
            $like->delete();

            // Décrémenter le compteur sur le produit
            if ($product) {
                $product->decrement('likes_count');
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Like retiré avec succès'
                ]);
            }

            return back()->with('success', 'Like retiré avec succès');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du like'
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la suppression du like');
        }
    }

    /**
     * Check if a product is liked by the authenticated user.
     */
    public function check(Request $request, $productId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => true,
                'data' => ['liked' => false],
                'message' => 'Utilisateur non connecté'
            ]);
        }

        $liked = ProductLike::isLiked(Auth::id(), $productId);

        return response()->json([
            'success' => true,
            'data' => ['liked' => $liked],
            'message' => 'Statut du like vérifié'
        ]);
    }

    /**
     * Get the most liked products.
     */
    public function mostLiked(Request $request)
    {
        $limit = $request->get('limit', 20);
        
        $query = Product::where('is_active', true)
            ->where('likes_count', '>', 0)
            ->orderByDesc('likes_count')
            ->orderByDesc('created_at')
            ->limit($limit);

        // Filter by category if provided
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $query->get(),
                'message' => 'Produits les plus likés récupérés avec succès'
            ]);
        }

        $products = $query->paginate($limit);
        return view('likes.most-liked', compact('products'));
    }

    /**
     * Get like statistics.
     */
    public function statistics(Request $request)
    {
        $stats = [
            'total_likes' => ProductLike::count(),
            'users_who_liked' => ProductLike::distinct('user_id')->count(),
            'products_with_likes' => ProductLike::distinct('product_id')->count(),
            'products_without_likes' => Product::where('is_active', true)
                ->where('likes_count', 0)
                ->count(),
            'average_likes_per_product' => round(
                ProductLike::count() / max(Product::where('is_active', true)->count(), 1), 
                2
            ),
            'most_liked_product' => Product::where('is_active', true)
                ->orderByDesc('likes_count')
                ->first(['id', 'name', 'likes_count']),
            'top_liked_products' => Product::where('is_active', true)
                ->where('likes_count', '>', 0)
                ->orderByDesc('likes_count')
                ->limit(10)
                ->get(['id', 'name', 'likes_count']),
            'recent_likes' => ProductLike::with(['user:id,name', 'product:id,name'])
                ->latest()
                ->limit(10)
                ->get()
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Statistiques des likes récupérées avec succès'
            ]);
        }

        return view('admin.likes.statistics', compact('stats'));
    }

    /**
     * Clear all likes for the authenticated user.
     */
    public function clearUserLikes(Request $request)
    {
        $userId = Auth::id();

        try {
            DB::beginTransaction();

            // Récupérer tous les likes de l'utilisateur
            $userLikes = ProductLike::where('user_id', $userId)->get();
            
            // Décrémenter les compteurs des produits
            foreach ($userLikes as $like) {
                if ($like->product) {
                    $like->product->decrement('likes_count');
                }
            }

            // Supprimer tous les likes de l'utilisateur
            $deletedCount = ProductLike::where('user_id', $userId)->delete();

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => ['deleted_count' => $deletedCount],
                    'message' => $deletedCount . ' like(s) supprimé(s)'
                ]);
            }

            return back()->with('success', $deletedCount . ' like(s) supprimé(s)');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression des likes'
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la suppression des likes');
        }
    }

    /**
     * Get user's like count.
     */
    public function getUserLikeCount(Request $request)
    {
        $count = Auth::check() ? ProductLike::where('user_id', Auth::id())->count() : 0;

        return response()->json([
            'success' => true,
            'data' => ['count' => $count],
            'message' => 'Nombre de likes utilisateur'
        ]);
    }

    /**
     * Admin method: Get all likes with pagination.
     */
    public function adminIndex(Request $request)
    {
        $this->authorize('manage products'); // Require admin permission

        $query = ProductLike::with(['user:id,name,email', 'product:id,name,price'])
            ->latest();

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $query->paginate(50),
                'message' => 'Likes récupérés avec succès'
            ]);
        }

        $likes = $query->paginate(50);
        return view('admin.likes.index', compact('likes'));
    }
}
