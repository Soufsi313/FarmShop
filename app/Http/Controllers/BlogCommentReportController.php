<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\BlogCommentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BlogCommentReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Signaler un commentaire
     */
    public function store(Request $request, Blog $blog, BlogComment $comment)
    {
        if ($comment->blog_id !== $blog->id) {
            abort(404);
        }
        
        // Vérifier que l'utilisateur n'a pas déjà signalé ce commentaire
        $existingReport = BlogCommentReport::where('comment_id', $comment->id)
            ->where('reported_by', Auth::id())
            ->first();
        
        if ($existingReport) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà signalé ce commentaire'
            ], 400);
        }
        
        $validated = $request->validate([
            'reason' => ['required', Rule::in(['spam', 'inappropriate', 'offensive', 'harassment', 'other'])],
            'description' => 'nullable|string|max:500'
        ]);
        
        $validated['comment_id'] = $comment->id;
        $validated['reported_by'] = Auth::id();
        $validated['status'] = 'pending';
        
        $report = BlogCommentReport::create($validated);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $report,
                'message' => 'Commentaire signalé avec succès. Notre équipe va examiner votre signalement.'
            ], 201);
        }
        
        return back()->with('success', 'Commentaire signalé avec succès. Notre équipe va examiner votre signalement.');
    }
    
    /**
     * Obtenir les signalements de l'utilisateur
     */
    public function myReports(Request $request)
    {
        $reports = Auth::user()
            ->blogCommentReports()
            ->with(['comment.blog', 'comment.user'])
            ->latest()
            ->paginate(20);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $reports,
                'message' => 'Vos signalements récupérés avec succès'
            ]);
        }
        
        return view('user.reports', compact('reports'));
    }
    
    /**
     * Annuler un signalement (si encore en pending)
     */
    public function cancel(Request $request, BlogCommentReport $report)
    {
        // Vérifier que le signalement appartient à l'utilisateur
        if ($report->reported_by !== Auth::id()) {
            abort(403, 'Vous ne pouvez annuler que vos propres signalements');
        }
        
        // On ne peut annuler que les signalements en attente
        if ($report->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Ce signalement ne peut plus être annulé'
            ], 400);
        }
        
        $report->update(['status' => 'cancelled']);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Signalement annulé avec succès'
            ]);
        }
        
        return back()->with('success', 'Signalement annulé avec succès');
    }
}
