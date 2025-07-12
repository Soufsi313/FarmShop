<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductLike;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Affichage public des produits avec pagination, tri et filtres
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::with(['category'])
            ->where('is_active', true)
            ->where('quantity', '>', 0); // Seulement les produits en stock

        // Filtrage par catégorie
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filtrage par type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filtrage par statut de stock
        if ($request->has('stock_status')) {
            switch ($request->stock_status) {
                case 'in_stock':
                    $query->where('quantity', '>', 0);
                    break;
                case 'low_stock':
                    $query->whereColumn('quantity', '<=', 'low_stock_threshold');
                    break;
                case 'out_of_stock':
                    $query->where('quantity', 0);
                    break;
            }
        }

        // Filtrage par prix
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSorts = ['name', 'price', 'created_at', 'quantity', 'likes_count'];
        if (in_array($sortBy, $allowedSorts)) {
            if ($sortBy === 'likes_count') {
                $query->withCount('likes')->orderBy('likes_count', $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        }

        $products = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Produits récupérés avec succès',
            'data' => $products
        ]);
    }

    /**
     * Recherche de produits
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2',
            'per_page' => 'integer|min:1|max:50'
        ]);

        $searchTerm = $request->get('q');
        
        $products = Product::with(['category'])
            ->where('is_active', true)
            ->where('quantity', '>', 0)
            ->where(function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('short_description', 'LIKE', "%{$searchTerm}%")
                      ->orWhereHas('category', function ($q) use ($searchTerm) {
                          $q->where('name', 'LIKE', "%{$searchTerm}%");
                      });
            })
            ->orderBy('name')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Résultats de recherche',
            'data' => $products,
            'search_term' => $searchTerm
        ]);
    }

    /**
     * Affichage public d'un produit
     */
    public function show(Product $product): JsonResponse
    {
        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Produit non disponible'
            ], 404);
        }

        $product->load(['category']);
        
        // Ajouter les informations de like et wishlist si l'utilisateur est connecté
        if (Auth::check()) {
            $user = Auth::user();
            $product->is_liked = $product->likes()->where('user_id', $user->id)->exists();
            $product->is_in_wishlist = $user->wishlists()->where('product_id', $product->id)->exists();
        }

        return response()->json([
            'success' => true,
            'message' => 'Produit récupéré avec succès',
            'data' => $product
        ]);
    }

    /**
     * Produits par catégorie
     */
    public function byCategory(Category $category, Request $request): JsonResponse
    {
        if (!$category->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Catégorie non disponible'
            ], 404);
        }

        $products = Product::with(['category'])
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->where('quantity', '>', 0)
            ->orderBy('name')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => "Produits de la catégorie {$category->name}",
            'data' => $products,
            'category' => $category
        ]);
    }

    /**
     * Toggle like sur un produit
     */
    public function toggleLike(Product $product): JsonResponse
    {
        $user = Auth::user();
        
        $existingLike = ProductLike::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $message = 'Produit retiré des favoris';
            $liked = false;
        } else {
            ProductLike::create([
                'user_id' => $user->id,
                'product_id' => $product->id
            ]);
            $message = 'Produit ajouté aux favoris';
            $liked = true;
        }

        $likesCount = $product->likes()->count();

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'liked' => $liked,
                'likes_count' => $likesCount
            ]
        ]);
    }

    /**
     * Toggle wishlist
     */
    public function toggleWishlist(Product $product): JsonResponse
    {
        $user = Auth::user();
        
        $existingWishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingWishlist) {
            $existingWishlist->delete();
            $message = 'Produit retiré de la liste de souhaits';
            $inWishlist = false;
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $product->id
            ]);
            $message = 'Produit ajouté à la liste de souhaits';
            $inWishlist = true;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'in_wishlist' => $inWishlist
            ]
        ]);
    }

    /**
     * Récupérer la wishlist de l'utilisateur
     */
    public function getWishlist(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        $wishlist = $user->wishlists()
            ->with(['product' => function ($query) {
                $query->with('category')->where('is_active', true);
            }])
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Liste de souhaits récupérée',
            'data' => $wishlist
        ]);
    }

    /**
     * Récupérer les produits likés par l'utilisateur
     */
    public function getLikedProducts(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        $likedProducts = ProductLike::where('user_id', $user->id)
            ->with(['product' => function ($query) {
                $query->with('category')->where('is_active', true);
            }])
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Produits favoris récupérés',
            'data' => $likedProducts
        ]);
    }

    // ==================== MÉTHODES ADMIN ====================

    /**
     * Liste admin avec tous les produits (actifs et supprimés)
     */
    public function adminIndex(Request $request): JsonResponse
    {
        $query = Product::withTrashed()->with(['category']);

        // Filtres admin
        if ($request->has('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', true)->whereNull('deleted_at');
                    break;
                case 'inactive':
                    $query->where('is_active', false)->whereNull('deleted_at');
                    break;
                case 'deleted':
                    $query->whereNotNull('deleted_at');
                    break;
            }
        }

        $products = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'message' => 'Produits récupérés (admin)',
            'data' => $products
        ]);
    }

    /**
     * Affichage admin d'un produit
     */
    public function adminShow(Product $product): JsonResponse
    {
        $product->load(['category', 'likes', 'wishlists']);
        
        return response()->json([
            'success' => true,
            'message' => 'Produit récupéré (admin)',
            'data' => $product
        ]);
    }

    /**
     * Création d'un produit (Admin)
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'rental_price_per_day' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'type' => ['required', Rule::in(['sale', 'rental', 'both'])],
            'category_id' => 'required|exists:categories,id',
            'sku' => 'nullable|string|unique:products,sku',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:255',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'out_of_stock_threshold' => 'nullable|integer|min:0',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            // Gestion des images
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    $imagePaths[] = $path;
                }
            }
            $validated['images'] = $imagePaths;

            $product = Product::create($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Produit créé avec succès',
                'data' => $product->load('category')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Supprimer les images uploadées en cas d'erreur
            foreach ($imagePaths as $path) {
                Storage::disk('public')->delete($path);
            }

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du produit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mise à jour d'un produit (Admin)
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'rental_price_per_day' => 'nullable|numeric|min:0',
            'quantity' => 'sometimes|required|integer|min:0',
            'type' => ['sometimes', 'required', Rule::in(['sale', 'rental', 'both'])],
            'category_id' => 'sometimes|required|exists:categories,id',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:255',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'out_of_stock_threshold' => 'nullable|integer|min:0',
            'new_images' => 'nullable|array|max:5',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'string',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            // Gestion des nouvelles images
            $currentImages = $product->images ?? [];
            
            // Supprimer les images demandées
            if (!empty($validated['remove_images'])) {
                foreach ($validated['remove_images'] as $imageToRemove) {
                    if (in_array($imageToRemove, $currentImages)) {
                        Storage::disk('public')->delete($imageToRemove);
                        $currentImages = array_diff($currentImages, [$imageToRemove]);
                    }
                }
            }

            // Ajouter les nouvelles images
            if ($request->hasFile('new_images')) {
                foreach ($request->file('new_images') as $image) {
                    $path = $image->store('products', 'public');
                    $currentImages[] = $path;
                }
            }

            $validated['images'] = array_values($currentImages);
            unset($validated['new_images'], $validated['remove_images']);

            $product->update($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Produit mis à jour avec succès',
                'data' => $product->load('category')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du produit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Suppression d'un produit (Admin)
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produit supprimé avec succès'
        ]);
    }

    /**
     * Restauration d'un produit (Admin)
     */
    public function restore($id): JsonResponse
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();

        return response()->json([
            'success' => true,
            'message' => 'Produit restauré avec succès',
            'data' => $product->load('category')
        ]);
    }

    /**
     * Toggle du statut actif/inactif
     */
    public function toggleStatus(Product $product): JsonResponse
    {
        $product->update(['is_active' => !$product->is_active]);

        $status = $product->is_active ? 'activé' : 'désactivé';

        return response()->json([
            'success' => true,
            'message' => "Produit {$status} avec succès",
            'data' => $product
        ]);
    }

    /**
     * Mise à jour du stock
     */
    public function updateStock(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
            'operation' => 'required|in:set,add,subtract',
            'reason' => 'nullable|string|max:255'
        ]);

        $oldQuantity = $product->quantity;

        switch ($validated['operation']) {
            case 'set':
                $newQuantity = $validated['quantity'];
                break;
            case 'add':
                $newQuantity = $oldQuantity + $validated['quantity'];
                break;
            case 'subtract':
                $newQuantity = max(0, $oldQuantity - $validated['quantity']);
                break;
        }

        $product->update(['quantity' => $newQuantity]);

        return response()->json([
            'success' => true,
            'message' => 'Stock mis à jour avec succès',
            'data' => [
                'product' => $product,
                'old_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'operation' => $validated['operation'],
                'reason' => $validated['reason'] ?? null
            ]
        ]);
    }

    /**
     * Alertes de stock
     */
    public function getStockAlerts(): JsonResponse
    {
        $lowStockProducts = Product::whereColumn('quantity', '<=', 'low_stock_threshold')
            ->where('is_active', true)
            ->with('category')
            ->get();

        $outOfStockProducts = Product::where('quantity', '<=', DB::raw('COALESCE(out_of_stock_threshold, 0)'))
            ->where('is_active', true)
            ->with('category')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Alertes de stock récupérées',
            'data' => [
                'low_stock' => $lowStockProducts,
                'out_of_stock' => $outOfStockProducts,
                'low_stock_count' => $lowStockProducts->count(),
                'out_of_stock_count' => $outOfStockProducts->count()
            ]
        ]);
    }

    /**
     * Produits avec stock faible
     */
    public function getLowStockProducts(Request $request): JsonResponse
    {
        $products = Product::whereColumn('quantity', '<=', 'low_stock_threshold')
            ->where('is_active', true)
            ->with('category')
            ->orderBy('quantity')
            ->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'message' => 'Produits avec stock faible',
            'data' => $products
        ]);
    }
}
