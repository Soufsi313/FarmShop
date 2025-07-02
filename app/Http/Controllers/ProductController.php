<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct()
    {
        // Pas de middleware auth pour les méthodes publiques
        $this->middleware('permission:manage products')->except(['index', 'show', 'search', 'like', 'wishlist']);
        $this->middleware('auth')->only(['like', 'wishlist']);
    }

    /**
     * Display a listing of products (public + admin).
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images', 'specialOffers'])
                       ->active();

        // Recherche
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filtrage par catégorie
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Filtrage par prix
        if ($request->filled('price_min') || $request->filled('price_max')) {
            $query->priceRange($request->price_min, $request->price_max);
        }

        // Filtrage par type de produit
        if ($request->filled('product_type')) {
            switch ($request->product_type) {
                case 'purchase':
                    $query->where('price', '>', 0);
                    break;
                case 'rental':
                    $query->where('is_rentable', true);
                    break;
            }
        }

        // Tri
        $sortBy = $request->get('sort', 'name');
        $sortDirection = $request->get('direction', 'asc');

        switch ($sortBy) {
            case 'price':
                $query->sortByPrice($sortDirection);
                break;
            case 'popularity':
                $query->popular();
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'name':
            default:
                $query->orderBy('name', $sortDirection);
                break;
        }

        // Filtres de stock (admin uniquement)
        if (auth()->check() && auth()->user()->can('manage products')) {
            if ($request->filled('stock_status')) {
                switch ($request->stock_status) {
                    case 'in_stock':
                        $query->inStock();
                        break;
                    case 'low_stock':
                        $query->lowStock();
                        break;
                    case 'out_of_stock':
                        $query->outOfStock();
                        break;
                }
            }
        }

        $products = $query->paginate(12);
        $categories = Category::active()->get();

        if ($request->expectsJson()) {
            return response()->json([
                'products' => $products,
                'categories' => $categories
            ]);
        }

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('manage products');
        
        $categories = Category::active()->get();
        $units = Product::getUnits();

        return view('admin.products.create', compact('categories', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        DB::beginTransaction();
        
        try {
            $productData = $request->validated();
            
            // Upload de l'image principale
            if ($request->hasFile('main_image')) {
                $productData['main_image'] = $request->file('main_image')
                    ->store('products', 'public');
            }

            // Créer le produit
            $product = Product::create($productData);

            // Traitement de la galerie d'images
            if ($request->hasFile('gallery_images')) {
                $this->handleGalleryImages($product, $request->file('gallery_images'));
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Produit créé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du produit: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        // Vérifier si le produit est actif (sauf pour les admins)
        if (!$product->is_active && (!auth()->user() || !auth()->user()->can('manage products'))) {
            abort(404);
        }

        $product->load(['category', 'images', 'specialOffers']);
        
        // Incrémenter le compteur de vues
        $product->incrementViews();

        // Produits similaires
        $relatedProducts = Product::with('specialOffers')
            ->active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inStock()
            ->limit(4)
            ->get();

        // Variables pour utilisateur connecté
        $isLiked = false;
        $isInWishlist = false;
        
        if (auth()->check()) {
            $isLiked = $product->isLikedBy(auth()->user());
            $isInWishlist = $product->isInWishlistOf(auth()->user());
        }

        return view('products.show', compact(
            'product', 
            'relatedProducts', 
            'isLiked', 
            'isInWishlist'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $this->authorize('manage products');
        
        $categories = Category::active()->get();
        $units = Product::getUnits();

        return view('admin.products.edit', compact('product', 'categories', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        DB::beginTransaction();
        
        try {
            $productData = $request->validated();
            
            // Upload de la nouvelle image principale si fournie
            if ($request->hasFile('main_image')) {
                // Supprimer l'ancienne image
                if ($product->main_image) {
                    Storage::disk('public')->delete($product->main_image);
                }
                
                $productData['main_image'] = $request->file('main_image')
                    ->store('products', 'public');
            }

            // Mettre à jour le produit
            $product->update($productData);

            // Supprimer les images de galerie sélectionnées
            if ($request->filled('remove_gallery_images')) {
                $imagesToRemove = $product->images()
                    ->whereIn('id', $request->remove_gallery_images)
                    ->get();
                
                foreach ($imagesToRemove as $image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }

            // Ajouter de nouvelles images de galerie
            if ($request->hasFile('gallery_images')) {
                $this->handleGalleryImages($product, $request->file('gallery_images'));
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Produit mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour du produit: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->authorize('manage products');

        try {
            // Soft delete du produit
            $product->delete();

            return redirect()->route('admin.products.index')
                ->with('success', 'Produit supprimé avec succès.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression du produit: ' . $e->getMessage());
        }
    }

    /**
     * API principale: Gestion de stock automatisée (admin only).
     */
    public function updateStock(Request $request, Product $product)
    {
        $this->authorize('manage products');

        $validator = Validator::make($request->all(), [
            'action' => 'required|in:add,remove,set',
            'quantity' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 400);
        }

        $oldQuantity = $product->quantity;
        $quantity = $request->quantity;

        try {
            switch ($request->action) {
                case 'add':
                    $product->increaseStock($quantity);
                    $message = "Stock augmenté de {$quantity} unités";
                    break;
                
                case 'remove':
                    if (!$product->decreaseStock($quantity)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Stock insuffisant pour cette opération'
                        ], 400);
                    }
                    $message = "Stock réduit de {$quantity} unités";
                    break;
                
                case 'set':
                    $product->updateStock($quantity);
                    $message = "Stock défini à {$quantity} unités";
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'old_quantity' => $oldQuantity,
                    'new_quantity' => $product->fresh()->quantity,
                    'stock_status' => $product->fresh()->status
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du stock: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle like sur un produit (utilisateurs connectés).
     */
    public function like(Request $request, Product $product)
    {
        $user = auth()->user();
        $liked = $product->toggleLike($user);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes_count' => $product->fresh()->likes_count,
                'message' => $liked ? 'Produit ajouté aux favoris' : 'Produit retiré des favoris'
            ]);
        }

        return redirect()->back()
            ->with('success', $liked ? 'Produit ajouté aux favoris' : 'Produit retiré des favoris');
    }

    /**
     * Toggle wishlist sur un produit (utilisateurs connectés).
     */
    public function wishlist(Request $request, Product $product)
    {
        $user = auth()->user();
        $wishlisted = $product->toggleWishlist($user);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'wishlisted' => $wishlisted,
                'message' => $wishlisted ? 'Produit ajouté à la liste de souhaits' : 'Produit retiré de la liste de souhaits'
            ]);
        }

        return redirect()->back()
            ->with('success', $wishlisted ? 'Produit ajouté à la liste de souhaits' : 'Produit retiré de la liste de souhaits');
    }

    /**
     * Recherche avancée de produits.
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'q' => 'required|string|min:2',
            'category' => 'nullable|exists:categories,id',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0',
            'sort' => 'nullable|in:name,price,popularity,newest',
            'direction' => 'nullable|in:asc,desc'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $query = Product::with(['category', 'images'])
                       ->active()
                       ->search($request->q);

        // Filtres additionnels
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('price_min') || $request->filled('price_max')) {
            $query->priceRange($request->price_min, $request->price_max);
        }

        // Tri
        $sortBy = $request->get('sort', 'name');
        $sortDirection = $request->get('direction', 'asc');

        switch ($sortBy) {
            case 'price':
                $query->sortByPrice($sortDirection);
                break;
            case 'popularity':
                $query->popular();
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'name':
            default:
                $query->orderBy('name', $sortDirection);
                break;
        }

        $products = $query->paginate(12);

        return response()->json([
            'success' => true,
            'products' => $products,
            'total' => $products->total()
        ]);
    }

    /**
     * Obtenir les statistiques des produits (admin only).
     */
    public function statistics()
    {
        $this->authorize('manage products');

        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::active()->count(),
            'inactive_products' => Product::where('is_active', false)->count(),
            'featured_products' => Product::featured()->count(),
            'in_stock' => Product::inStock()->count(),
            'low_stock' => Product::lowStock()->count(),
            'out_of_stock' => Product::outOfStock()->count(),
            'total_value' => Product::active()->sum(DB::raw('price * quantity')),
            'categories_count' => Category::count(),
            'recent_products' => Product::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Actions en lot sur les produits (admin only).
     */
    public function bulkAction(Request $request)
    {
        $this->authorize('manage products');

        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,feature,unfeature,delete',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $productIds = $request->product_ids;
        $action = $request->action;

        try {
            switch ($action) {
                case 'activate':
                    Product::whereIn('id', $productIds)->update(['is_active' => true]);
                    $message = 'Produits activés avec succès.';
                    break;

                case 'deactivate':
                    Product::whereIn('id', $productIds)->update(['is_active' => false]);
                    $message = 'Produits désactivés avec succès.';
                    break;

                case 'feature':
                    Product::whereIn('id', $productIds)->update(['is_featured' => true]);
                    $message = 'Produits mis en avant avec succès.';
                    break;

                case 'unfeature':
                    Product::whereIn('id', $productIds)->update(['is_featured' => false]);
                    $message = 'Produits retirés de la mise en avant avec succès.';
                    break;

                case 'delete':
                    Product::whereIn('id', $productIds)->delete();
                    $message = 'Produits supprimés avec succès.';
                    break;
            }

            return redirect()->route('admin.products.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'action en lot: ' . $e->getMessage());
        }
    }

    /**
     * Gérer les images de galerie.
     */
    private function handleGalleryImages(Product $product, array $images)
    {
        $sortOrder = $product->images()->max('sort_order') ?? 0;

        foreach ($images as $image) {
            $imagePath = $image->store('products/gallery', 'public');
            
            $product->images()->create([
                'image_path' => $imagePath,
                'alt_text' => $product->name . ' - Image',
                'sort_order' => ++$sortOrder
            ]);
        }
    }
}
