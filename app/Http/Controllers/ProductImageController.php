<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductImageRequest;
use App\Http\Requests\UpdateProductImageRequest;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ProductImageController extends Controller
{
    /**
     * Display a listing of the product images.
     */
    public function index(Request $request)
    {
        $query = ProductImage::with('product')
            ->when($request->filled('product_id'), function ($q) use ($request) {
                return $q->where('product_id', $request->product_id);
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                return $q->whereHas('product', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%');
                })->orWhere('alt_text', 'like', '%' . $request->search . '%');
            })
            ->ordered();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $query->paginate(20),
                'message' => 'Images récupérées avec succès'
            ]);
        }

        $images = $query->paginate(20);
        $products = Product::select('id', 'name')->get();

        return view('admin.product-images.index', compact('images', 'products'));
    }

    /**
     * Show the form for creating new product images.
     */
    public function create(Request $request)
    {
        $products = Product::select('id', 'name')->get();
        $selectedProduct = $request->filled('product_id') 
            ? Product::find($request->product_id) 
            : null;

        return view('admin.product-images.create', compact('products', 'selectedProduct'));
    }

    /**
     * Store newly uploaded product images.
     */
    public function store(StoreProductImageRequest $request)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->product_id);
            $uploadedImages = [];

            // Obtenir le prochain ordre de tri
            $nextSortOrder = ProductImage::where('product_id', $product->id)
                ->max('sort_order') + 1 ?? 0;

            foreach ($request->file('images') as $index => $image) {
                // Génération d'un nom unique pour l'image
                $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                
                // Création du répertoire si nécessaire
                $directory = 'public/products/gallery';
                if (!Storage::exists($directory)) {
                    Storage::makeDirectory($directory);
                }

                // Redimensionnement et optimisation de l'image
                $processedImage = Image::make($image)
                    ->resize(1200, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->encode('jpg', 85);

                // Sauvegarde de l'image
                Storage::put($directory . '/' . $filename, $processedImage);

                // Création de l'enregistrement en base
                $productImage = ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $filename,
                    'alt_text' => $request->alt_texts[$index] ?? $product->name . ' - Image ' . ($index + 1),
                    'sort_order' => $nextSortOrder + $index,
                ]);

                $uploadedImages[] = $productImage;
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $uploadedImages,
                    'message' => count($uploadedImages) . ' image(s) uploadée(s) avec succès'
                ], 201);
            }

            return redirect()
                ->route('admin.product-images.index', ['product_id' => $product->id])
                ->with('success', count($uploadedImages) . ' image(s) uploadée(s) avec succès');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'upload: ' . $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'upload: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified product image.
     */
    public function show(ProductImage $productImage)
    {
        $productImage->load('product');

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $productImage,
                'message' => 'Image récupérée avec succès'
            ]);
        }

        return view('admin.product-images.show', compact('productImage'));
    }

    /**
     * Show the form for editing the specified product image.
     */
    public function edit(ProductImage $productImage)
    {
        $productImage->load('product');
        return view('admin.product-images.edit', compact('productImage'));
    }

    /**
     * Update the specified product image.
     */
    public function update(UpdateProductImageRequest $request, ProductImage $productImage)
    {
        try {
            $productImage->update($request->validated());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $productImage->fresh(),
                    'message' => 'Image mise à jour avec succès'
                ]);
            }

            return redirect()
                ->route('admin.product-images.show', $productImage)
                ->with('success', 'Image mise à jour avec succès');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified product image.
     */
    public function destroy(ProductImage $productImage)
    {
        try {
            // Suppression du fichier physique
            if (Storage::exists('public/products/gallery/' . $productImage->image_path)) {
                Storage::delete('public/products/gallery/' . $productImage->image_path);
            }

            $productId = $productImage->product_id;
            $productImage->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Image supprimée avec succès'
                ]);
            }

            return redirect()
                ->route('admin.product-images.index', ['product_id' => $productId])
                ->with('success', 'Image supprimée avec succès');

        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete product images.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:product_images,id'
        ]);

        try {
            $images = ProductImage::whereIn('id', $request->ids)->get();
            
            foreach ($images as $image) {
                // Suppression du fichier physique
                if (Storage::exists('public/products/gallery/' . $image->image_path)) {
                    Storage::delete('public/products/gallery/' . $image->image_path);
                }
            }

            $deletedCount = ProductImage::whereIn('id', $request->ids)->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $deletedCount . ' image(s) supprimée(s) avec succès'
                ]);
            }

            return back()->with('success', $deletedCount . ' image(s) supprimée(s) avec succès');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Reorder product images.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'images' => 'required|array|min:1',
            'images.*.id' => 'required|integer|exists:product_images,id',
            'images.*.sort_order' => 'required|integer|min:0'
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->images as $imageData) {
                ProductImage::where('id', $imageData['id'])
                    ->where('product_id', $request->product_id)
                    ->update(['sort_order' => $imageData['sort_order']]);
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ordre des images mis à jour avec succès'
                ]);
            }

            return back()->with('success', 'Ordre des images mis à jour avec succès');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la réorganisation: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la réorganisation: ' . $e->getMessage());
        }
    }

    /**
     * Set main image for product.
     */
    public function setMain(Request $request, ProductImage $productImage)
    {
        try {
            DB::beginTransaction();

            // Réinitialiser l'ordre de tri : mettre l'image sélectionnée en premier
            ProductImage::where('product_id', $productImage->product_id)
                ->where('id', '!=', $productImage->id)
                ->increment('sort_order');

            $productImage->update(['sort_order' => 0]);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Image principale définie avec succès'
                ]);
            }

            return back()->with('success', 'Image principale définie avec succès');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la définition de l\'image principale: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la définition de l\'image principale: ' . $e->getMessage());
        }
    }

    /**
     * Get statistics for product images.
     */
    public function statistics()
    {
        $stats = [
            'total_images' => ProductImage::count(),
            'products_with_images' => ProductImage::distinct('product_id')->count(),
            'products_without_images' => Product::whereDoesntHave('images')->count(),
            'average_images_per_product' => round(ProductImage::count() / max(Product::count(), 1), 2),
            'total_storage_size' => $this->calculateStorageSize(),
            'top_products_by_images' => ProductImage::select('product_id', DB::raw('count(*) as image_count'))
                ->with('product:id,name')
                ->groupBy('product_id')
                ->orderByDesc('image_count')
                ->limit(10)
                ->get()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Statistiques récupérées avec succès'
        ]);
    }

    /**
     * Calculate total storage size of product images.
     */
    private function calculateStorageSize()
    {
        $totalSize = 0;
        $images = ProductImage::all();
        
        foreach ($images as $image) {
            $filePath = 'public/products/gallery/' . $image->image_path;
            if (Storage::exists($filePath)) {
                $totalSize += Storage::size($filePath);
            }
        }

        return $this->formatBytes($totalSize);
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
