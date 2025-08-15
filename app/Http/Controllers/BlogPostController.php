<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    public function __construct()
    {
        // Pas de middleware d'authentification pour les méthodes publiques
        // $this->middleware('auth:sanctum');
        // $this->middleware('admin')->except(['index', 'show']);
    }

    /**
     * @OA\Get(
     *     path="/api/blog/posts",
     *     tags={"Blog", "Posts"},
     *     summary="Liste des articles de blog",
     *     description="Récupère la liste des articles de blog publiés avec filtres et pagination",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Recherche dans le titre et contenu",
     *         required=false,
     *         @OA\Schema(type="string", example="jardinage")
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Slug de la catégorie",
     *         required=false,
     *         @OA\Schema(type="string", example="jardinage-bio")
     *     ),
     *     @OA\Parameter(
     *         name="author",
     *         in="query",
     *         description="ID de l'auteur",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="tag",
     *         in="query",
     *         description="Tag pour filtrer",
     *         required=false,
     *         @OA\Schema(type="string", example="bio")
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Tri des résultats",
     *         required=false,
     *         @OA\Schema(type="string", enum={"latest", "oldest", "popular", "trending"}, example="latest")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Nombre d'articles par page",
     *         required=false,
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des articles récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Articles récupérés avec succès"),
     *             @OA\Property(property="data", ref="#/components/schemas/PaginatedResponse")
     *         )
     *     )
     * )
     * 
     * Afficher la liste des articles de blog
     */
    public function index(Request $request)
    {
        // Détection et application de la langue
        if ($request->has('lang')) {
            $locale = $request->get('lang');
            if (in_array($locale, ['fr', 'en', 'nl'])) {
                app()->setLocale($locale);
            }
        }

        $query = BlogPost::with(['category', 'author'])
            ->withCount(['approvedComments as comments_count']);

        // Filtrage par statut pour les utilisateurs non-admin
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            $query->where('status', 'published');
        }

        // Recherche
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filtrage par catégorie
        if ($request->filled('category')) {
            $category = BlogCategory::where('slug', $request->category)->first();
            if ($category) {
                $query->byCategory($category->id);
            }
        }

        // Filtrage par auteur
        if ($request->filled('author')) {
            $query->byAuthor($request->author);
        }

        // Filtrage par statut (admin seulement)
        if ($request->filled('status') && Auth::check() && Auth::user()->isAdmin()) {
            switch ($request->status) {
                case 'published':
                    $query->where('status', 'published');
                    break;
                case 'draft':
                    $query->where('status', 'draft');
                    break;
                case 'scheduled':
                    $query->where('status', 'scheduled');
                    break;
            }
        }

        // Filtrage par tag
        if ($request->filled('tag')) {
            $query->withTag($request->tag);
        }

        // Filtrage par featured
        if ($request->filled('featured') && $request->featured === 'true') {
            $query->featured();
        }

        // Tri
        $sortBy = $request->get('sort_by', 'recent');
        switch ($sortBy) {
            case 'popular':
                $query->popular();
                break;
            case 'views':
                $query->orderBy('views_count', 'desc');
                break;
            case 'comments':
                $query->orderBy('comments_count', 'desc');
                break;
            default:
                $query->recent();
        }

        $posts = $query->paginate(12);

        // Si c'est une requête web, retourner une vue
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $posts,
                'meta' => [
                    'total_posts' => BlogPost::count(),
                    'published_posts' => BlogPost::where('status', 'published')->count(),
                    'draft_posts' => BlogPost::where('status', 'draft')->count(),
                ]
            ]);
        }

        // Pour les requêtes web, retourner la vue
        $categories = BlogCategory::where('is_active', true)->orderBy('name')->get();
        
        return view('blog.index', compact('posts', 'categories'));
    }

    /**
     * @OA\Get(
     *     path="/api/blog/posts/{slug}",
     *     tags={"Blog", "Posts"},
     *     summary="Détails d'un article de blog",
     *     description="Récupère les détails complets d'un article de blog par son slug",
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         description="Slug de l'article",
     *         required=true,
     *         @OA\Schema(type="string", example="guide-jardinage-bio")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article récupéré avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", ref="#/components/schemas/BlogPost"),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="related_posts", type="array", @OA\Items(ref="#/components/schemas/BlogPost")),
     *                 @OA\Property(property="previous_post", type="object", nullable=true),
     *                 @OA\Property(property="next_post", type="object", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article non trouvé",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     )
     * )
     * 
     * Afficher un article spécifique
     */
    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->with(['category', 'author', 'lastEditor'])
            ->withCount(['approvedComments as comments_count'])
            ->firstOrFail();

        // Vérifier si l'article est publié pour les non-admin
        if ((!Auth::check() || !Auth::user()->isAdmin()) && $post->status !== 'published') {
            abort(404);
        }

        // Incrémenter le compteur de vues
        $post->increment('views_count');

        // Charger les commentaires approuvés
        $comments = $post->comments()
            ->where('status', 'approved')
            ->whereNull('parent_id')
            ->with(['user', 'replies' => function($query) {
                $query->where('status', 'approved')->with('user');
            }])
            ->latest()
            ->paginate(10);

        // Articles similaires
        $relatedPosts = BlogPost::where('status', 'published')
            ->where('id', '!=', $post->id)
            ->where('blog_category_id', $post->blog_category_id)
            ->latest('published_at')
            ->take(4)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'post' => $post,
                'comments' => $comments,
                'related_posts' => $relatedPosts
            ]
        ]);
    }

    /**
     * Afficher un article spécifique pour le web
     */
    public function showWeb(Request $request, $slug)
    {
        // Détection de la langue
        if ($request->has('lang') && in_array($request->get('lang'), ['fr', 'en', 'nl'])) {
            app()->setLocale($request->get('lang'));
        }
        
        $currentLang = app()->getLocale();
        
        // Rechercher l'article par slug selon la langue
        $post = null;
        
        switch ($currentLang) {
            case 'en':
                $post = BlogPost::where('slug_en', $slug)
                    ->orWhere('slug', $slug)
                    ->with(['category', 'author', 'lastEditor'])
                    ->first();
                break;
            case 'nl':
                $post = BlogPost::where('slug_nl', $slug)
                    ->orWhere('slug', $slug)
                    ->with(['category', 'author', 'lastEditor'])
                    ->first();
                break;
            default:
                $post = BlogPost::where('slug', $slug)
                    ->with(['category', 'author', 'lastEditor'])
                    ->first();
                break;
        }
        
        if (!$post) {
            abort(404);
        }

        // Vérifier si l'article est publié pour les non-admin
        if ((!Auth::check() || !Auth::user()->isAdmin()) && $post->status !== 'published') {
            abort(404);
        }

        // Incrémenter le compteur de vues
        $post->increment('views_count');

        // Charger les commentaires approuvés
        $comments = $post->comments()
            ->where('status', 'approved')
            ->whereNull('parent_id')
            ->with(['user', 'replies' => function($query) {
                $query->where('status', 'approved')->with('user');
            }])
            ->latest()
            ->paginate(10);

        // Articles similaires
        $relatedPosts = BlogPost::where('status', 'published')
            ->where('id', '!=', $post->id)
            ->where('blog_category_id', $post->blog_category_id)
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('blog.show', compact('post', 'comments', 'relatedPosts'));
    }

    /**
     * @OA\Post(
     *     path="/api/admin/blog/posts",
     *     tags={"Admin", "Blog", "Posts"},
     *     summary="Créer un article de blog",
     *     description="Crée un nouvel article de blog avec toutes ses propriétés",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"blog_category_id", "title", "content", "status"},
     *             @OA\Property(property="blog_category_id", type="integer", example=1, description="ID de la catégorie"),
     *             @OA\Property(property="title", type="string", maxLength=255, example="Guide du jardinage bio", description="Titre de l'article"),
     *             @OA\Property(property="slug", type="string", maxLength=255, example="guide-jardinage-bio", description="Slug URL (généré automatiquement si absent)"),
     *             @OA\Property(property="excerpt", type="string", maxLength=500, example="Découvrez les secrets...", description="Extrait de l'article"),
     *             @OA\Property(property="content", type="string", example="Contenu complet de l'article...", description="Contenu principal"),
     *             @OA\Property(property="featured_image", type="string", format="binary", description="Image principale"),
     *             @OA\Property(property="gallery", type="array", @OA\Items(type="string", format="binary"), description="Images de galerie"),
     *             @OA\Property(property="status", type="string", enum={"draft", "published", "scheduled"}, example="draft", description="Statut de publication"),
     *             @OA\Property(property="scheduled_for", type="string", format="date-time", example="2024-12-25 10:00:00", description="Date de publication programmée"),
     *             @OA\Property(property="meta_title", type="string", maxLength=255, example="Guide complet du jardinage bio", description="Titre SEO"),
     *             @OA\Property(property="meta_description", type="string", maxLength=500, example="Apprenez les techniques...", description="Description SEO"),
     *             @OA\Property(property="meta_keywords", type="string", maxLength=255, example="jardinage, bio, écologie", description="Mots-clés SEO"),
     *             @OA\Property(property="tags", type="array", @OA\Items(type="string"), example={"bio", "jardinage", "écologie"}, description="Tags de l'article"),
     *             @OA\Property(property="allow_comments", type="boolean", example=true, description="Autoriser les commentaires"),
     *             @OA\Property(property="is_featured", type="boolean", example=false, description="Article mis en avant")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Article créé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Article créé avec succès"),
     *             @OA\Property(property="data", ref="#/components/schemas/BlogPost")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès non autorisé",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Données de validation invalides",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     * 
     * Créer un nouvel article (Admin seulement)
     */
    public function store(Request $request)
    {
        // Vérification des permissions
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Accès non autorisé'
            ], 403);
        }

        $validated = $request->validate([
            'blog_category_id' => 'required|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            'status' => 'required|in:draft,published,scheduled',
            'scheduled_for' => 'nullable|date|after:now',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'allow_comments' => 'boolean',
            'is_featured' => 'boolean',
            'is_sticky' => 'boolean'
        ]);

        // Générer le slug si non fourni
        if (!$validated['slug']) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Gestion de l'upload d'image principale
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')
                ->store('blog/posts', 'public');
        }

        // Gestion de la galerie
        if ($request->hasFile('gallery')) {
            $galleryPaths = [];
            foreach ($request->file('gallery') as $image) {
                $galleryPaths[] = $image->store('blog/posts/gallery', 'public');
            }
            $validated['gallery'] = $galleryPaths;
        }

        // Validation pour les articles programmés
        if ($validated['status'] === 'scheduled' && !$validated['scheduled_for']) {
            return response()->json([
                'status' => 'error',
                'message' => 'Une date de publication est requise pour les articles programmés'
            ], 422);
        }

        $post = BlogPost::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Article créé avec succès',
            'data' => $post->load(['category', 'author'])
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/admin/blog/posts/{blogPost}",
     *     tags={"Admin", "Blog", "Posts"},
     *     summary="Modifier un article de blog",
     *     description="Met à jour un article de blog existant (Admin uniquement)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="blogPost",
     *         in="path",
     *         description="ID de l'article à modifier",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données de l'article à modifier",
     *         @OA\JsonContent(
     *             required={"blog_category_id", "title", "content"},
     *             @OA\Property(property="blog_category_id", type="integer", example=1, description="ID de la catégorie"),
     *             @OA\Property(property="title", type="string", maxLength=255, example="Guide jardinage bio mis à jour", description="Titre de l'article"),
     *             @OA\Property(property="slug", type="string", maxLength=255, example="guide-jardinage-bio-nouveau", description="Slug personnalisé (optionnel)"),
     *             @OA\Property(property="excerpt", type="string", maxLength=500, example="Description courte mise à jour", description="Extrait de l'article"),
     *             @OA\Property(property="content", type="string", example="Contenu de l'article mis à jour...", description="Contenu complet en HTML"),
     *             @OA\Property(property="featured_image", type="string", format="binary", description="Image à la une (JPEG, PNG, JPG, WebP, max 5MB)"),
     *             @OA\Property(property="gallery", type="array", @OA\Items(type="string", format="binary"), description="Galerie d'images"),
     *             @OA\Property(property="tags", type="array", @OA\Items(type="string"), example={"jardinage", "bio", "écologie"}, description="Tags de l'article"),
     *             @OA\Property(property="meta_title", type="string", maxLength=60, example="Guide jardinage bio - FarmShop", description="Titre SEO"),
     *             @OA\Property(property="meta_description", type="string", maxLength=160, example="Découvrez notre guide complet...", description="Description SEO"),
     *             @OA\Property(property="is_featured", type="boolean", example=true, description="Article en vedette"),
     *             @OA\Property(property="status", type="string", enum={"draft", "published", "scheduled"}, example="published", description="Statut de publication"),
     *             @OA\Property(property="published_at", type="string", format="date-time", nullable=true, example="2024-12-19T10:00:00Z", description="Date de publication (pour scheduled)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article modifié avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Article modifié avec succès"),
     *             @OA\Property(property="data", ref="#/components/schemas/BlogPost")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès non autorisé",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article non trouvé",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreurs de validation",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     * 
     * Mettre à jour un article (Admin seulement)
     */
    public function update(Request $request, BlogPost $blogPost)
    {
        // Vérification des permissions
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Accès non autorisé'
            ], 403);
        }

        $validated = $request->validate([
            'blog_category_id' => 'required|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug,' . $blogPost->id,
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            'status' => 'required|in:draft,published,scheduled,archived',
            'scheduled_for' => 'nullable|date|after:now',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'allow_comments' => 'boolean',
            'is_featured' => 'boolean',
            'is_sticky' => 'boolean'
        ]);

        // Gestion de l'upload d'image principale
        if ($request->hasFile('featured_image')) {
            // Supprimer l'ancienne image
            if ($blogPost->featured_image) {
                Storage::disk('public')->delete($blogPost->featured_image);
            }
            
            $validated['featured_image'] = $request->file('featured_image')
                ->store('blog/posts', 'public');
        }

        // Gestion de la galerie
        if ($request->hasFile('gallery')) {
            // Supprimer l'ancienne galerie
            if ($blogPost->gallery) {
                foreach ($blogPost->gallery as $imagePath) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
            
            $galleryPaths = [];
            foreach ($request->file('gallery') as $image) {
                $galleryPaths[] = $image->store('blog/posts/gallery', 'public');
            }
            $validated['gallery'] = $galleryPaths;
        }

        $blogPost->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Article mis à jour avec succès',
            'data' => $blogPost->fresh(['category', 'author'])
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/admin/blog/posts/{blogPost}",
     *     tags={"Admin", "Blog", "Posts"},
     *     summary="Supprimer un article de blog",
     *     description="Supprime définitivement un article de blog (Admin uniquement)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="blogPost",
     *         in="path",
     *         description="ID de l'article à supprimer",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article supprimé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Article supprimé avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès non autorisé",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article non trouvé",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     * 
     * Supprimer un article (Admin seulement)
     */
    public function destroy(BlogPost $blogPost)
    {
        // Vérification des permissions
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Accès non autorisé'
            ], 403);
        }

        // Supprimer les images
        if ($blogPost->featured_image) {
            Storage::disk('public')->delete($blogPost->featured_image);
        }

        if ($blogPost->gallery) {
            foreach ($blogPost->gallery as $imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        $blogPost->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Article supprimé avec succès'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/blog/posts/{blogPost}/publish",
     *     tags={"Admin", "Blog", "Posts"},
     *     summary="Publier un article de blog",
     *     description="Publie un article de blog (passe le statut à 'published') (Admin uniquement)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="blogPost",
     *         in="path",
     *         description="ID de l'article à publier",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article publié avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Article publié avec succès"),
     *             @OA\Property(property="data", ref="#/components/schemas/BlogPost")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès non autorisé",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article non trouvé",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     * 
     * Publier un article (Admin seulement)
     */
    public function publish(BlogPost $blogPost)
    {
        $blogPost->publish();

        return response()->json([
            'status' => 'success',
            'message' => 'Article publié avec succès',
            'data' => $blogPost->fresh()
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/blog/posts/{blogPost}/unpublish",
     *     tags={"Admin", "Blog", "Posts"},
     *     summary="Dépublier un article de blog",
     *     description="Dépublie un article de blog (passe le statut à 'draft') (Admin uniquement)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="blogPost",
     *         in="path",
     *         description="ID de l'article à dépublier",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article dépublié avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Article dépublié avec succès"),
     *             @OA\Property(property="data", ref="#/components/schemas/BlogPost")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès non autorisé",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article non trouvé",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     * 
     * Dépublier un article (Admin seulement)
     */
    public function unpublish(BlogPost $blogPost)
    {
        $blogPost->unpublish();

        return response()->json([
            'status' => 'success',
            'message' => 'Article dépublié avec succès',
            'data' => $blogPost->fresh()
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/blog/posts/{blogPost}/schedule",
     *     tags={"Admin", "Blog", "Posts"},
     *     summary="Programmer la publication d'un article",
     *     description="Programme la publication automatique d'un article à une date donnée (Admin uniquement)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="blogPost",
     *         in="path",
     *         description="ID de l'article à programmer",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Date de publication programmée",
     *         @OA\JsonContent(
     *             required={"published_at"},
     *             @OA\Property(property="published_at", type="string", format="date-time", example="2024-12-25T10:00:00Z", description="Date et heure de publication (ISO 8601)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article programmé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Article programmé pour publication le 25/12/2024 à 10:00"),
     *             @OA\Property(property="data", ref="#/components/schemas/BlogPost")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès non autorisé",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreurs de validation",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     * 
     * Programmer un article (Admin seulement)
     */
    public function schedule(Request $request, BlogPost $blogPost)
    {
        $validated = $request->validate([
            'scheduled_for' => 'required|date|after:now'
        ]);

        $blogPost->schedule($validated['scheduled_for']);

        return response()->json([
            'status' => 'success',
            'message' => 'Article programmé avec succès',
            'data' => $blogPost->fresh()
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/admin/blog/posts/statistics",
     *     tags={"Admin", "Blog", "Posts"},
     *     summary="Statistiques des articles de blog",
     *     description="Récupère les statistiques complètes des articles de blog (Admin uniquement)",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Statistiques récupérées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="total_posts", type="integer", example=125, description="Total des articles"),
     *                 @OA\Property(property="published_posts", type="integer", example=98, description="Articles publiés"),
     *                 @OA\Property(property="draft_posts", type="integer", example=20, description="Articles en brouillon"),
     *                 @OA\Property(property="scheduled_posts", type="integer", example=7, description="Articles programmés"),
     *                 @OA\Property(property="total_views", type="integer", example=25847, description="Total des vues"),
     *                 @OA\Property(property="total_comments", type="integer", example=1239, description="Total des commentaires"),
     *                 @OA\Property(property="most_viewed", ref="#/components/schemas/BlogPost", description="Article le plus vu"),
     *                 @OA\Property(property="most_commented", ref="#/components/schemas/BlogPost", description="Article le plus commenté"),
     *                 @OA\Property(property="recent_posts", type="array", @OA\Items(ref="#/components/schemas/BlogPost"), description="Articles récents"),
     *                 @OA\Property(property="popular_tags", type="array", @OA\Items(type="object", @OA\Property(property="tag", type="string"), @OA\Property(property="count", type="integer")), description="Tags populaires"),
     *                 @OA\Property(property="posts_by_category", type="array", @OA\Items(type="object", @OA\Property(property="category", type="string"), @OA\Property(property="count", type="integer")), description="Articles par catégorie"),
     *                 @OA\Property(property="monthly_growth", type="object", @OA\Property(property="current_month", type="integer"), @OA\Property(property="previous_month", type="integer"), @OA\Property(property="growth_rate", type="number", format="float"), description="Croissance mensuelle")
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
     * Statistiques des articles (Admin seulement)
     */
    public function statistics()
    {
        $stats = [
            'total_posts' => BlogPost::count(),
            'published_posts' => BlogPost::published()->count(),
            'draft_posts' => BlogPost::draft()->count(),
            'scheduled_posts' => BlogPost::scheduled()->count(),
            'total_views' => BlogPost::sum('views_count'),
            'total_comments' => BlogPost::sum('comments_count'),
            'most_viewed' => BlogPost::orderBy('views_count', 'desc')->first(),
            'most_commented' => BlogPost::orderBy('comments_count', 'desc')->first(),
            'recent_posts' => BlogPost::with('category')->latest()->take(5)->get(),
            'popular_tags' => BlogPost::whereNotNull('tags')
                ->get()
                ->pluck('tags')
                ->flatten()
                ->countBy()
                ->sortDesc()
                ->take(10)
        ];

        return response()->json([
            'status' => 'success',
            'data' => $stats
        ]);
    }

    /**
     * Obtenir les articles par tag
     */
    public function byTag($tag)
    {
        $posts = BlogPost::published()
            ->withTag($tag)
            ->with(['category', 'author'])
            ->latest('published_at')
            ->paginate(12);

        return response()->json([
            'status' => 'success',
            'data' => $posts,
            'meta' => [
                'tag' => $tag,
                'total_posts' => $posts->total()
            ]
        ]);
    }
}
