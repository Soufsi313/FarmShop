<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminBlogController extends Controller
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
     * Afficher la liste des articles de blog
     */
    public function index(Request $request)
    {
        $this->checkAdminAccess();
        
        $query = BlogPost::with(['category', 'author']);

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        // Filtrage par catégorie
        if ($request->filled('category_id')) {
            $query->where('blog_category_id', $request->category_id);
        }

        // Filtrage par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtrage par auteur
        if ($request->filled('author_id')) {
            $query->where('author_id', $request->author_id);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $posts = $query->paginate(15);
        $categories = BlogCategory::orderBy('name')->get();
        $authors = User::whereHas('blogPosts')->orderBy('name')->get();

        return view('admin.blog.index', compact('posts', 'categories', 'authors'));
    }

    /**
     * Afficher le formulaire de création d'un article
     */
    public function create()
    {
        $this->checkAdminAccess();
        
        $categories = BlogCategory::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.blog.create', compact('categories'));
    }

    /**
     * Enregistrer un nouvel article
     */
    public function store(Request $request)
    {
        $this->checkAdminAccess();

        $validated = $request->validate([
            'blog_category_id' => 'required|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'status' => 'required|in:draft,published,scheduled',
            'scheduled_for' => 'nullable|date|after:now',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'allow_comments' => 'boolean',
            'is_featured' => 'boolean',
            'is_sticky' => 'boolean'
        ]);

        // Générer le slug si non fourni
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
            
            // Vérifier l'unicité du slug généré
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (BlogPost::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        // Gestion de l'upload d'image principale
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')
                ->store('blog/posts', 'public');
        }

        // Traitement des tags
        if (!empty($validated['tags'])) {
            $tags = array_map('trim', explode(',', $validated['tags']));
            $validated['tags'] = array_filter($tags);
        }

        // Définir l'auteur
        $validated['author_id'] = Auth::id();

        // Gestion des dates de publication
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $post = BlogPost::create($validated);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Article créé avec succès !');
    }

    /**
     * Afficher un article spécifique
     */
    public function show(BlogPost $blog)
    {
        $this->checkAdminAccess();
        
        $blog->load(['category', 'author', 'comments.user']);
        
        return view('admin.blog.show', compact('blog'));
    }

    /**
     * Afficher le formulaire d'édition d'un article
     */
    public function edit(BlogPost $blog)
    {
        $this->checkAdminAccess();
        
        $categories = BlogCategory::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.blog.edit', compact('blog', 'categories'));
    }

    /**
     * Mettre à jour un article
     */
    public function update(Request $request, BlogPost $blog)
    {
        $this->checkAdminAccess();

        $validated = $request->validate([
            'blog_category_id' => 'required|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug,' . $blog->id,
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'status' => 'required|in:draft,published,scheduled,archived',
            'scheduled_for' => 'nullable|date|after:now',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'allow_comments' => 'boolean',
            'is_featured' => 'boolean',
            'is_sticky' => 'boolean'
        ]);

        // Gestion de l'upload d'image principale
        if ($request->hasFile('featured_image')) {
            // Supprimer l'ancienne image
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            
            $validated['featured_image'] = $request->file('featured_image')
                ->store('blog/posts', 'public');
        }

        // Traitement des tags
        if (!empty($validated['tags'])) {
            $tags = array_map('trim', explode(',', $validated['tags']));
            $validated['tags'] = array_filter($tags);
        }

        // Gestion des dates de publication
        if ($validated['status'] === 'published' && $blog->status !== 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        // Marquer comme édité
        $validated['last_edited_by'] = Auth::id();
        $validated['is_edited'] = true;

        $blog->update($validated);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Article mis à jour avec succès !');
    }

    /**
     * Supprimer un article
     */
    public function destroy(BlogPost $blog)
    {
        $this->checkAdminAccess();

        // Supprimer l'image principale
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }

        // Supprimer la galerie
        if ($blog->gallery) {
            foreach ($blog->gallery as $imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        $blog->delete();

        return redirect()->route('admin.blog.index')
            ->with('success', 'Article supprimé avec succès !');
    }

    /**
     * Actions en lot
     */
    public function bulkAction(Request $request)
    {
        $this->checkAdminAccess();

        $validated = $request->validate([
            'action' => 'required|in:publish,unpublish,delete,featured,unfeatured',
            'posts' => 'required|array',
            'posts.*' => 'exists:blog_posts,id'
        ]);

        $posts = BlogPost::whereIn('id', $validated['posts']);

        switch ($validated['action']) {
            case 'publish':
                $posts->update([
                    'status' => 'published',
                    'published_at' => now()
                ]);
                $message = 'Articles publiés avec succès !';
                break;
                
            case 'unpublish':
                $posts->update(['status' => 'draft']);
                $message = 'Articles dépubliés avec succès !';
                break;
                
            case 'featured':
                $posts->update(['is_featured' => true]);
                $message = 'Articles mis en avant avec succès !';
                break;
                
            case 'unfeatured':
                $posts->update(['is_featured' => false]);
                $message = 'Articles retirés de la mise en avant avec succès !';
                break;
                
            case 'delete':
                $posts->each(function($post) {
                    if ($post->featured_image) {
                        Storage::disk('public')->delete($post->featured_image);
                    }
                    if ($post->gallery) {
                        foreach ($post->gallery as $imagePath) {
                            Storage::disk('public')->delete($imagePath);
                        }
                    }
                });
                $posts->delete();
                $message = 'Articles supprimés avec succès !';
                break;
        }

        return redirect()->route('admin.blog.index')->with('success', $message);
    }

    /**
     * Prévisualisation d'un article
     */
    public function preview(BlogPost $blog)
    {
        $this->checkAdminAccess();
        
        return redirect()->route('blog.show', $blog->slug);
    }
}
