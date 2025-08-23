<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Calculer les statistiques globales avant la pagination
        $stats = [
            'total' => Product::count(),
            'available' => Product::where('is_active', true)->count(),
            'low_stock' => Product::whereColumn('quantity', '<=', 'low_stock_threshold')->count(),
            'categories' => Category::count(),
        ];
        
        $query = Product::with(['category', 'rentalCategory']);

        // Filtrage par recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Filtrage par catégorie
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filtrage par type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtrage par statut
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', true);
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
                case 'featured':
                    $query->where('is_featured', true);
                    break;
                case 'low_stock':
                    $query->whereColumn('quantity', '<=', 'low_stock_threshold');
                    break;
                case 'out_of_stock':
                    $query->whereColumn('quantity', '<=', 'out_of_stock_threshold');
                    break;
            }
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15);

        // Récupérer toutes les catégories pour les filtres
        $categories = Category::orderBy('name')->get();

        // Conserver les valeurs des filtres pour l'affichage
        $filters = [
            'search' => $request->get('search', ''),
            'category' => $request->get('category', ''),
            'type' => $request->get('type', ''),
            'status' => $request->get('status', ''),
        ];

        // Si c'est une requête AJAX, retourner seulement le contenu du tableau
        if ($request->ajax()) {
            return view('admin.products._table', compact('products'))->render();
        }

        return view('admin.products.index', compact('products', 'categories', 'stats', 'filters'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'rental_price_per_day' => 'nullable|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'min_rental_days' => 'nullable|integer|min:1',
            'max_rental_days' => 'nullable|integer|min:1',
            'type' => 'required|in:sale,rental,both',
            'quantity' => 'required|integer|min:0',
            'rental_stock' => 'nullable|integer|min:0',
            'critical_threshold' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'unit_symbol' => 'required|in:kg,pièce,litre,gramme,tonne',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:255',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_alt' => 'nullable|string|max:255',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        // Génération du SKU si non fourni
        if (empty($validated['sku'])) {
            $validated['sku'] = $this->generateSku($validated['name']);
        }

        // Génération du slug unique
        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        
        // Vérifier si le slug existe déjà
        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        $validated['slug'] = $slug;

        // Upload de l'image principale
        if ($request->hasFile('main_image')) {
            $validated['main_image'] = $request->file('main_image')->store('products', 'public');
        }

        // Upload des images de galerie
        $galleryImages = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $galleryImages[] = $image->store('products/gallery', 'public');
            }
        }
        $validated['gallery_images'] = $galleryImages;

        // Upload des images supplémentaires
        $additionalImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $additionalImages[] = $image->store('products/additional', 'public');
            }
        }
        $validated['images'] = $additionalImages;

        // Valeurs par défaut
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        $product = Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit créé avec succès.');
    }

    public function show(Product $product)
    {
        $product->load('category');
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'rental_price_per_day' => 'nullable|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'min_rental_days' => 'nullable|integer|min:1',
            'max_rental_days' => 'nullable|integer|min:1',
            'type' => 'required|in:sale,rental,both',
            'quantity' => 'required|integer|min:0',
            'rental_stock' => 'nullable|integer|min:0',
            'critical_threshold' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'unit_symbol' => 'required|in:kg,pièce,litre,gramme,tonne',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:255',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'remove_gallery_images' => 'nullable|array',
            'remove_gallery_images.*' => 'nullable|string',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        // Upload de la nouvelle image principale si fournie
        if ($request->hasFile('main_image')) {
            // Supprimer l'ancienne image
            if ($product->main_image) {
                Storage::disk('public')->delete($product->main_image);
            }
            $validated['main_image'] = $request->file('main_image')->store('products', 'public');
        }

        // Gestion des images de galerie
        $currentGalleryImages = $product->gallery_images ?? [];
        
        // Supprimer les images sélectionnées pour suppression
        if ($request->has('remove_gallery_images')) {
            foreach ($request->remove_gallery_images as $imagePath) {
                // Chercher l'image dans le tableau
                $index = array_search($imagePath, $currentGalleryImages);
                
                if ($index !== false) {
                    // Vérifier si le fichier existe avant de le supprimer
                    if (Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                    }
                    unset($currentGalleryImages[$index]);
                }
            }
            $currentGalleryImages = array_values($currentGalleryImages); // Réindexer
        }

        // Ajouter de nouvelles images de galerie
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $currentGalleryImages[] = $image->store('products/gallery', 'public');
            }
        }
        
        $validated['gallery_images'] = $currentGalleryImages;

        // Gestion des images supplémentaires
        $currentImages = $product->images ?? [];
        
        // Supprimer les images supplémentaires sélectionnées pour suppression
        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $imagePath) {
                // Chercher l'image dans le tableau
                $index = array_search($imagePath, $currentImages);
                if ($index !== false) {
                    Storage::disk('public')->delete($imagePath);
                    unset($currentImages[$index]);
                }
            }
            $currentImages = array_values($currentImages); // Réindexer
        }

        // Ajouter de nouvelles images supplémentaires
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $currentImages[] = $image->store('products/additional', 'public');
            }
        }
        
        $validated['images'] = $currentImages;

        // Mise à jour du slug - toujours générer un slug unique
        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        
        // Vérifier si le slug existe déjà (en excluant le produit actuel)
        while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        $validated['slug'] = $slug;
        
        // Log pour debug
        \Log::info("Updating product {$product->id}: name='{$validated['name']}', baseSlug='{$baseSlug}', finalSlug='{$slug}'");

        // Valeurs par défaut
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    public function destroy($productId)
    {
        try {
            // Chercher le produit manuellement pour éviter les problèmes de model binding
            $product = Product::find($productId);
            
            if (!$product) {
                // Si c'est une requête AJAX, retourner une erreur JSON
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Produit non trouvé.'
                    ], 404);
                }
                
                return redirect()->route('admin.products.index')
                    ->with('error', 'Produit non trouvé.');
            }

            // Supprimer les images associées
            if ($product->main_image) {
                Storage::disk('public')->delete($product->main_image);
            }

            if ($product->gallery_images) {
                foreach ($product->gallery_images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }

            if ($product->images) {
                foreach ($product->images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }

            $product->delete();

            // Si c'est une requête AJAX, retourner JSON
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produit supprimé avec succès.'
                ]);
            }

            // Sinon, redirection normale
            return redirect()->route('admin.products.index')
                ->with('success', 'Produit supprimé avec succès.');
                
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression du produit ID ' . $productId . ': ' . $e->getMessage());
            
            // Si c'est une requête AJAX, retourner une erreur JSON
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du produit: ' . $e->getMessage()
                ], 500);
            }

            // Sinon, redirection avec erreur
            return redirect()->route('admin.products.index')
                ->with('error', 'Erreur lors de la suppression du produit.');
        }
    }

    /**
     * Générer un SKU unique
     */
    private function generateSku($name)
    {
        $base = strtoupper(Str::slug($name, ''));
        $base = substr($base, 0, 6);
        
        $counter = 1;
        $sku = $base . sprintf('%03d', $counter);
        
        while (Product::where('sku', $sku)->exists()) {
            $counter++;
            $sku = $base . sprintf('%03d', $counter);
        }
        
        return $sku;
    }
}
