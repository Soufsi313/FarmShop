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
     * @OA\Get(
     *     path="/api/products",
     *     operationId="getProducts",
     *     tags={"Products"},
     *     summary="Récupérer la liste des produits",
     *     description="Récupère une liste paginée de produits avec possibilité de filtrage et tri",
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="ID de la catégorie pour filtrer",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Type de produit (sale, rental, both)",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"sale", "rental", "both"}
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="stock_status",
     *         in="query",
     *         description="Statut du stock",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"in_stock", "low_stock", "out_of_stock"}
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Terme de recherche",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         description="Critère de tri",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"name", "price", "created_at", "popularity"}
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="sort_direction",
     *         in="query",
     *         description="Direction du tri",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"asc", "desc"}
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Nombre d'éléments par page",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, maximum=100, default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des produits récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Produits récupérés avec succès"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="total", type="integer"),
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/Product")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erreur de validation"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     * 
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
                'is_liked' => $liked,
                'liked' => $liked, // Alias pour compatibilité
                'likes_count' => $likesCount,
                'product' => $product->load('category')
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
                'in_wishlist' => $inWishlist,
                'is_wishlisted' => $inWishlist, // Alias pour compatibilité
                'total_items' => $user->wishlists()->count(),
                'product' => $product->load('category')
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
     * @OA\Get(
     *     path="/api/admin/products",
     *     tags={"Admin", "Products"},
     *     summary="Liste des produits (Admin)",
     *     description="Récupère tous les produits avec statut et filtres pour l'administration",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filtrer par statut du produit",
     *         required=false,
     *         @OA\Schema(type="string", enum={"active", "inactive", "deleted"}, example="active")
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
     *         description="Liste des produits récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Produits récupérés (admin)"),
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
     * @OA\Get(
     *     path="/api/admin/products/{product}",
     *     tags={"Admin", "Products"},
     *     summary="Détails d'un produit (Admin)",
     *     description="Récupère les détails complets d'un produit pour l'administration",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         description="ID du produit",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails du produit récupérés avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Produit récupéré (admin)"),
     *             @OA\Property(property="data", ref="#/components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produit non trouvé",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès refusé",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     * 
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
     * @OA\Post(
     *     path="/api/admin/products",
     *     tags={"Admin", "Products"},
     *     summary="Créer un produit",
     *     description="Crée un nouveau produit avec toutes ses propriétés",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "price", "quantity", "type", "category_id"},
     *             @OA\Property(property="name", type="string", maxLength=255, example="Tomates Bio"),
     *             @OA\Property(property="short_description", type="string", maxLength=500, example="Tomates biologiques fraîches"),
     *             @OA\Property(property="description", type="string", example="Tomates cultivées sans pesticides..."),
     *             @OA\Property(property="price", type="number", format="float", minimum=0, example=3.50),
     *             @OA\Property(property="rental_price_per_day", type="number", format="float", minimum=0, example=0.50),
     *             @OA\Property(property="quantity", type="integer", minimum=0, example=100),
     *             @OA\Property(property="type", type="string", enum={"sale", "rental", "both"}, example="sale"),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="sku", type="string", example="TOM-BIO-001"),
     *             @OA\Property(property="weight_kg", type="number", format="float", minimum=0, example=0.5),
     *             @OA\Property(property="min_rental_days", type="integer", minimum=1, example=1),
     *             @OA\Property(property="max_rental_days", type="integer", minimum=1, example=30),
     *             @OA\Property(property="deposit_amount", type="number", format="float", minimum=0, example=10.0),
     *             @OA\Property(property="is_organic", type="boolean", example=true),
     *             @OA\Property(property="is_active", type="boolean", example=true),
     *             @OA\Property(
     *                 property="images",
     *                 type="array",
     *                 @OA\Items(type="string", format="binary"),
     *                 description="Images du produit"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Produit créé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Produit créé avec succès"),
     *             @OA\Property(property="data", ref="#/components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Données de validation invalides",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès refusé",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     * 
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
     * @OA\Put(
     *     path="/api/admin/products/{product}",
     *     tags={"Admin", "Products"},
     *     summary="Mettre à jour un produit",
     *     description="Met à jour les informations d'un produit existant",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         description="ID du produit",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=255, example="Tomates Bio Premium"),
     *             @OA\Property(property="short_description", type="string", maxLength=500, example="Tomates biologiques premium"),
     *             @OA\Property(property="description", type="string", example="Description mise à jour..."),
     *             @OA\Property(property="price", type="number", format="float", minimum=0, example=4.00),
     *             @OA\Property(property="rental_price_per_day", type="number", format="float", minimum=0, example=0.60),
     *             @OA\Property(property="quantity", type="integer", minimum=0, example=150),
     *             @OA\Property(property="type", type="string", enum={"sale", "rental", "both"}, example="sale"),
     *             @OA\Property(property="category_id", type="integer", example=2),
     *             @OA\Property(property="sku", type="string", example="TOM-BIO-002"),
     *             @OA\Property(property="weight", type="number", format="float", minimum=0, example=0.6),
     *             @OA\Property(property="dimensions", type="string", example="10x10x5 cm"),
     *             @OA\Property(property="low_stock_threshold", type="integer", minimum=0, example=10),
     *             @OA\Property(property="out_of_stock_threshold", type="integer", minimum=0, example=0),
     *             @OA\Property(property="is_active", type="boolean", example=true),
     *             @OA\Property(
     *                 property="new_images",
     *                 type="array",
     *                 @OA\Items(type="string", format="binary"),
     *                 description="Nouvelles images à ajouter"
     *             ),
     *             @OA\Property(
     *                 property="remove_images",
     *                 type="array",
     *                 @OA\Items(type="string"),
     *                 description="Noms des images à supprimer"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produit mis à jour avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Produit mis à jour avec succès"),
     *             @OA\Property(property="data", ref="#/components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produit non trouvé",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Données de validation invalides",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès refusé",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     * 
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
     * @OA\Delete(
     *     path="/api/admin/products/{product}",
     *     tags={"Admin", "Products"},
     *     summary="Supprimer un produit",
     *     description="Supprime un produit (soft delete)",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         description="ID du produit",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produit supprimé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Produit supprimé avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produit non trouvé",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès refusé",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     * 
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
     * @OA\Post(
     *     path="/api/admin/products/{id}/restore",
     *     tags={"Admin", "Products"},
     *     summary="Restaurer un produit",
     *     description="Restaure un produit supprimé",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID du produit supprimé",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produit restauré avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Produit restauré avec succès"),
     *             @OA\Property(property="data", ref="#/components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produit non trouvé",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès refusé",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     * 
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
     * @OA\Patch(
     *     path="/api/admin/products/{product}/toggle-status",
     *     tags={"Admin", "Products"},
     *     summary="Basculer le statut du produit",
     *     description="Active ou désactive un produit",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         description="ID du produit",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Statut du produit modifié avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Produit activé avec succès"),
     *             @OA\Property(property="data", ref="#/components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produit non trouvé",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès refusé",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     * 
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
     * @OA\Get(
     *     path="/api/admin/products/stock/alerts",
     *     tags={"Admin", "Products", "Stock"},
     *     summary="Alertes de stock",
     *     description="Récupère les alertes de stock pour le dashboard admin",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Alertes de stock récupérées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Alertes de stock récupérées"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="summary",
     *                     type="object",
     *                     @OA\Property(property="total_products", type="integer", example=250),
     *                     @OA\Property(property="out_of_stock", type="integer", example=5),
     *                     @OA\Property(property="critical_stock", type="integer", example=12),
     *                     @OA\Property(property="low_stock", type="integer", example=23)
     *                 ),
     *                 @OA\Property(
     *                     property="alerts_by_type",
     *                     type="object",
     *                     @OA\Property(
     *                         property="out_of_stock",
     *                         type="array",
     *                         @OA\Items(ref="#/components/schemas/Product")
     *                     ),
     *                     @OA\Property(
     *                         property="critical_stock",
     *                         type="array",
     *                         @OA\Items(ref="#/components/schemas/Product")
     *                     ),
     *                     @OA\Property(
     *                         property="low_stock",
     *                         type="array",
     *                         @OA\Items(ref="#/components/schemas/Product")
     *                     )
     *                 ),
     *                 @OA\Property(property="total_alerts", type="integer", example=40),
     *                 @OA\Property(property="urgent_alerts", type="integer", example=17)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès refusé",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     * 
     * Obtenir les alertes de stock pour le dashboard admin
     */
    public function getStockAlerts(Request $request): JsonResponse
    {
        $alerts = Product::getStockAlerts();
        
        $alertsByType = [
            'out_of_stock' => $alerts->filter(fn($p) => $p->is_out_of_stock),
            'critical_stock' => $alerts->filter(fn($p) => $p->is_critical_stock),
            'low_stock' => $alerts->filter(fn($p) => $p->is_low_stock)
        ];

        return response()->json([
            'success' => true,
            'message' => 'Alertes de stock récupérées',
            'data' => [
                'summary' => Product::getStockStatistics(),
                'alerts_by_type' => [
                    'out_of_stock' => $alertsByType['out_of_stock']->values(),
                    'critical_stock' => $alertsByType['critical_stock']->values(),
                    'low_stock' => $alertsByType['low_stock']->values()
                ],
                'total_alerts' => $alerts->count(),
                'urgent_alerts' => $alertsByType['out_of_stock']->count() + $alertsByType['critical_stock']->count()
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/admin/products/stock/dashboard",
     *     tags={"Admin", "Products", "Stock", "Dashboard"},
     *     summary="Dashboard de stock",
     *     description="Récupère les statistiques globales de stock pour le dashboard admin",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Statistiques de stock récupérées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Statistiques de stock récupérées"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="overview",
     *                     type="object",
     *                     @OA\Property(property="total_products", type="integer", example=250),
     *                     @OA\Property(property="active_products", type="integer", example=235),
     *                     @OA\Property(property="inactive_products", type="integer", example=15),
     *                     @OA\Property(property="total_stock_value", type="number", format="float", example=125000.50)
     *                 ),
     *                 @OA\Property(
     *                     property="stock_status",
     *                     type="object",
     *                     @OA\Property(property="in_stock", type="integer", example=210),
     *                     @OA\Property(property="low_stock", type="integer", example=23),
     *                     @OA\Property(property="critical_stock", type="integer", example=12),
     *                     @OA\Property(property="out_of_stock", type="integer", example=5)
     *                 ),
     *                 @OA\Property(
     *                     property="movement_trends",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="period", type="string", example="2024-01"),
     *                         @OA\Property(property="stock_movements", type="integer", example=150)
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="recent_activities",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="product_name", type="string", example="Tomates Bio"),
     *                         @OA\Property(property="action", type="string", example="stock_update"),
     *                         @OA\Property(property="quantity_change", type="integer", example=-5),
     *                         @OA\Property(property="timestamp", type="string", format="datetime")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès refusé",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     * 
     * Obtenir les statistiques globales de stock pour le dashboard
     */
    public function getStockDashboard(Request $request): JsonResponse
    {
        $stats = Product::getStockStatistics();
        
        // Produits nécessitant une action immédiate
        $urgentProducts = Product::where(function($query) {
            $query->outOfStock()->orWhere(function($q) {
                $q->criticalStock();
            });
        })->with('category')->get();

        // Évolution du stock sur les 7 derniers jours
        $stockEvolution = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $stockEvolution[] = [
                'date' => $date->format('Y-m-d'),
                'out_of_stock' => Product::outOfStock()->whereDate('updated_at', $date)->count(),
                'critical_stock' => Product::criticalStock()->whereDate('updated_at', $date)->count()
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Dashboard stock récupéré',
            'data' => [
                'statistics' => $stats,
                'urgent_products' => $urgentProducts,
                'stock_evolution' => $stockEvolution,
                'recommendations' => $this->getStockRecommendations($stats)
            ]
        ]);
    }

    /**
     * Générer des recommandations basées sur les statistiques de stock
     */
    private function getStockRecommendations(array $stats): array
    {
        $recommendations = [];

        if ($stats['out_of_stock'] > 0) {
            $recommendations[] = [
                'type' => 'urgent',
                'icon' => '🚨',
                'title' => 'Action immédiate requise',
                'message' => "{$stats['out_of_stock']} produit(s) en rupture de stock",
                'action' => 'Réapprovisionner immédiatement'
            ];
        }

        if ($stats['critical_stock'] > 0) {
            $recommendations[] = [
                'type' => 'warning',
                'icon' => '⚠️',
                'title' => 'Stock critique',
                'message' => "{$stats['critical_stock']} produit(s) avec stock critique",
                'action' => 'Programmer un réapprovisionnement'
            ];
        }

        if ($stats['low_stock'] > 0) {
            $recommendations[] = [
                'type' => 'info',
                'icon' => '📉',
                'title' => 'Stock faible',
                'message' => "{$stats['low_stock']} produit(s) avec stock faible",
                'action' => 'Surveiller et prévoir réapprovisionnement'
            ];
        }

        if (empty($recommendations)) {
            $recommendations[] = [
                'type' => 'success',
                'icon' => '✅',
                'title' => 'Stock sous contrôle',
                'message' => 'Tous les produits ont un stock suffisant',
                'action' => 'Maintenir la surveillance'
            ];
        }

        return $recommendations;
    }

    /**
     * Mettre à jour manuellement le stock d'un produit avec notification
     */
    public function updateStock(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'action' => 'required|in:set,increase,decrease',
            'quantity' => 'required|integer|min:0',
            'reason' => 'nullable|string|max:255'
        ]);

        $oldQuantity = $product->quantity;

        try {
            DB::beginTransaction();

            switch ($validated['action']) {
                case 'set':
                    $product->setStock($validated['quantity']);
                    break;
                case 'increase':
                    $product->increaseStock($validated['quantity']);
                    break;
                case 'decrease':
                    $success = $product->decreaseStock($validated['quantity']);
                    if (!$success) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Stock insuffisant pour cette opération'
                        ], 400);
                    }
                    break;
            }

            // Log de l'action pour l'audit
            $this->logStockChange($product, $oldQuantity, $product->fresh()->quantity, $validated['reason'] ?? 'Mise à jour manuelle admin');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stock mis à jour avec succès',
                'data' => [
                    'product' => $product->fresh(),
                    'old_quantity' => $oldQuantity,
                    'new_quantity' => $product->quantity,
                    'stock_status' => $product->stock_status
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du stock: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enregistrer les changements de stock pour l'audit
     */
    private function logStockChange(Product $product, int $oldQuantity, int $newQuantity, string $reason)
    {
        // On peut utiliser la table messages pour l'audit aussi
        Message::create([
            'user_id' => Auth::id(),
            'sender_id' => Auth::id(),
            'type' => 'stock_change_log',
            'subject' => "Modification stock - {$product->name}",
            'content' => "Stock modifié: {$oldQuantity} → {$newQuantity}\nRaison: {$reason}",
            'metadata' => [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_sku' => $product->sku,
                'old_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'difference' => $newQuantity - $oldQuantity,
                'reason' => $reason,
                'admin_id' => Auth::id()
            ],
            'status' => 'read', // Les logs sont automatiquement lus
            'priority' => 'low'
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

    /**
     * Vérifier la disponibilité d'un produit pour l'achat ou la location
     */
    public function checkAvailability(Product $product, Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|in:sale,rental',
            'quantity' => 'required|integer|min:1'
        ]);

        $availability = [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'available' => false,
            'reasons' => []
        ];

        // Vérifications communes
        if (!$product->is_active) {
            $availability['reasons'][] = 'Produit non disponible';
        }

        if ($product->is_out_of_stock) {
            $availability['reasons'][] = 'Produit en rupture de stock';
        }

        if ($product->quantity < $request->quantity) {
            $availability['reasons'][] = "Stock insuffisant. Disponible: {$product->quantity}";
        }

        // Vérifications spécifiques au type
        if ($request->type === 'sale') {
            if (!in_array($product->type, ['sale', 'both'])) {
                $availability['reasons'][] = 'Produit non disponible à la vente';
            }
        } elseif ($request->type === 'rental') {
            if (!in_array($product->type, ['rental', 'both'])) {
                $availability['reasons'][] = 'Produit non disponible à la location';
            }
        }

        // Le produit est disponible s'il n'y a aucune raison d'indisponibilité
        $availability['available'] = empty($availability['reasons']);

        return response()->json([
            'success' => true,
            'data' => $availability
        ]);
    }

    /**
     * Ajouter un produit au panier
     */
    public function addToCart(Request $request, Product $product): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Vous devez être connecté pour ajouter au panier'], 401);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->quantity
        ]);

        $user = auth()->user();
        $quantity = $request->quantity;

        // Vérifier la disponibilité du stock
        if ($product->quantity < $quantity) {
            return response()->json(['error' => 'Stock insuffisant'], 400);
        }

        // Vérifier si le produit est déjà dans le panier
        $cartItem = $user->cartItems()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($newQuantity > $product->quantity) {
                return response()->json(['error' => 'Quantité totale demandée supérieure au stock disponible'], 400);
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $user->cartItems()->create([
                'product_id' => $product->id,
                'quantity' => $quantity
            ]);
        }

        $cartCount = $user->cartItems()->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Produit ajouté au panier',
            'cart_count' => $cartCount
        ]);
    }
}
