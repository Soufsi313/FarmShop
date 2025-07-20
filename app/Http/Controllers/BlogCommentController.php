<?php

namespace App\Http\Controllers;

use App\Models\BlogComment;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('admin')->only(['index', 'moderate', 'statistics']);
    }

    /**
     * Afficher la liste des commentaires (Admin seulement)
     */
    public function index(Request $request)
    {
        $query = BlogComment::with(['post', 'user', 'moderator']);

        // Recherche
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filtrage par statut
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'pending':
                    $query->pending();
                    break;
                case 'approved':
                    $query->approved();
                    break;
                case 'rejected':
                    $query->rejected();
                    break;
                case 'spam':
                    $query->spam();
                    break;
            }
        }

        // Filtrage par article
        if ($request->filled('post_id')) {
            $query->byPost($request->post_id);
        }

        // Filtrage par utilisateur
        if ($request->filled('user_id')) {
            $query->byUser($request->user_id);
        }

        // Filtrage par signalements
        if ($request->filled('reported') && $request->reported === 'true') {
            $query->reported();
        }

        // Tri
        $sortBy = $request->get('sort_by', 'recent');
        switch ($sortBy) {
            case 'popular':
                $query->popular();
                break;
            case 'reports':
                $query->orderBy('reports_count', 'desc');
                break;
            default:
                $query->recent();
        }

        $comments = $query->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => $comments,
            'meta' => [
                'total_comments' => BlogComment::count(),
                'pending_comments' => BlogComment::pending()->count(),
                'approved_comments' => BlogComment::approved()->count(),
                'reported_comments' => BlogComment::reported()->count(),
            ]
        ]);
    }

    /**
     * Afficher les commentaires d'un article
     */
    public function show(BlogPost $blogPost)
    {
        $comments = $blogPost->topLevelComments()
            ->approved()
            ->with(['user', 'approvedReplies.user'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $comments
        ]);
    }

    /**
     * Afficher les détails d'un commentaire spécifique (Admin seulement)
     */
    public function showComment(BlogComment $blogComment)
    {
        $comment = $blogComment->load(['post', 'user', 'moderator', 'reports.reporter']);

        return response()->json([
            'status' => 'success',
            'data' => $comment
        ]);
    }

    /**
     * Créer un nouveau commentaire
     */
    public function store(Request $request, BlogPost $blogPost)
    {
        // Vérifier si les commentaires sont autorisés
        if (!$blogPost->allow_comments) {
            return response()->json([
                'status' => 'error',
                'message' => 'Les commentaires ne sont pas autorisés sur cet article'
            ], 422);
        }

        // Vérifier si l'article est publié
        if (!$blogPost->is_published) {
            return response()->json([
                'status' => 'error',
                'message' => 'Impossible de commenter un article non publié'
            ], 422);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:blog_comments,id',
            'guest_name' => 'nullable|string|max:255',
            'guest_email' => 'nullable|email|max:255',
            'guest_website' => 'nullable|url|max:255'
        ]);

        $validated['blog_post_id'] = $blogPost->id;

        // Si l'utilisateur est connecté
        if (Auth::check()) {
            $validated['user_id'] = Auth::id();
            // Les admins voient leurs commentaires approuvés automatiquement
            if (Auth::user()->role === 'admin') {
                $validated['status'] = 'approved';
            }
        } else {
            // Commentaire d'invité - validation supplémentaire
            if (!$validated['guest_name'] || !$validated['guest_email']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Le nom et l\'email sont requis pour les invités'
                ], 422);
            }
        }

        // Vérifier si le parent existe et appartient au même article
        if ($validated['parent_id']) {
            $parent = BlogComment::find($validated['parent_id']);
            if (!$parent || $parent->blog_post_id !== $blogPost->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Commentaire parent invalide'
                ], 422);
            }
        }

        $comment = BlogComment::create($validated);

        // Si approuvé automatiquement, incrémenter les compteurs
        if ($comment->status === 'approved') {
            $blogPost->incrementCommentsCount();
            if ($comment->parent) {
                $comment->parent->incrementRepliesCount();
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => $comment->status === 'approved' 
                ? 'Commentaire publié avec succès' 
                : 'Commentaire soumis et en attente de modération',
            'data' => $comment->load('user')
        ], 201);
    }

    /**
     * Mettre à jour un commentaire
     */
    public function update(Request $request, BlogComment $blogComment)
    {
        // Vérifier les permissions
        if (!$blogComment->can_edit) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vous n\'avez pas l\'autorisation de modifier ce commentaire'
            ], 403);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:2000'
        ]);

        $blogComment->editContent($validated['content']);

        return response()->json([
            'status' => 'success',
            'message' => 'Commentaire modifié avec succès',
            'data' => $blogComment->fresh()
        ]);
    }

    /**
     * Supprimer un commentaire
     */
    public function destroy(BlogComment $blogComment)
    {
        // Vérifier les permissions
        if (!$blogComment->can_delete) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vous n\'avez pas l\'autorisation de supprimer ce commentaire'
            ], 403);
        }

        $blogComment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Commentaire supprimé avec succès'
        ]);
    }

    /**
     * Modérer un commentaire (Admin seulement)
     */
    public function moderate(Request $request, BlogComment $blogComment)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject,spam',
            'reason' => 'nullable|string|max:500'
        ]);

        switch ($validated['action']) {
            case 'approve':
                $blogComment->approve();
                $message = 'Commentaire approuvé avec succès';
                break;
                
            case 'reject':
                $blogComment->reject($validated['reason'] ?? null);
                $message = 'Commentaire rejeté avec succès';
                break;
                
            case 'spam':
                $blogComment->markAsSpam();
                $message = 'Commentaire marqué comme spam';
                break;
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $blogComment->fresh()
        ]);
    }

    /**
     * Épingler/Désépingler un commentaire (Admin seulement)
     */
    public function togglePin(BlogComment $blogComment)
    {
        if ($blogComment->is_pinned) {
            $blogComment->unpin();
            $message = 'Commentaire désépinglé';
        } else {
            $blogComment->pin();
            $message = 'Commentaire épinglé';
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $blogComment->fresh()
        ]);
    }

    /**
     * Liker un commentaire
     */
    public function like(BlogComment $blogComment)
    {
        // Vérifier si l'utilisateur a déjà liké (optionnel - implémenter table pivot)
        $blogComment->incrementLikesCount();

        return response()->json([
            'status' => 'success',
            'message' => 'Commentaire liké',
            'data' => ['likes_count' => $blogComment->fresh()->likes_count]
        ]);
    }

    /**
     * Obtenir les réponses d'un commentaire
     */
    public function replies(BlogComment $blogComment)
    {
        $replies = $blogComment->approvedReplies()
            ->with('user')
            ->latest()
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $replies
        ]);
    }

    /**
     * Statistiques des commentaires (Admin seulement)
     */
    public function statistics()
    {
        $stats = [
            'total_comments' => BlogComment::count(),
            'pending_comments' => BlogComment::pending()->count(),
            'approved_comments' => BlogComment::approved()->count(),
            'rejected_comments' => BlogComment::rejected()->count(),
            'spam_comments' => BlogComment::spam()->count(),
            'reported_comments' => BlogComment::reported()->count(),
            'guest_comments' => BlogComment::whereNull('user_id')->count(),
            'user_comments' => BlogComment::whereNotNull('user_id')->count(),
            'most_liked' => BlogComment::orderBy('likes_count', 'desc')->first(),
            'most_replied' => BlogComment::orderBy('replies_count', 'desc')->first(),
            'recent_comments' => BlogComment::with(['user', 'post'])
                ->latest()
                ->take(10)
                ->get(),
            'comments_by_day' => BlogComment::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get()
        ];

        return response()->json([
            'status' => 'success',
            'data' => $stats
        ]);
    }
}
