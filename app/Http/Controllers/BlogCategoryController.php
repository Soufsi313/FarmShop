<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('admin')->except(['index', 'show']);
    }

    /**
     * Afficher la liste des catégories de blog
     */
    public function index(Request $request)
    {
        $query = BlogCategory::query();

        // Filtrage par statut pour les utilisateurs non-admin
        if (!Auth::user() || !in_array(Auth::user()->role, ['admin', 'Admin'])) {
            $query->active();
        }

        // Recherche
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filtrage par statut (admin seulement)
        if ($request->filled('status') && in_array(Auth::user()->role, ['admin', 'Admin'])) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Tri
        $sortBy = $request->get('sort_by', 'sort_order');
        $sortDirection = $request->get('sort_direction', 'asc');

        if ($sortBy === 'posts_count') {
            $query->withPostsCount()->orderBy('posts_count', $sortDirection);
        } elseif ($sortBy === 'popularity') {
            $query->popular();
        } else {
            $query->ordered();
        }

        $categories = $query->withPostsCount()->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => $categories,
            'meta' => [
                'total_categories' => BlogCategory::count(),
                'active_categories' => BlogCategory::active()->count(),
            ]
        ]);
    }

    /**
     * Afficher une catégorie spécifique
     */
    public function show($slug)
    {
        $category = BlogCategory::where('slug', $slug)
            ->withPostsCount()
            ->firstOrFail();

        // Vérifier si la catégorie est active pour les non-admin
        if ((!Auth::user() || !in_array(Auth::user()->role, ['admin', 'Admin'])) && !$category->is_active) {
            abort(404);
        }

        // Incrémenter le compteur de vues
        $category->incrementViewsCount();

        // Charger les articles publiés de cette catégorie
        $posts = $category->publishedPosts()
            ->with(['author', 'category'])
            ->latest('published_at')
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => [
                'category' => $category,
                'posts' => $posts
            ]
        ]);
    }

    /**
     * Créer une nouvelle catégorie (Admin seulement)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:blog_categories,name',
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        // Gestion de l'upload d'image
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')
                ->store('blog/categories', 'public');
        }

        $category = BlogCategory::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Catégorie créée avec succès',
            'data' => $category
        ], 201);
    }

    /**
     * Mettre à jour une catégorie (Admin seulement)
     */
    public function update(Request $request, BlogCategory $blogCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:blog_categories,name,' . $blogCategory->id,
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        // Gestion de l'upload d'image
        if ($request->hasFile('featured_image')) {
            // Supprimer l'ancienne image
            if ($blogCategory->featured_image) {
                Storage::disk('public')->delete($blogCategory->featured_image);
            }
            
            $validated['featured_image'] = $request->file('featured_image')
                ->store('blog/categories', 'public');
        }

        $blogCategory->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Catégorie mise à jour avec succès',
            'data' => $blogCategory->fresh()
        ]);
    }

    /**
     * Supprimer une catégorie (Admin seulement)
     */
    public function destroy(BlogCategory $blogCategory)
    {
        // Vérifier si la catégorie peut être supprimée
        if (!$blogCategory->canBeDeleted()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Impossible de supprimer une catégorie contenant des articles'
            ], 422);
        }

        // Supprimer l'image
        if ($blogCategory->featured_image) {
            Storage::disk('public')->delete($blogCategory->featured_image);
        }

        $blogCategory->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Catégorie supprimée avec succès'
        ]);
    }

    /**
     * Activer/Désactiver une catégorie (Admin seulement)
     */
    public function toggleStatus(BlogCategory $blogCategory)
    {
        $blogCategory->update([
            'is_active' => !$blogCategory->is_active
        ]);

        $status = $blogCategory->is_active ? 'activée' : 'désactivée';

        return response()->json([
            'status' => 'success',
            'message' => "Catégorie {$status} avec succès",
            'data' => $blogCategory
        ]);
    }

    /**
     * Statistiques des catégories (Admin seulement)
     */
    public function statistics()
    {
        $stats = [
            'total_categories' => BlogCategory::count(),
            'active_categories' => BlogCategory::active()->count(),
            'inactive_categories' => BlogCategory::where('is_active', false)->count(),
            'categories_with_posts' => BlogCategory::has('posts')->count(),
            'most_popular' => BlogCategory::popular()->first(),
            'recent_categories' => BlogCategory::latest()->take(5)->get(),
            'categories_by_posts' => BlogCategory::withCount('posts')
                ->orderBy('posts_count', 'desc')
                ->take(10)
                ->get()
        ];

        return response()->json([
            'status' => 'success',
            'data' => $stats
        ]);
    }

    /**
     * Mettre à jour l'ordre des catégories (Admin seulement)
     */
    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:blog_categories,id',
            'categories.*.sort_order' => 'required|integer|min:0'
        ]);

        foreach ($validated['categories'] as $categoryData) {
            BlogCategory::where('id', $categoryData['id'])
                ->update(['sort_order' => $categoryData['sort_order']]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Ordre des catégories mis à jour avec succès'
        ]);
    }
}
