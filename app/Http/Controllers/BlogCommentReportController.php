<?php

namespace App\Http\Controllers;

use App\Models\BlogCommentReport;
use App\Models\BlogComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogCommentReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('admin')->except(['store']);
    }

    /**
     * Afficher la liste des signalements (Admin seulement)
     */
    public function index(Request $request)
    {
        $query = BlogCommentReport::with(['comment.post', 'comment.user', 'reporter', 'reviewer']);

        // Recherche
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filtrage par statut
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Filtrage par raison
        if ($request->filled('reason')) {
            $query->byReason($request->reason);
        }

        // Filtrage par priorité
        if ($request->filled('priority')) {
            $query->byPriority($request->priority);
        }

        // Filtrage haute priorité
        if ($request->filled('high_priority') && $request->high_priority === 'true') {
            $query->highPriority();
        }

        // Tri
        $sortBy = $request->get('sort_by', 'recent');
        switch ($sortBy) {
            case 'priority':
                $query->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low')");
                break;
            case 'status':
                $query->orderByRaw("FIELD(status, 'pending', 'reviewed', 'resolved', 'dismissed')");
                break;
            default:
                $query->recent();
        }

        $reports = $query->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => $reports,
            'meta' => [
                'total_reports' => BlogCommentReport::count(),
                'pending_reports' => BlogCommentReport::pending()->count(),
                'resolved_reports' => BlogCommentReport::resolved()->count(),
                'high_priority_reports' => BlogCommentReport::highPriority()->count(),
            ]
        ]);
    }

    /**
     * Afficher un signalement spécifique (Admin seulement)
     */
    public function show(BlogCommentReport $blogCommentReport)
    {
        $report = $blogCommentReport->load([
            'comment.post',
            'comment.user',
            'reporter',
            'reviewer'
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $report
        ]);
    }

    /**
     * Signaler un commentaire
     */
    public function store(Request $request, BlogComment $blogComment)
    {
        // Vérifier si l'utilisateur a déjà signalé ce commentaire
        $existingReport = BlogCommentReport::where('blog_comment_id', $blogComment->id)
            ->where('reported_by', Auth::id())
            ->first();

        if ($existingReport) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vous avez déjà signalé ce commentaire'
            ], 422);
        }

        $validated = $request->validate([
            'reason' => 'required|in:spam,inappropriate_content,harassment,hate_speech,false_information,copyright_violation,other',
            'description' => 'nullable|string|max:1000',
            'additional_info' => 'nullable|array',
            'evidence' => 'nullable|array'
        ]);

        $validated['blog_comment_id'] = $blogComment->id;
        $validated['reported_by'] = Auth::id();

        // Déterminer la priorité basée sur la raison
        $highPriorityReasons = ['harassment', 'hate_speech', 'inappropriate_content'];
        $validated['priority'] = in_array($validated['reason'], $highPriorityReasons) ? 'high' : 'medium';

        $report = BlogCommentReport::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Signalement envoyé avec succès. Notre équipe l\'examinera rapidement.',
            'data' => $report
        ], 201);
    }

    /**
     * Examiner un signalement (Admin seulement)
     */
    public function review(Request $request, BlogCommentReport $blogCommentReport)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $blogCommentReport->review(Auth::id(), $validated['admin_notes'] ?? null);

        return response()->json([
            'status' => 'success',
            'message' => 'Signalement marqué comme examiné',
            'data' => $blogCommentReport->fresh()
        ]);
    }

    /**
     * Résoudre un signalement (Admin seulement)
     */
    public function resolve(Request $request, BlogCommentReport $blogCommentReport)
    {
        $validated = $request->validate([
            'action_taken' => 'required|in:none,warning_sent,comment_hidden,comment_deleted,user_warned,user_suspended,user_banned',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $blogCommentReport->resolve(
            $validated['action_taken'],
            Auth::id(),
            $validated['admin_notes'] ?? null
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Signalement résolu avec succès',
            'data' => $blogCommentReport->fresh()
        ]);
    }

    /**
     * Rejeter un signalement (Admin seulement)
     */
    public function dismiss(Request $request, BlogCommentReport $blogCommentReport)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $blogCommentReport->dismiss(Auth::id(), $validated['admin_notes'] ?? null);

        return response()->json([
            'status' => 'success',
            'message' => 'Signalement rejeté',
            'data' => $blogCommentReport->fresh()
        ]);
    }

    /**
     * Mettre à jour la priorité d'un signalement (Admin seulement)
     */
    public function updatePriority(Request $request, BlogCommentReport $blogCommentReport)
    {
        $validated = $request->validate([
            'priority' => 'required|in:low,medium,high,urgent'
        ]);

        $blogCommentReport->updatePriority($validated['priority']);

        return response()->json([
            'status' => 'success',
            'message' => 'Priorité mise à jour avec succès',
            'data' => $blogCommentReport->fresh()
        ]);
    }

    /**
     * Statistiques des signalements (Admin seulement)
     */
    public function statistics()
    {
        $stats = [
            'total_reports' => BlogCommentReport::count(),
            'pending_reports' => BlogCommentReport::pending()->count(),
            'reviewed_reports' => BlogCommentReport::reviewed()->count(),
            'resolved_reports' => BlogCommentReport::resolved()->count(),
            'dismissed_reports' => BlogCommentReport::dismissed()->count(),
            'high_priority_reports' => BlogCommentReport::highPriority()->count(),
            'reports_by_reason' => BlogCommentReport::selectRaw('reason, COUNT(*) as count')
                ->groupBy('reason')
                ->orderBy('count', 'desc')
                ->get(),
            'reports_by_status' => BlogCommentReport::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get(),
            'reports_by_priority' => BlogCommentReport::selectRaw('priority, COUNT(*) as count')
                ->groupBy('priority')
                ->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low')")
                ->get(),
            'recent_reports' => BlogCommentReport::with(['comment.post', 'reporter'])
                ->latest()
                ->take(10)
                ->get(),
            'reports_by_day' => BlogCommentReport::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            'top_reporters' => BlogCommentReport::selectRaw('reported_by, COUNT(*) as reports_count')
                ->with('reporter:id,name')
                ->groupBy('reported_by')
                ->orderBy('reports_count', 'desc')
                ->take(10)
                ->get(),
            'resolution_stats' => [
                'avg_resolution_time' => BlogCommentReport::resolved()
                    ->whereNotNull('reviewed_at')
                    ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, reviewed_at)) as avg_hours')
                    ->value('avg_hours'),
                'actions_taken' => BlogCommentReport::resolved()
                    ->selectRaw('action_taken, COUNT(*) as count')
                    ->groupBy('action_taken')
                    ->get()
            ]
        ];

        return response()->json([
            'status' => 'success',
            'data' => $stats
        ]);
    }

    /**
     * Obtenir les signalements d'un commentaire spécifique (Admin seulement)
     */
    public function commentReports(BlogComment $blogComment)
    {
        $reports = $blogComment->reports()
            ->with(['reporter', 'reviewer'])
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $reports
        ]);
    }

    /**
     * Actions en lot sur les signalements (Admin seulement)
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:review,resolve,dismiss',
            'report_ids' => 'required|array',
            'report_ids.*' => 'exists:blog_comment_reports,id',
            'action_taken' => 'required_if:action,resolve|in:none,warning_sent,comment_hidden,comment_deleted,user_warned,user_suspended,user_banned',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $reports = BlogCommentReport::whereIn('id', $validated['report_ids'])->get();
        $processedCount = 0;

        foreach ($reports as $report) {
            switch ($validated['action']) {
                case 'review':
                    $report->review(Auth::id(), $validated['admin_notes'] ?? null);
                    $processedCount++;
                    break;
                    
                case 'resolve':
                    $report->resolve(
                        $validated['action_taken'],
                        Auth::id(),
                        $validated['admin_notes'] ?? null
                    );
                    $processedCount++;
                    break;
                    
                case 'dismiss':
                    $report->dismiss(Auth::id(), $validated['admin_notes'] ?? null);
                    $processedCount++;
                    break;
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => "Actions appliquées à {$processedCount} signalement(s)",
            'data' => ['processed_count' => $processedCount]
        ]);
    }

    /**
     * Traiter un signalement (résoudre ou rejeter)
     */
    public function process(Request $request, BlogCommentReport $blogCommentReport)
    {
        $request->validate([
            'action' => 'required|in:resolve,dismiss',
            'comment_action' => 'nullable|in:delete,reject,keep',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $action = $request->action;
        $commentAction = $request->comment_action;
        $adminNotes = $request->admin_notes;

        // Vérifier que le signalement peut être traité
        if ($blogCommentReport->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Ce signalement a déjà été traité'
            ], 400);
        }

        // Traiter le signalement
        if ($action === 'resolve') {
            $blogCommentReport->status = 'resolved';
            $message = 'Signalement résolu avec succès';

            // Actions sur le commentaire si spécifiées
            if ($commentAction && $blogCommentReport->comment) {
                switch ($commentAction) {
                    case 'delete':
                        $blogCommentReport->comment->delete();
                        break;
                    case 'reject':
                        $blogCommentReport->comment->update([
                            'status' => 'rejected',
                            'moderated_by' => Auth::id(),
                            'moderated_at' => now()
                        ]);
                        break;
                    case 'keep':
                        // Ne rien faire, garder le commentaire tel quel
                        break;
                }
            }
        } else {
            $blogCommentReport->status = 'dismissed';
            $message = 'Signalement rejeté avec succès';
        }

        // Mettre à jour le signalement
        $blogCommentReport->update([
            'status' => $blogCommentReport->status,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => $adminNotes
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $blogCommentReport->fresh(['comment.post', 'comment.user', 'reporter', 'reviewer'])
        ]);
    }
}
