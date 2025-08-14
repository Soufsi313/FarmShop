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
     * @OA\Get(
     *     path="/api/blog/categories",
     *     tags={"Blog", "Categories"},
     *     summary="Liste des catégories de blog",
     *     description="Récupère la liste des catégories de blog avec possibilité de filtrage et tri",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Terme de recherche dans le nom ou slug",
     *         @OA\Schema(type="string", example="jardinage")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filtrer par statut (admin uniquement)",
     *         @OA\Schema(type="string", enum={"active", "inactive"}, example="active")
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         description="Critère de tri",
     *         @OA\Schema(type="string", enum={"sort_order", "name", "posts_count", "created_at"}, example="sort_order")
     *     ),
     *     @OA\Parameter(
     *         name="sort_direction",
     *         in="query",
     *         description="Direction du tri",
     *         @OA\Schema(type="string", enum={"asc", "desc"}, example="asc")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Nombre d'éléments par page",
     *         @OA\Schema(type="integer", minimum=1, maximum=100, example=20)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des catégories récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", ref="#/components/schemas/PaginatedResponse"),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="total_categories", type="integer", example=15),
     *                 @OA\Property(property="active_categories", type="integer", example=12),
     *                 @OA\Property(property="inactive_categories", type="integer", example=3)
     *             )
     *         )
     *     )
     * )
     * 
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
     * @OA\Get(
     *     path="/api/blog/categories/{slug}",
     *     tags={"Blog", "Categories"},
     *     summary="Détails d'une catégorie de blog",
     *     description="Récupère les détails d'une catégorie avec ses articles",
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         description="Slug de la catégorie",
     *         required=true,
     *         @OA\Schema(type="string", example="jardinage-bio")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Catégorie récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", ref="#/components/schemas/BlogCategory"),
     *             @OA\Property(property="posts", ref="#/components/schemas/PaginatedResponse")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Catégorie non trouvée",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     * 
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
     * @OA\Post(
     *     path="/api/admin/blog/categories",
     *     tags={"Admin", "Blog", "Categories"},
     *     summary="Créer une catégorie de blog",
     *     description="Crée une nouvelle catégorie de blog (Admin uniquement)",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données de la catégorie à créer",
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", maxLength=255, example="Jardinage Bio", description="Nom de la catégorie"),
     *             @OA\Property(property="description", type="string", maxLength=1000, example="Tout sur le jardinage biologique", description="Description de la catégorie"),
     *             @OA\Property(property="color", type="string", pattern="^#[0-9A-Fa-f]{6}$", example="#4CAF50", description="Couleur de la catégorie (format hexadécimal)"),
     *             @OA\Property(property="icon", type="string", maxLength=255, example="fa-leaf", description="Icône de la catégorie"),
     *             @OA\Property(property="image", type="string", format="binary", description="Image de la catégorie (JPEG, PNG, JPG, WebP, max 2MB)"),
     *             @OA\Property(property="meta_title", type="string", maxLength=60, example="Jardinage Bio - FarmShop", description="Titre SEO"),
     *             @OA\Property(property="meta_description", type="string", maxLength=160, example="Découvrez nos conseils...", description="Description SEO"),
     *             @OA\Property(property="is_active", type="boolean", example=true, description="Catégorie active"),
     *             @OA\Property(property="sort_order", type="integer", example=1, description="Ordre d'affichage")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Catégorie créée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Catégorie créée avec succès"),
     *             @OA\Property(property="data", ref="#/components/schemas/BlogCategory")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreurs de validation",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès non autorisé",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     * 
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
     * @OA\Put(
     *     path="/api/admin/blog/categories/{blogCategory}",
     *     tags={"Admin", "Blog", "Categories"},
     *     summary="Modifier une catégorie de blog",
     *     description="Met à jour une catégorie de blog existante (Admin uniquement)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="blogCategory",
     *         in="path",
     *         description="ID de la catégorie à modifier",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données de la catégorie à modifier",
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", maxLength=255, example="Jardinage Bio Avancé", description="Nom de la catégorie"),
     *             @OA\Property(property="description", type="string", maxLength=1000, example="Description mise à jour", description="Description de la catégorie"),
     *             @OA\Property(property="color", type="string", pattern="^#[0-9A-Fa-f]{6}$", example="#2E7D32", description="Couleur de la catégorie"),
     *             @OA\Property(property="icon", type="string", maxLength=255, example="fa-seedling", description="Icône de la catégorie"),
     *             @OA\Property(property="image", type="string", format="binary", description="Nouvelle image de la catégorie"),
     *             @OA\Property(property="meta_title", type="string", maxLength=60, example="Jardinage Bio Avancé", description="Titre SEO"),
     *             @OA\Property(property="meta_description", type="string", maxLength=160, example="Description SEO mise à jour", description="Description SEO"),
     *             @OA\Property(property="is_active", type="boolean", example=true, description="Catégorie active"),
     *             @OA\Property(property="sort_order", type="integer", example=2, description="Ordre d'affichage")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Catégorie modifiée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Catégorie modifiée avec succès"),
     *             @OA\Property(property="data", ref="#/components/schemas/BlogCategory")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Catégorie non trouvée",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreurs de validation",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     * 
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
     * @OA\Delete(
     *     path="/api/admin/blog/categories/{blogCategory}",
     *     tags={"Admin", "Blog", "Categories"},
     *     summary="Supprimer une catégorie de blog",
     *     description="Supprime une catégorie de blog si elle ne contient pas d'articles (Admin uniquement)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="blogCategory",
     *         in="path",
     *         description="ID de la catégorie à supprimer",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Catégorie supprimée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Catégorie supprimée avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Catégorie non trouvée",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Impossible de supprimer (catégorie contient des articles)",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Impossible de supprimer une catégorie contenant des articles")
     *         )
     *     )
     * )
     * 
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
     * @OA\Patch(
     *     path="/api/admin/blog/categories/{blogCategory}/toggle-status",
     *     tags={"Admin", "Blog", "Categories"},
     *     summary="Activer/Désactiver une catégorie",
     *     description="Change le statut actif/inactif d'une catégorie de blog (Admin uniquement)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="blogCategory",
     *         in="path",
     *         description="ID de la catégorie",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Statut modifié avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Statut de la catégorie modifié avec succès"),
     *             @OA\Property(property="data", ref="#/components/schemas/BlogCategory")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Catégorie non trouvée",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     * 
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
     * @OA\Get(
     *     path="/api/admin/blog/categories/statistics",
     *     tags={"Admin", "Blog", "Categories"},
     *     summary="Statistiques des catégories de blog",
     *     description="Récupère les statistiques complètes des catégories de blog (Admin uniquement)",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Statistiques récupérées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="total_categories", type="integer", example=15, description="Total des catégories"),
     *                 @OA\Property(property="active_categories", type="integer", example=12, description="Catégories actives"),
     *                 @OA\Property(property="inactive_categories", type="integer", example=3, description="Catégories inactives"),
     *                 @OA\Property(property="categories_with_posts", type="integer", example=10, description="Catégories avec articles"),
     *                 @OA\Property(property="most_popular", ref="#/components/schemas/BlogCategory", description="Catégorie la plus populaire"),
     *                 @OA\Property(property="recent_categories", type="array", @OA\Items(ref="#/components/schemas/BlogCategory"), description="Catégories récentes"),
     *                 @OA\Property(property="posts_by_category", type="array", @OA\Items(type="object", @OA\Property(property="category", type="string"), @OA\Property(property="posts_count", type="integer")), description="Articles par catégorie")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès non autorisé",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     * 
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
     * @OA\Post(
     *     path="/api/admin/blog/categories/update-order",
     *     tags={"Admin", "Blog", "Categories"},
     *     summary="Mettre à jour l'ordre des catégories",
     *     description="Met à jour l'ordre d'affichage des catégories de blog (Admin uniquement)",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Nouvel ordre des catégories",
     *         @OA\JsonContent(
     *             required={"categories"},
     *             @OA\Property(
     *                 property="categories",
     *                 type="array",
     *                 description="Liste des catégories avec leur nouvel ordre",
     *                 @OA\Items(
     *                     type="object",
     *                     required={"id", "sort_order"},
     *                     @OA\Property(property="id", type="integer", example=1, description="ID de la catégorie"),
     *                     @OA\Property(property="sort_order", type="integer", minimum=0, example=1, description="Nouvel ordre d'affichage")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ordre mis à jour avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Ordre des catégories mis à jour avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreurs de validation",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès non autorisé",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     * 
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
