<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\BlogCommentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BlogAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'permission:manage blogs']);
    }
    
    /**
     * Afficher la liste des articles (admin)
     */
    public function index(Request $request)
    {
        $query = Blog::with(['author']);
        
        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        
        if ($request->filled('author')) {
            $query->where('author_id', $request->input('author'));
        }
        
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%");
            });
        }
        
        // Tri
        $sortBy = $request->input('sort', 'created_at');
        $sortOrder = $request->input('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $blogs = $query->paginate(15);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $blogs,
                'message' => 'Articles récupérés avec succès'
            ]);
        }
        
        return view('admin.blogs.index', compact('blogs'));
    }
    
    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('admin.blogs.create');
    }
    
    /**
     * Stocker un nouvel article
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'status' => ['required', Rule::in(['draft', 'published'])],
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'tags' => 'nullable|string'
        ]);
        
        $validated['author_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['title']);
        
        // S'assurer que le slug est unique
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Blog::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        // Gestion de l'image
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blog-images', 'public');
        }
        
        // Date de publication
        if ($validated['status'] === 'published' && !isset($validated['published_at'])) {
            $validated['published_at'] = now();
        }
        
        $blog = Blog::create($validated);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $blog,
                'message' => 'Article créé avec succès'
            ], 201);
        }
        
        return redirect()->route('admin.blogs.index')->with('success', 'Article créé avec succès');
    }
    
    /**
     * Afficher un article spécifique (admin)
     */
    public function show(Blog $blog)
    {
        $blog->load(['author', 'comments.user', 'comments.reports']);
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $blog,
                'message' => 'Article récupéré avec succès'
            ]);
        }
        
        return view('admin.blogs.show', compact('blog'));
    }
    
    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }
    
    /**
     * Mettre à jour un article
     */
    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'status' => ['required', Rule::in(['draft', 'published'])],
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'tags' => 'nullable|string'
        ]);
        
        // Mise à jour du slug si le titre change
        if ($validated['title'] !== $blog->title) {
            $validated['slug'] = Str::slug($validated['title']);
            
            // S'assurer que le slug est unique
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Blog::where('slug', $validated['slug'])->where('id', '!=', $blog->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }
        
        // Gestion de l'image
        if ($request->hasFile('featured_image')) {
            // Supprimer l'ancienne image
            if ($blog->featured_image) {
                \Storage::disk('public')->delete($blog->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('blog-images', 'public');
        }
        
        // Date de publication
        if ($validated['status'] === 'published' && $blog->status !== 'published') {
            $validated['published_at'] = now();
        }
        
        $blog->update($validated);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $blog->fresh(),
                'message' => 'Article mis à jour avec succès'
            ]);
        }
        
        return redirect()->route('admin.blogs.index')->with('success', 'Article mis à jour avec succès');
    }
    
    /**
     * Supprimer un article
     */
    public function destroy(Request $request, Blog $blog)
    {
        // Supprimer l'image associée
        if ($blog->featured_image) {
            \Storage::disk('public')->delete($blog->featured_image);
        }
        
        $blog->delete();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Article supprimé avec succès'
            ]);
        }
        
        return redirect()->route('admin.blogs.index')->with('success', 'Article supprimé avec succès');
    }
    
    /**
     * Publier un article
     */
    public function publish(Request $request, Blog $blog)
    {
        $blog->update([
            'status' => 'published',
            'published_at' => $blog->published_at ?? now()
        ]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $blog->fresh(),
                'message' => 'Article publié avec succès'
            ]);
        }
        
        return back()->with('success', 'Article publié avec succès');
    }
    
    /**
     * Mettre en brouillon un article
     */
    public function unpublish(Request $request, Blog $blog)
    {
        $blog->update(['status' => 'draft']);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $blog->fresh(),
                'message' => 'Article mis en brouillon'
            ]);
        }
        
        return back()->with('success', 'Article mis en brouillon');
    }
    
    /**
     * Actions en lot
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:publish,unpublish,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:blogs,id'
        ]);
        
        $blogs = Blog::whereIn('id', $request->ids);
        $count = $blogs->count();
        
        switch ($request->action) {
            case 'publish':
                $blogs->update([
                    'status' => 'published',
                    'published_at' => now()
                ]);
                $message = "{$count} article(s) publié(s)";
                break;
                
            case 'unpublish':
                $blogs->update(['status' => 'draft']);
                $message = "{$count} article(s) mis en brouillon";
                break;
                
            case 'delete':
                // Supprimer les images associées
                $blogsToDelete = $blogs->get();
                foreach ($blogsToDelete as $blog) {
                    if ($blog->featured_image) {
                        \Storage::disk('public')->delete($blog->featured_image);
                    }
                }
                $blogs->delete();
                $message = "{$count} article(s) supprimé(s)";
                break;
        }
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }
        
        return back()->with('success', $message);
    }
    
    /**
     * Statistiques des blogs
     */
    public function statistics()
    {
        $stats = [
            'total_blogs' => Blog::count(),
            'published_blogs' => Blog::where('status', 'published')->count(),
            'draft_blogs' => Blog::where('status', 'draft')->count(),
            'total_comments' => BlogComment::count(),
            'pending_comments' => BlogComment::where('status', 'pending')->count(),
            'total_reports' => BlogCommentReport::count(),
            'pending_reports' => BlogCommentReport::where('status', 'pending')->count(),
            'most_viewed' => Blog::orderBy('views_count', 'desc')->take(5)->get(['title', 'views_count']),
            'most_commented' => Blog::withCount('comments')->orderBy('comments_count', 'desc')->take(5)->get(['title', 'comments_count'])
        ];
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Statistiques récupérées'
            ]);
        }
        
        return view('admin.blogs.statistics', compact('stats'));
    }
}
