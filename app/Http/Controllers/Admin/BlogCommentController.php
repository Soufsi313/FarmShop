<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogComment;
use App\Models\BlogCommentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogCommentController extends Controller
{
    public function index(Request $request)
    {
        // Si c'est une requête AJAX, retourner du JSON
        if ($request->ajax() || $request->wantsJson()) {
            $query = BlogComment::with(['user', 'post', 'parent']);
            
            // Filtres
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('content', 'like', "%{$search}%")
                      ->orWhere('guest_name', 'like', "%{$search}%")
                      ->orWhere('guest_email', 'like', "%{$search}%")
                      ->orWhereHas('user', function($subQ) use ($search) {
                          $subQ->where('name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('post', function($subQ) use ($search) {
                          $subQ->where('title', 'like', "%{$search}%");
                      });
                });
            }
            
            if ($request->filled('status')) {
                $query->where('status', $request->get('status'));
            }
            
            if ($request->filled('type')) {
                if ($request->get('type') === 'guest') {
                    $query->whereNull('user_id');
                } else {
                    $query->whereNotNull('user_id');
                }
            }
            
            if ($request->filled('post_id')) {
                $query->where('post_id', $request->get('post_id'));
            }
            
            if ($request->filled('comment_id')) {
                $query->where('id', $request->get('comment_id'));
            }
            
            // Tri
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);
            
            // Pagination
            $perPage = $request->get('per_page', 15);
            $comments = $query->paginate($perPage);
            
            // Statistiques
            $stats = [
                'total_comments' => BlogComment::count(),
                'published_comments' => BlogComment::where('status', 'published')->count(),
                'pending_comments' => BlogComment::where('status', 'pending')->count(),
                'rejected_comments' => BlogComment::where('status', 'rejected')->count(),
                'guest_comments' => BlogComment::whereNull('user_id')->count(),
                'user_comments' => BlogComment::whereNotNull('user_id')->count(),
            ];
            
            return response()->json([
                'data' => $comments,
                'meta' => $stats
            ]);
        }
        
        // Vue normale
        return view('admin.blog.comments.index_alpine');
    }
    
    public function show($id)
    {
        $comment = BlogComment::with(['user', 'post', 'parent', 'replies.user'])->findOrFail($id);
        
        return view('admin.blog.comments.show', compact('comment'));
    }
    
    public function update(Request $request, $id)
    {
        $comment = BlogComment::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,published,rejected',
            'content' => 'required|string|min:1'
        ]);
        
        $comment->update([
            'status' => $request->status,
            'content' => $request->content
        ]);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Commentaire mis à jour avec succès',
                'data' => $comment->load(['user', 'post'])
            ]);
        }
        
        return redirect()->route('admin.blog-comments.index')
                         ->with('success', 'Commentaire mis à jour avec succès');
    }
    
    public function destroy($id)
    {
        $comment = BlogComment::findOrFail($id);
        
        // Supprimer également les réponses si nécessaire
        $comment->replies()->delete();
        
        // Supprimer les signalements associés
        BlogCommentReport::where('comment_id', $id)->delete();
        
        $comment->delete();
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Commentaire supprimé avec succès'
            ]);
        }
        
        return redirect()->route('admin.blog-comments.index')
                         ->with('success', 'Commentaire supprimé avec succès');
    }
    
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:publish,reject,delete',
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'exists:blog_comments,id'
        ]);
        
        $commentIds = $request->comment_ids;
        $action = $request->action;
        
        DB::transaction(function() use ($commentIds, $action) {
            switch ($action) {
                case 'publish':
                    BlogComment::whereIn('id', $commentIds)->update(['status' => 'published']);
                    break;
                case 'reject':
                    BlogComment::whereIn('id', $commentIds)->update(['status' => 'rejected']);
                    break;
                case 'delete':
                    // Supprimer les signalements associés
                    BlogCommentReport::whereIn('comment_id', $commentIds)->delete();
                    // Supprimer les commentaires
                    BlogComment::whereIn('id', $commentIds)->delete();
                    break;
            }
        });
        
        $message = match($action) {
            'publish' => 'Commentaires publiés avec succès',
            'reject' => 'Commentaires rejetés avec succès',
            'delete' => 'Commentaires supprimés avec succès'
        };
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }
        
        return redirect()->route('admin.blog-comments.index')->with('success', $message);
    }
    
    public function reports(Request $request)
    {
        // Si c'est une requête AJAX, retourner du JSON
        if ($request->ajax() || $request->wantsJson()) {
            $query = BlogCommentReport::with(['comment.user', 'comment.post', 'reporter']);
            
            // Filtres
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('description', 'like', "%{$search}%")
                      ->orWhereHas('comment', function($subQ) use ($search) {
                          $subQ->where('content', 'like', "%{$search}%");
                      })
                      ->orWhereHas('reporter', function($subQ) use ($search) {
                          $subQ->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            }
            
            if ($request->filled('status')) {
                $query->where('status', $request->get('status'));
            }
            
            if ($request->filled('reason')) {
                $query->where('reason', $request->get('reason'));
            }
            
            if ($request->filled('priority')) {
                $query->where('priority', $request->get('priority'));
            }
            
            // Tri par priorité puis par date
            $query->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 WHEN 'low' THEN 3 END")
                  ->orderBy('created_at', 'desc');
            
            // Pagination
            $perPage = $request->get('per_page', 15);
            $reports = $query->paginate($perPage);
            
            // Statistiques
            $stats = [
                'total_reports' => BlogCommentReport::count(),
                'pending_reports' => BlogCommentReport::where('status', 'pending')->count(),
                'resolved_reports' => BlogCommentReport::where('status', 'resolved')->count(),
                'dismissed_reports' => BlogCommentReport::where('status', 'dismissed')->count(),
                'high_priority' => BlogCommentReport::where('priority', 'high')->count(),
                'medium_priority' => BlogCommentReport::where('priority', 'medium')->count(),
                'low_priority' => BlogCommentReport::where('priority', 'low')->count(),
            ];
            
            return response()->json([
                'data' => $reports,
                'meta' => $stats
            ]);
        }
        
        // Vue normale
        return view('admin.blog.comments.reports_alpine');
    }
    
    public function updateReport(Request $request, $id)
    {
        $report = BlogCommentReport::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,resolved,dismissed',
            'admin_notes' => 'nullable|string',
            'comment_action' => 'nullable|in:none,moderate,delete'
        ]);
        
        DB::transaction(function() use ($report, $request) {
            // Mettre à jour le signalement
            $report->update([
                'status' => $request->status,
                'admin_notes' => $request->admin_notes,
                'processed_at' => now(),
                'processed_by' => auth()->id()
            ]);
            
            // Traiter le commentaire si nécessaire
            if ($request->status === 'resolved' && $request->comment_action && $request->comment_action !== 'none') {
                $comment = $report->comment;
                if ($comment) {
                    switch ($request->comment_action) {
                        case 'moderate':
                            $comment->update(['status' => 'rejected']);
                            break;
                        case 'delete':
                            // Supprimer les réponses si nécessaire
                            $comment->replies()->delete();
                            // Supprimer les autres signalements de ce commentaire
                            BlogCommentReport::where('comment_id', $comment->id)
                                           ->where('id', '!=', $report->id)
                                           ->delete();
                            $comment->delete();
                            break;
                    }
                }
            }
        });
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Signalement traité avec succès',
                'data' => $report->load(['comment.user', 'comment.post', 'reporter'])
            ]);
        }
        
        return redirect()->route('admin.blog-comment-reports.index')
                         ->with('success', 'Signalement traité avec succès');
    }
    
    public function destroyReport($id)
    {
        $report = BlogCommentReport::findOrFail($id);
        $report->delete();
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Signalement supprimé avec succès'
            ]);
        }
        
        return redirect()->route('admin.blog-comment-reports.index')
                         ->with('success', 'Signalement supprimé avec succès');
    }
}
