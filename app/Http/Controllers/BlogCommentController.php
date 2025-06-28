<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }
    
    /**
     * Afficher les commentaires d'un article
     */
    public function index(Blog $blog, Request $request)
    {
        $query = $blog->comments()->approved()->with(['user'])->latest();
        
        $comments = $query->paginate(20);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $comments,
                'message' => 'Commentaires récupérés avec succès'
            ]);
        }
        
        return view('blogs.comments.index', compact('blog', 'comments'));
    }
    
    /**
     * Stocker un nouveau commentaire
     */
    public function store(Request $request, Blog $blog)
    {
        $this->middleware('auth');
        
        $validated = $request->validate([
            'content' => 'required|string|min:3|max:1000',
            'parent_id' => 'nullable|exists:blog_comments,id'
        ]);
        
        $validated['blog_id'] = $blog->id;
        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending'; // Modération par défaut
        
        // Vérifier que le parent appartient au même blog
        if ($validated['parent_id']) {
            $parent = BlogComment::find($validated['parent_id']);
            if ($parent && $parent->blog_id !== $blog->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Commentaire parent invalide'
                ], 400);
            }
        }
        
        $comment = BlogComment::create($validated);
        $comment->load(['user']);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $comment,
                'message' => 'Commentaire ajouté avec succès (en attente de modération)'
            ], 201);
        }
        
        return back()->with('success', 'Commentaire ajouté avec succès (en attente de modération)');
    }
    
    /**
     * Afficher un commentaire spécifique
     */
    public function show(Blog $blog, BlogComment $comment)
    {
        if ($comment->blog_id !== $blog->id) {
            abort(404);
        }
        
        $comment->load(['user', 'replies.user']);
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $comment,
                'message' => 'Commentaire récupéré avec succès'
            ]);
        }
        
        return view('blogs.comments.show', compact('blog', 'comment'));
    }
    
    /**
     * Mettre à jour un commentaire (par l'auteur)
     */
    public function update(Request $request, Blog $blog, BlogComment $comment)
    {
        if ($comment->blog_id !== $blog->id) {
            abort(404);
        }
        
        // Seul l'auteur peut modifier son commentaire
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Vous ne pouvez modifier que vos propres commentaires');
        }
        
        $validated = $request->validate([
            'content' => 'required|string|min:3|max:1000'
        ]);
        
        // Remettre en modération après modification
        $validated['status'] = 'pending';
        $validated['updated_by'] = Auth::id();
        
        $comment->update($validated);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $comment->fresh(),
                'message' => 'Commentaire mis à jour (en attente de modération)'
            ]);
        }
        
        return back()->with('success', 'Commentaire mis à jour (en attente de modération)');
    }
    
    /**
     * Supprimer un commentaire (par l'auteur)
     */
    public function destroy(Request $request, Blog $blog, BlogComment $comment)
    {
        if ($comment->blog_id !== $blog->id) {
            abort(404);
        }
        
        // Seul l'auteur peut supprimer son commentaire
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Vous ne pouvez supprimer que vos propres commentaires');
        }
        
        $comment->delete();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Commentaire supprimé avec succès'
            ]);
        }
        
        return back()->with('success', 'Commentaire supprimé avec succès');
    }
    
    /**
     * Liker/Unliker un commentaire
     */
    public function toggleLike(Request $request, Blog $blog, BlogComment $comment)
    {
        if ($comment->blog_id !== $blog->id) {
            abort(404);
        }
        
        $userId = Auth::id();
        $likes = $comment->likes ?? [];
        
        if (in_array($userId, $likes)) {
            // Retirer le like
            $likes = array_diff($likes, [$userId]);
            $action = 'unliked';
        } else {
            // Ajouter le like
            $likes[] = $userId;
            $action = 'liked';
        }
        
        $comment->update(['likes' => array_values($likes)]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'likes_count' => count($likes),
                    'user_liked' => $action === 'liked',
                    'action' => $action
                ],
                'message' => $action === 'liked' ? 'Commentaire liké' : 'Like retiré'
            ]);
        }
        
        return back();
    }
    
    /**
     * Obtenir les réponses d'un commentaire
     */
    public function replies(Blog $blog, BlogComment $comment, Request $request)
    {
        if ($comment->blog_id !== $blog->id) {
            abort(404);
        }
        
        $replies = $comment->replies()
            ->approved()
            ->with(['user'])
            ->latest()
            ->paginate(10);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $replies,
                'message' => 'Réponses récupérées avec succès'
            ]);
        }
        
        return view('blogs.comments.replies', compact('blog', 'comment', 'replies'));
    }
    
    /**
     * Obtenir les commentaires de l'utilisateur connecté
     */
    public function myComments(Request $request)
    {
        $comments = Auth::user()
            ->blogComments()
            ->with(['blog'])
            ->latest()
            ->paginate(20);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $comments,
                'message' => 'Vos commentaires récupérés avec succès'
            ]);
        }
        
        return view('user.comments', compact('comments'));
    }
}
