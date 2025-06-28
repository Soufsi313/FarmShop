<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogComment;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogCommentAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'permission:manage blogs']);
    }
    
    /**
     * Liste des commentaires pour l'admin
     */
    public function index(Request $request)
    {
        $query = BlogComment::with(['blog', 'user']);
        
        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        
        if ($request->filled('blog_id')) {
            $query->where('blog_id', $request->input('blog_id'));
        }
        
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('content', 'LIKE', "%{$search}%");
        }
        
        // Tri
        $sortBy = $request->input('sort', 'created_at');
        $sortOrder = $request->input('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $comments = $query->paginate(20);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $comments,
                'message' => 'Commentaires récupérés avec succès'
            ]);
        }
        
        return view('admin.blog-comments.index', compact('comments'));
    }
    
    /**
     * Afficher un commentaire spécifique
     */
    public function show(BlogComment $comment)
    {
        $comment->load(['blog', 'user', 'replies', 'reports']);
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $comment,
                'message' => 'Commentaire récupéré avec succès'
            ]);
        }
        
        return view('admin.blog-comments.show', compact('comment'));
    }
    
    /**
     * Approuver un commentaire
     */
    public function approve(Request $request, BlogComment $comment)
    {
        $comment->update([
            'status' => 'approved',
            'moderated_by' => Auth::id(),
            'moderated_at' => now()
        ]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $comment->fresh(),
                'message' => 'Commentaire approuvé'
            ]);
        }
        
        return back()->with('success', 'Commentaire approuvé');
    }
    
    /**
     * Rejeter un commentaire
     */
    public function reject(Request $request, BlogComment $comment)
    {
        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:500'
        ]);
        
        $comment->update([
            'status' => 'rejected',
            'moderated_by' => Auth::id(),
            'moderated_at' => now(),
            'rejection_reason' => $validated['rejection_reason'] ?? null
        ]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $comment->fresh(),
                'message' => 'Commentaire rejeté'
            ]);
        }
        
        return back()->with('success', 'Commentaire rejeté');
    }
    
    /**
     * Masquer un commentaire
     */
    public function hide(Request $request, BlogComment $comment)
    {
        $comment->update([
            'status' => 'hidden',
            'moderated_by' => Auth::id(),
            'moderated_at' => now()
        ]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $comment->fresh(),
                'message' => 'Commentaire masqué'
            ]);
        }
        
        return back()->with('success', 'Commentaire masqué');
    }
    
    /**
     * Restaurer un commentaire
     */
    public function restore(Request $request, BlogComment $comment)
    {
        $comment->update([
            'status' => 'approved',
            'moderated_by' => Auth::id(),
            'moderated_at' => now()
        ]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $comment->fresh(),
                'message' => 'Commentaire restauré'
            ]);
        }
        
        return back()->with('success', 'Commentaire restauré');
    }
    
    /**
     * Supprimer un commentaire
     */
    public function destroy(Request $request, BlogComment $comment)
    {
        $comment->delete();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Commentaire supprimé'
            ]);
        }
        
        return back()->with('success', 'Commentaire supprimé');
    }
    
    /**
     * Actions en lot
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,hide,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:blog_comments,id',
            'bulk_rejection_reason' => 'nullable|string|max:500'
        ]);
        
        $comments = BlogComment::whereIn('id', $request->ids);
        $count = $comments->count();
        
        switch ($request->action) {
            case 'approve':
                $comments->update([
                    'status' => 'approved',
                    'moderated_by' => Auth::id(),
                    'moderated_at' => now()
                ]);
                $message = "{$count} commentaire(s) approuvé(s)";
                break;
                
            case 'reject':
                $comments->update([
                    'status' => 'rejected',
                    'moderated_by' => Auth::id(),
                    'moderated_at' => now(),
                    'rejection_reason' => $request->bulk_rejection_reason
                ]);
                $message = "{$count} commentaire(s) rejeté(s)";
                break;
                
            case 'hide':
                $comments->update([
                    'status' => 'hidden',
                    'moderated_by' => Auth::id(),
                    'moderated_at' => now()
                ]);
                $message = "{$count} commentaire(s) masqué(s)";
                break;
                
            case 'delete':
                $comments->delete();
                $message = "{$count} commentaire(s) supprimé(s)";
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
     * Statistiques des commentaires
     */
    public function statistics()
    {
        $stats = [
            'total_comments' => BlogComment::count(),
            'pending_comments' => BlogComment::where('status', 'pending')->count(),
            'approved_comments' => BlogComment::where('status', 'approved')->count(),
            'rejected_comments' => BlogComment::where('status', 'rejected')->count(),
            'hidden_comments' => BlogComment::where('status', 'hidden')->count(),
            'comments_today' => BlogComment::whereDate('created_at', today())->count(),
            'comments_this_week' => BlogComment::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'most_active_blogs' => Blog::withCount('comments')->orderBy('comments_count', 'desc')->take(10)->get(['title', 'comments_count']),
            'recent_comments' => BlogComment::with(['blog', 'user'])->latest()->take(10)->get()
        ];
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Statistiques des commentaires récupérées'
            ]);
        }
        
        return view('admin.blog-comments.statistics', compact('stats'));
    }
}
