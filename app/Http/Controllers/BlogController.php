<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    /**
     * Afficher la liste des articles de blog publiés
     */
    public function index(Request $request)
    {
        $query = Blog::published()->with(['author']);
        
        // Recherche par titre ou contenu
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%");
            });
        }
        
        // Filtrage par catégorie/tag si implémenté plus tard
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }
        
        // Tri
        $sortBy = $request->input('sort', 'created_at');
        $sortOrder = $request->input('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $blogs = $query->paginate(10);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $blogs,
                'message' => 'Articles récupérés avec succès'
            ]);
        }
        
        return view('blogs.index', compact('blogs'));
    }
    
    /**
     * Afficher un article de blog spécifique
     */
    public function show($slug)
    {
        $blog = Blog::published()
            ->where('slug', $slug)
            ->with(['author', 'comments' => function($query) {
                $query->approved()->with(['user'])->latest();
            }])
            ->firstOrFail();
        
        // Incrémenter le nombre de vues
        $blog->increment('views_count');
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $blog,
                'message' => 'Article récupéré avec succès'
            ]);
        }
        
        return view('blogs.show', compact('blog'));
    }
    
    /**
     * Rechercher dans les articles
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:3|max:255'
        ]);
        
        $query = $request->input('q');
        
        $blogs = Blog::published()
            ->where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%")
                  ->orWhere('excerpt', 'LIKE', "%{$query}%");
            })
            ->with(['author'])
            ->latest()
            ->paginate(10);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $blogs,
                'query' => $query,
                'message' => "Résultats pour: {$query}"
            ]);
        }
        
        return view('blogs.search', compact('blogs', 'query'));
    }
    
    /**
     * Récupérer les articles les plus populaires
     */
    public function popular(Request $request)
    {
        $blogs = Blog::published()
            ->with(['author'])
            ->orderBy('views_count', 'desc')
            ->take(10)
            ->get();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $blogs,
                'message' => 'Articles populaires récupérés'
            ]);
        }
        
        return view('blogs.popular', compact('blogs'));
    }
    
    /**
     * Récupérer les articles récents
     */
    public function recent(Request $request)
    {
        $blogs = Blog::published()
            ->with(['author'])
            ->latest()
            ->take(10)
            ->get();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $blogs,
                'message' => 'Articles récents récupérés'
            ]);
        }
        
        return view('blogs.recent', compact('blogs'));
    }
    
    /**
     * Récupérer les articles par auteur
     */
    public function byAuthor($authorId)
    {
        $blogs = Blog::published()
            ->where('author_id', $authorId)
            ->with(['author'])
            ->latest()
            ->paginate(10);
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $blogs,
                'message' => 'Articles de l\'auteur récupérés'
            ]);
        }
        
        return view('blogs.by-author', compact('blogs'));
    }
}
