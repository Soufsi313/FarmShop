<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        // Calculer les statistiques globales
        $stats = [
            'total' => Category::count(),
            'active' => Category::where('is_active', true)->count(),
            'with_products' => Category::has('products')->count(),
            'inactive' => Category::where('is_active', false)->count(),
        ];
        
        $categories = Category::withCount('products')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.categories.index', compact('categories', 'stats'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'food_type' => 'required|in:alimentaire,non_alimentaire',
            'is_active' => 'boolean',
            'is_returnable' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:10',
            'display_order' => 'nullable|integer|min:0',
        ]);

        // Génération du slug
        $validated['slug'] = Str::slug($validated['name']);

        // Valeurs par défaut
        $validated['is_active'] = $request->has('is_active');
        $validated['is_returnable'] = $request->has('is_returnable');
        $validated['display_order'] = $validated['display_order'] ?? 0;

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    public function show(Category $category)
    {
        $category->load(['products' => function($query) {
            $query->orderBy('created_at', 'desc')->take(10);
        }]);
        
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'food_type' => 'required|in:alimentaire,non_alimentaire',
            'is_active' => 'boolean',
            'is_returnable' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:10',
            'display_order' => 'nullable|integer|min:0',
        ]);

        // Mise à jour du slug si le nom a changé
        if ($category->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Valeurs par défaut
        $validated['is_active'] = $request->has('is_active');
        $validated['is_returnable'] = $request->has('is_returnable');
        $validated['display_order'] = $validated['display_order'] ?? $category->display_order ?? 0;

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie mise à jour avec succès.');
    }

    public function destroy(Category $category)
    {
        // Vérifier s'il y a des produits associés
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Impossible de supprimer cette catégorie car elle contient des produits.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }
}
