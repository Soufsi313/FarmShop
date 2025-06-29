<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Category::withCount('products');

        // Filtres
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'sort_order');
        $sortDirection = $request->get('sort_direction', 'asc');
        
        if (in_array($sortBy, ['name', 'sort_order', 'created_at', 'products_count'])) {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            $query->ordered();
        }

        $categories = $query->paginate(10);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        }

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('manage categories');
        
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $data = $request->validated();

            // Gérer l'upload de l'image
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('categories', 'public');
                $data['image'] = basename($imagePath);
            }

            $category = Category::create($data);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Catégorie créée avec succès.',
                    'data' => $category->load('products')
                ], 201);
            }

            return redirect()->route('admin.categories.index')
                ->with('success', 'Catégorie créée avec succès.');

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création de la catégorie: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création de la catégorie.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la création de la catégorie.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load(['products' => function($query) {
            $query->active()->with(['images', 'category']);
        }]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $category
            ]);
        }

        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $this->authorize('manage categories');
        
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            $data = $request->validated();

            // Gérer l'upload de la nouvelle image
            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image si elle existe
                if ($category->image) {
                    Storage::disk('public')->delete('categories/' . $category->image);
                }

                $imagePath = $request->file('image')->store('categories', 'public');
                $data['image'] = basename($imagePath);
            }

            $category->update($data);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Catégorie mise à jour avec succès.',
                    'data' => $category->refresh()->load('products')
                ]);
            }

            return redirect()->route('admin.categories.index')
                ->with('success', 'Catégorie mise à jour avec succès.');

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour de la catégorie: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour de la catégorie.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour de la catégorie.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $this->authorize('manage categories');

        try {
            // Vérifier s'il y a des produits associés
            if ($category->products()->count() > 0) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Impossible de supprimer cette catégorie car elle contient des produits.'
                    ], 422);
                }

                return redirect()->back()
                    ->with('error', 'Impossible de supprimer cette catégorie car elle contient des produits.');
            }

            // Supprimer l'image associée
            if ($category->image) {
                Storage::disk('public')->delete('categories/' . $category->image);
            }

            $category->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Catégorie supprimée avec succès.'
                ]);
            }

            return redirect()->route('admin.categories.index')
                ->with('success', 'Catégorie supprimée avec succès.');

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression de la catégorie: ' . $e->getMessage());

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression de la catégorie.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de la catégorie.');
        }
    }

    /**
     * Actions en lot pour les catégories.
     */
    public function bulkAction(Request $request)
    {
        $this->authorize('manage categories');

        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $categories = Category::whereIn('id', $request->category_ids)->get();
        $action = $request->action;
        $successCount = 0;

        foreach ($categories as $category) {
            try {
                switch ($action) {
                    case 'activate':
                        $category->update(['is_active' => true]);
                        $successCount++;
                        break;
                    case 'deactivate':
                        $category->update(['is_active' => false]);
                        $successCount++;
                        break;
                    case 'delete':
                        if ($category->products()->count() === 0) {
                            if ($category->image) {
                                Storage::disk('public')->delete('categories/' . $category->image);
                            }
                            $category->delete();
                            $successCount++;
                        }
                        break;
                }
            } catch (\Exception $e) {
                \Log::error("Erreur lors de l'action sur la catégorie {$category->id}: " . $e->getMessage());
            }
        }

        $message = "Action effectuée sur {$successCount} catégorie(s).";

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Réorganiser les catégories (drag & drop).
     */
    public function reorder(Request $request)
    {
        $this->authorize('manage categories');

        $validator = Validator::make($request->all(), [
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:categories,id',
            'categories.*.sort_order' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            foreach ($request->categories as $categoryData) {
                Category::where('id', $categoryData['id'])
                    ->update(['sort_order' => $categoryData['sort_order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Ordre des catégories mis à jour avec succès.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la réorganisation des catégories: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la réorganisation des catégories.'
            ], 500);
        }
    }

    /**
     * Obtenir les statistiques des catégories.
     */
    public function statistics()
    {
        $this->authorize('manage categories');

        $stats = [
            'total_categories' => Category::count(),
            'active_categories' => Category::active()->count(),
            'inactive_categories' => Category::where('is_active', false)->count(),
            'categories_with_products' => Category::has('products')->count(),
            'empty_categories' => Category::doesntHave('products')->count(),
            'total_products_in_categories' => Category::withCount('products')->get()->sum('products_count'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get categories for purchase products only.
     */
    public function purchaseCategories(Request $request)
    {
        $this->authorize('manage categories');

        $query = Category::forPurchase()->active()->ordered();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $query->get(),
                'message' => 'Catégories d\'achat récupérées avec succès'
            ]);
        }

        $categories = $query->paginate(20);
        return view('admin.categories.purchase', compact('categories'));
    }

    /**
     * Get categories for rental products only.
     */
    public function rentalCategories(Request $request)
    {
        $this->authorize('manage categories');

        $query = Category::forRental()->active()->ordered();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $query->get(),
                'message' => 'Catégories de location récupérées avec succès'
            ]);
        }

        $categories = $query->paginate(20);
        return view('admin.categories.rental', compact('categories'));
    }

    /**
     * Get enhanced statistics with type breakdown.
     */
    public function enhancedStatistics()
    {
        $this->authorize('manage categories');

        $stats = [
            'total_categories' => Category::count(),
            'active_categories' => Category::active()->count(),
            'inactive_categories' => Category::where('is_active', false)->count(),
            'categories_with_products' => Category::has('products')->count(),
            'empty_categories' => Category::doesntHave('products')->count(),
            'total_products_in_categories' => Category::withCount('products')->get()->sum('products_count'),
            
            // Statistiques par type
            'purchase_only_categories' => Category::byType(Category::TYPE_PURCHASE)->count(),
            'rental_only_categories' => Category::byType(Category::TYPE_RENTAL)->count(),
            'both_categories' => Category::byType(Category::TYPE_BOTH)->count(),
            
            // Distribution des produits par type de catégorie
            'products_in_purchase_categories' => Category::forPurchase()->withCount('products')->get()->sum('products_count'),
            'products_in_rental_categories' => Category::forRental()->withCount('products')->get()->sum('products_count'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Statistiques détaillées récupérées avec succès'
        ]);
    }

    /**
     * Get available category types for forms.
     */
    public function getAvailableTypes()
    {
        return response()->json([
            'success' => true,
            'data' => Category::getAvailableTypes(),
            'message' => 'Types de catégories disponibles'
        ]);
    }
}
