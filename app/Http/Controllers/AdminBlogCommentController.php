<?php

namespace App\Http\Controllers;

use App\Models\BlogComment;
use App\Models\BlogCommentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminBlogCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Afficher la page des signalements de commentaires
     */
    public function reports()
    {
        // Vérifier l'accès admin
        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            abort(403, 'Accès refusé. Seuls les administrateurs peuvent accéder à cette page.');
        }

        // Récupérer les statistiques
        $stats = [
            'total_reports' => BlogCommentReport::count(),
            'pending_reports' => BlogCommentReport::where('status', 'pending')->count(),
            'resolved_reports' => BlogCommentReport::where('status', 'resolved')->count(),
            'dismissed_reports' => BlogCommentReport::where('status', 'dismissed')->count(),
        ];

        // Récupérer les signalements avec pagination
        $reports = BlogCommentReport::with(['comment.post', 'comment.user', 'reporter', 'reviewer'])
            ->latest()
            ->paginate(20);

        return view('admin.blog.comments.reports', compact('stats', 'reports'));
    }

    /**
     * Mettre à jour un signalement
     */
    public function updateReport(Request $request, BlogCommentReport $report)
    {
        $request->validate([
            'action' => 'required|in:moderate,delete,dismiss',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $action = $request->action;
        $adminNotes = $request->admin_notes;

        // Vérifier que le signalement peut être traité
        if ($report->status !== 'pending') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce signalement a déjà été traité'
                ], 422);
            }
            return redirect()->back()->with('error', 'Ce signalement a déjà été traité');
        }

        $message = '';
        $actionTaken = 'none';

        try {
            // Traiter selon l'action demandée
            switch ($action) {
                case 'moderate':
                    // Modérer = masquer le commentaire
                    if ($report->comment) {
                        $report->comment->update([
                            'status' => 'rejected',
                            'moderated_by' => Auth::id(),
                            'moderated_at' => now()
                        ]);
                        $actionTaken = 'comment_hidden';
                        $message = 'Signalement traité : commentaire modéré avec succès';
                    }
                    $report->status = 'resolved';
                    break;

                case 'delete':
                    // Supprimer le commentaire définitivement
                    if ($report->comment) {
                        // Supprimer les autres signalements de ce commentaire
                        BlogCommentReport::where('blog_comment_id', $report->comment->id)
                                       ->where('id', '!=', $report->id)
                                       ->delete();
                        $report->comment->delete();
                        $actionTaken = 'comment_deleted';
                        $message = 'Signalement traité : commentaire supprimé avec succès';
                    }
                    $report->status = 'resolved';
                    break;

                case 'dismiss':
                    // Rejeter le signalement
                    $report->status = 'dismissed';
                    $message = 'Signalement rejeté : aucune action requise';
                    break;

                default:
                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Action non valide'
                        ], 422);
                    }
                    return redirect()->back()->with('error', 'Action non valide');
            }

            // Mettre à jour le signalement
            $report->update([
                'status' => $report->status,
                'action_taken' => $actionTaken,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'admin_notes' => $adminNotes
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Erreur lors du traitement du signalement', [
                'report_id' => $report->id,
                'action' => $action,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du traitement : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors du traitement : ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un signalement
     */
    public function destroyReport(BlogCommentReport $report)
    {
        $report->delete();
        return redirect()->back()->with('success', 'Signalement supprimé avec succès');
    }
}
