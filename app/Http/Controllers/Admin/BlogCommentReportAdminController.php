<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCommentReport;
use App\Models\BlogComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogCommentReportAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'permission:manage blogs']);
    }
    
    /**
     * Liste des signalements pour l'admin
     */
    public function index(Request $request)
    {
        $query = BlogCommentReport::with(['comment.blog', 'comment.user', 'reportedBy']);
        
        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        
        if ($request->filled('reason')) {
            $query->where('reason', $request->input('reason'));
        }
        
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('comment', function($q) use ($search) {
                $q->where('content', 'LIKE', "%{$search}%");
            });
        }
        
        // Tri
        $sortBy = $request->input('sort', 'created_at');
        $sortOrder = $request->input('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $reports = $query->paginate(20);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $reports,
                'message' => 'Signalements récupérés avec succès'
            ]);
        }
        
        return view('admin.blog-reports.index', compact('reports'));
    }
    
    /**
     * Afficher un signalement spécifique
     */
    public function show(BlogCommentReport $report)
    {
        $report->load(['comment.blog', 'comment.user', 'reportedBy']);
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $report,
                'message' => 'Signalement récupéré avec succès'
            ]);
        }
        
        return view('admin.blog-reports.show', compact('report'));
    }
    
    /**
     * Approuver un signalement (et prendre action)
     */
    public function approve(Request $request, BlogCommentReport $report)
    {
        $validated = $request->validate([
            'action' => 'required|in:delete_comment,hide_comment,warn_user,no_action',
            'admin_notes' => 'nullable|string|max:500'
        ]);
        
        $validated['status'] = 'approved';
        $validated['reviewed_by'] = Auth::id();
        $validated['reviewed_at'] = now();
        
        // Exécuter l'action sur le commentaire
        $comment = $report->comment;
        switch ($validated['action']) {
            case 'delete_comment':
                $comment->delete();
                break;
            case 'hide_comment':
                $comment->update(['status' => 'hidden']);
                break;
            case 'warn_user':
                // Ici vous pourriez implémenter un système d'avertissement
                // Par exemple, envoyer une notification à l'utilisateur
                break;
            case 'no_action':
                // Aucune action sur le commentaire
                break;
        }
        
        $report->update($validated);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $report->fresh(),
                'message' => 'Signalement approuvé et action exécutée'
            ]);
        }
        
        return back()->with('success', 'Signalement approuvé et action exécutée');
    }
    
    /**
     * Rejeter un signalement
     */
    public function reject(Request $request, BlogCommentReport $report)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:500'
        ]);
        
        $validated['status'] = 'rejected';
        $validated['reviewed_by'] = Auth::id();
        $validated['reviewed_at'] = now();
        
        $report->update($validated);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $report->fresh(),
                'message' => 'Signalement rejeté'
            ]);
        }
        
        return back()->with('success', 'Signalement rejeté');
    }
    
    /**
     * Actions en lot sur les signalements
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:blog_comment_reports,id',
            'bulk_admin_notes' => 'nullable|string|max:500'
        ]);
        
        $reports = BlogCommentReport::whereIn('id', $request->ids);
        $count = $reports->count();
        
        switch ($request->action) {
            case 'approve':
                $reports->update([
                    'status' => 'approved',
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now(),
                    'admin_notes' => $request->bulk_admin_notes
                ]);
                $message = "{$count} signalement(s) approuvé(s)";
                break;
                
            case 'reject':
                $reports->update([
                    'status' => 'rejected',
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now(),
                    'admin_notes' => $request->bulk_admin_notes
                ]);
                $message = "{$count} signalement(s) rejeté(s)";
                break;
                
            case 'delete':
                $reports->delete();
                $message = "{$count} signalement(s) supprimé(s)";
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
     * Statistiques des signalements
     */
    public function statistics()
    {
        $stats = [
            'total_reports' => BlogCommentReport::count(),
            'pending_reports' => BlogCommentReport::where('status', 'pending')->count(),
            'approved_reports' => BlogCommentReport::where('status', 'approved')->count(),
            'rejected_reports' => BlogCommentReport::where('status', 'rejected')->count(),
            'reports_by_reason' => BlogCommentReport::selectRaw('reason, COUNT(*) as count')
                ->groupBy('reason')
                ->pluck('count', 'reason'),
            'recent_reports' => BlogCommentReport::with(['comment.blog', 'reportedBy'])
                ->latest()
                ->take(10)
                ->get()
        ];
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Statistiques des signalements récupérées'
            ]);
        }
        
        return view('admin.blog-reports.statistics', compact('stats'));
    }
}
