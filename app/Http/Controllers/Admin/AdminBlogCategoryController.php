<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminBlogCategoryController extends Controller
{
    /**
     * Vérifier que l'utilisateur est admin avant chaque action
     */
    private function checkAdminAccess()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès refusé. Seuls les administrateurs peuvent accéder à cette section.');
        }
    }

    /**
     * Afficher la liste des catégories de blog
     */
    public function index(Request $request)
    {
        $this->checkAdminAccess();
        
        $query = BlogCategory::withCount('posts');

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtrage par statut
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        $categories = $query->paginate(15);

        return view('admin.blog.categories.index', compact('categories'));
    }

    /**
     * Afficher le formulaire de création d'une catégorie
     */
    public function create()
    {
        $this->checkAdminAccess();
        
        return view('admin.blog.categories.create');
    }

    /**
     * Enregistrer une nouvelle catégorie
     */
    public function store(Request $request)
    {
        $this->checkAdminAccess();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_categories,slug',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'color' => 'nullable|string|max:7',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        // Générer le slug si non fourni
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
            
            // Vérifier l'unicité du slug généré
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (BlogCategory::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        // Gestion de l'upload d'image
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('blog/categories', 'public');
        }

        // Définir l'ordre de tri si non spécifié
        if (empty($validated['sort_order'])) {
            $validated['sort_order'] = BlogCategory::max('sort_order') + 1;
        }

        BlogCategory::create($validated);

        return redirect()->route('admin.blog.categories.index')
            ->with('success', 'Catégorie créée avec succès !');
    }

    /**
     * Afficher une catégorie spécifique
     */
    public function show(BlogCategory $category)
    {
        $this->checkAdminAccess();
        
        $category->loadCount('posts');
        $recentPosts = $category->posts()->latest()->take(10)->get();
        
        return view('admin.blog.categories.show', compact('category', 'recentPosts'));
    }

    /**
     * Afficher le formulaire d'édition d'une catégorie
     */
    public function edit(BlogCategory $category)
    {
        $this->checkAdminAccess();
        
        return view('admin.blog.categories.edit', compact('category'));
    }

    /**
     * Mettre à jour une catégorie
     */
    public function update(Request $request, BlogCategory $category)
    {
        $this->checkAdminAccess();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_categories,slug,' . $category->id,
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'color' => 'nullable|string|max:7',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        // Gestion de l'upload d'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            
            $validated['image'] = $request->file('image')
                ->store('blog/categories', 'public');
        }

        $category->update($validated);

        return redirect()->route('admin.blog.categories.index')
            ->with('success', 'Catégorie mise à jour avec succès !');
    }

    /**
     * Supprimer une catégorie
     */
    public function destroy(BlogCategory $category)
    {
        $this->checkAdminAccess();

        // Vérifier s'il y a des articles dans cette catégorie
        if ($category->posts()->count() > 0) {
            return redirect()->route('admin.blog.categories.index')
                ->with('error', 'Impossible de supprimer cette catégorie car elle contient des articles.');
        }

        // Supprimer l'image
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.blog.categories.index')
            ->with('success', 'Catégorie supprimée avec succès !');
    }

    /**
     * Actions en lot
     */
    public function bulkAction(Request $request)
    {
        $this->checkAdminAccess();

        $validated = $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'categories' => 'required|array',
            'categories.*' => 'exists:blog_categories,id'
        ]);

        $categories = BlogCategory::whereIn('id', $validated['categories']);

        switch ($validated['action']) {
            case 'activate':
                $categories->update(['is_active' => true]);
                $message = 'Catégories activées avec succès !';
                break;
                
            case 'deactivate':
                $categories->update(['is_active' => false]);
                $message = 'Catégories désactivées avec succès !';
                break;
                
            case 'delete':
                // Vérifier s'il y a des articles dans ces catégories
                $categoriesWithPosts = $categories->withCount('posts')->get()
                    ->filter(function($category) {
                        return $category->posts_count > 0;
                    });

                if ($categoriesWithPosts->count() > 0) {
                    return redirect()->route('admin.blog.categories.index')
                        ->with('error', 'Impossible de supprimer certaines catégories car elles contiennent des articles.');
                }

                $categories->each(function($category) {
                    if ($category->image) {
                        Storage::disk('public')->delete($category->image);
                    }
                });
                $categories->delete();
                $message = 'Catégories supprimées avec succès !';
                break;
        }

        return redirect()->route('admin.blog.categories.index')->with('success', $message);
    }

    /**
     * Réorganiser les catégories
     */
    public function reorder(Request $request)
    {
        $this->checkAdminAccess();

        $validated = $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:blog_categories,id',
            'categories.*.sort_order' => 'required|integer|min:0'
        ]);

        foreach ($validated['categories'] as $categoryData) {
            BlogCategory::where('id', $categoryData['id'])
                ->update(['sort_order' => $categoryData['sort_order']]);
        }

        return response()->json(['success' => true]);
    }
}
