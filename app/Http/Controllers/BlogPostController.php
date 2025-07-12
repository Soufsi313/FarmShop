<?php

namespace App\Http\Controllers;

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
        $this->middleware('auth:sanctum');
        $this->middleware('admin')->except(['index', 'show']);
    }

    /**
     * Afficher la liste des articles de blog
     */
    public function index(Request $request)
    {
        $query = BlogPost::with(['category', 'author']);

        // Filtrage par statut pour les utilisateurs non-admin
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            $query->published();
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
        if ($request->filled('status') && Auth::user()->role === 'admin') {
            switch ($request->status) {
                case 'published':
                    $query->published();
                    break;
                case 'draft':
                    $query->draft();
                    break;
                case 'scheduled':
                    $query->scheduled();
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

        return response()->json([
            'status' => 'success',
            'data' => $posts,
            'meta' => [
                'total_posts' => BlogPost::count(),
                'published_posts' => BlogPost::published()->count(),
                'draft_posts' => BlogPost::draft()->count(),
            ]
        ]);
    }

    /**
     * Afficher un article spécifique
     */
    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->with(['category', 'author', 'lastEditor'])
            ->firstOrFail();

        // Vérifier si l'article est publié pour les non-admin
        if ((!Auth::user() || Auth::user()->role !== 'admin') && !$post->is_published) {
            abort(404);
        }

        // Incrémenter le compteur de vues
        $post->incrementViewsCount();

        // Charger les commentaires approuvés
        $comments = $post->topLevelComments()
            ->approved()
            ->with(['user', 'approvedReplies.user'])
            ->latest()
            ->paginate(10);

        // Articles similaires
        $relatedPosts = BlogPost::published()
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
     * Créer un nouvel article (Admin seulement)
     */
    public function store(Request $request)
    {
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
     * Mettre à jour un article (Admin seulement)
     */
    public function update(Request $request, BlogPost $blogPost)
    {
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
     * Supprimer un article (Admin seulement)
     */
    public function destroy(BlogPost $blogPost)
    {
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
