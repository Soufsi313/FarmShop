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
        // Utiliser uniquement l'authentification web (session) pour les signalements
        $this->middleware('auth');
        $this->middleware('admin')->except(['store']);
    }

    /**
     * @OA\Get(
     *     path="/api/admin/blog/comment-reports",
     *     tags={"Admin", "Blog", "Reports"},
     *     summary="Liste des signalements de commentaires",
     *     description="Récupère la liste des signalements de commentaires avec filtrage (Admin uniquement)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Terme de recherche dans le signalement ou commentaire",
     *         @OA\Schema(type="string", example="spam")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filtrer par statut",
     *         @OA\Schema(type="string", enum={"pending", "reviewing", "resolved", "dismissed"}, example="pending")
     *     ),
     *     @OA\Parameter(
     *         name="reason",
     *         in="query",
     *         description="Filtrer par raison du signalement",
     *         @OA\Schema(type="string", enum={"spam", "inappropriate", "harassment", "hate_speech", "violence", "other"}, example="spam")
     *     ),
     *     @OA\Parameter(
     *         name="priority",
     *         in="query",
     *         description="Filtrer par priorité",
     *         @OA\Schema(type="string", enum={"low", "medium", "high", "urgent"}, example="high")
     *     ),
     *     @OA\Parameter(
     *         name="high_priority",
     *         in="query",
     *         description="Afficher uniquement les signalements haute priorité",
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         description="Critère de tri",
     *         @OA\Schema(type="string", enum={"recent", "oldest", "priority"}, example="priority")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Nombre d'éléments par page",
     *         @OA\Schema(type="integer", minimum=1, maximum=100, example=20)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des signalements récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", ref="#/components/schemas/PaginatedResponse"),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="total_reports", type="integer", example=157),
     *                 @OA\Property(property="pending_reports", type="integer", example=23),
     *                 @OA\Property(property="reviewing_reports", type="integer", example=8),
     *                 @OA\Property(property="resolved_reports", type="integer", example=118),
     *                 @OA\Property(property="dismissed_reports", type="integer", example=8),
     *                 @OA\Property(property="high_priority_reports", type="integer", example=5)
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
     * @OA\Get(
     *     path="/api/admin/blog/comment-reports/{blogCommentReport}",
     *     tags={"Admin", "Blog", "Reports"},
     *     summary="Détails d'un signalement",
     *     description="Récupère les détails complets d'un signalement de commentaire (Admin uniquement)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="blogCommentReport",
     *         in="path",
     *         description="ID du signalement",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails du signalement récupérés avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", ref="#/components/schemas/BlogCommentReport")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Signalement non trouvé",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès non autorisé",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     * 
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
     * @OA\Post(
     *     path="/api/blog/comments/{blogComment}/report",
     *     tags={"Blog", "Comments", "Reports"},
     *     summary="Signaler un commentaire",
     *     description="Permet à un utilisateur de signaler un commentaire inapproprié",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="blogComment",
     *         in="path",
     *         description="ID du commentaire à signaler",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Détails du signalement",
     *         @OA\JsonContent(
     *             required={"reason"},
     *             @OA\Property(property="reason", type="string", enum={"spam", "inappropriate", "harassment", "hate_speech", "violence", "other"}, example="spam", description="Raison du signalement"),
     *             @OA\Property(property="description", type="string", maxLength=1000, example="Ce commentaire contient des liens publicitaires non pertinents", description="Description détaillée du problème")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Signalement créé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Signalement créé avec succès"),
     *             @OA\Property(property="data", ref="#/components/schemas/BlogCommentReport")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Commentaire déjà signalé par cet utilisateur",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Vous avez déjà signalé ce commentaire")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreurs de validation",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     * 
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
     * @OA\Get(
     *     path="/api/admin/blog/comment-reports/statistics",
     *     tags={"Admin", "Blog", "Reports"},
     *     summary="Statistiques des signalements",
     *     description="Récupère les statistiques complètes des signalements de commentaires (Admin uniquement)",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Statistiques récupérées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="total_reports", type="integer", example=157, description="Total des signalements"),
     *                 @OA\Property(property="pending_reports", type="integer", example=23, description="Signalements en attente"),
     *                 @OA\Property(property="reviewed_reports", type="integer", example=8, description="Signalements en cours de révision"),
     *                 @OA\Property(property="resolved_reports", type="integer", example=118, description="Signalements résolus"),
     *                 @OA\Property(property="dismissed_reports", type="integer", example=8, description="Signalements rejetés"),
     *                 @OA\Property(property="high_priority_reports", type="integer", example=5, description="Signalements haute priorité"),
     *                 @OA\Property(property="recent_reports", type="array", @OA\Items(ref="#/components/schemas/BlogCommentReport"), description="Signalements récents"),
     *                 @OA\Property(property="reports_by_reason", type="array", @OA\Items(type="object", @OA\Property(property="reason", type="string"), @OA\Property(property="count", type="integer")), description="Signalements par raison"),
     *                 @OA\Property(property="reports_by_day", type="array", @OA\Items(type="object", @OA\Property(property="date", type="string", format="date"), @OA\Property(property="count", type="integer")), description="Signalements par jour (7 derniers jours)"),
     *                 @OA\Property(property="most_reported_comments", type="array", @OA\Items(ref="#/components/schemas/BlogComment"), description="Commentaires les plus signalés"),
     *                 @OA\Property(property="top_reporters", type="array", @OA\Items(ref="#/components/schemas/User"), description="Utilisateurs signalant le plus")
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
     * @OA\Patch(
     *     path="/api/admin/blog/comment-reports/{blogCommentReport}/process",
     *     tags={"Admin", "Blog", "Reports"},
     *     summary="Traiter un signalement",
     *     description="Traite un signalement en le résolvant ou le rejetant avec action sur le commentaire (Admin uniquement)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="blogCommentReport",
     *         in="path",
     *         description="ID du signalement à traiter",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Action de traitement",
     *         @OA\JsonContent(
     *             required={"action"},
     *             @OA\Property(property="action", type="string", enum={"resolve", "dismiss"}, example="resolve", description="Action sur le signalement"),
     *             @OA\Property(property="comment_action", type="string", enum={"delete", "reject", "keep"}, example="delete", description="Action sur le commentaire signalé"),
     *             @OA\Property(property="admin_notes", type="string", maxLength=1000, example="Commentaire supprimé pour spam", description="Notes administrateur")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Signalement traité avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Signalement résolu avec succès"),
     *             @OA\Property(property="data", ref="#/components/schemas/BlogCommentReport")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreurs de validation",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Signalement non trouvé",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     * 
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
                'success' => false,
                'message' => 'Ce signalement a déjà été traité'
            ], 400);
        }

        $message = '';
        $actionTaken = 'none';

        // Traiter le signalement
        if ($action === 'resolve') {
            $blogCommentReport->status = 'resolved';

            // Actions sur le commentaire si spécifiées
            if ($commentAction && $blogCommentReport->comment) {
                switch ($commentAction) {
                    case 'delete':
                        $blogCommentReport->comment->delete();
                        $actionTaken = 'comment_deleted';
                        $message = 'Signalement résolu : commentaire supprimé avec succès';
                        break;
                    case 'reject':
                        $blogCommentReport->comment->update([
                            'status' => 'rejected',
                            'moderated_by' => Auth::id(),
                            'moderated_at' => now()
                        ]);
                        $actionTaken = 'comment_hidden';
                        $message = 'Signalement résolu : commentaire masqué du public';
                        break;
                    case 'keep':
                        // Garder le commentaire, mais marquer comme approuvé pour être sûr
                        $blogCommentReport->comment->update([
                            'status' => 'approved',
                            'moderated_by' => Auth::id(),
                            'moderated_at' => now()
                        ]);
                        $actionTaken = 'none';
                        $message = 'Signalement résolu : commentaire conservé et approuvé';
                        break;
                    default:
                        $message = 'Signalement résolu sans action sur le commentaire';
                        break;
                }
            } else {
                $message = 'Signalement résolu sans action spécifique sur le commentaire';
            }
        } else {
            $blogCommentReport->status = 'dismissed';
            $message = 'Signalement rejeté : aucune action requise';
        }

        // Mettre à jour le signalement
        $blogCommentReport->update([
            'status' => $blogCommentReport->status,
            'action_taken' => $actionTaken,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => $adminNotes
        ]);

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $blogCommentReport->fresh(['comment.post', 'comment.user', 'reporter', 'reviewer'])
        ]);
    }
}
