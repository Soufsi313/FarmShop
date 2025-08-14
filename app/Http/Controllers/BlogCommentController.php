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
     * @OA\Get(
     *     path="/api/admin/blog/comments",
     *     tags={"Admin", "Blog", "Comments"},
     *     summary="Liste des commentaires de blog",
     *     description="Récupère la liste des commentaires avec filtrage et modération (Admin uniquement)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Terme de recherche dans le contenu ou auteur",
     *         @OA\Schema(type="string", example="excellent article")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filtrer par statut de modération",
     *         @OA\Schema(type="string", enum={"pending", "approved", "rejected", "spam"}, example="pending")
     *     ),
     *     @OA\Parameter(
     *         name="post_id",
     *         in="query",
     *         description="Filtrer par article de blog",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="Filtrer par utilisateur",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         description="Critère de tri",
     *         @OA\Schema(type="string", enum={"recent", "oldest", "priority"}, example="recent")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Nombre d'éléments par page",
     *         @OA\Schema(type="integer", minimum=1, maximum=100, example=20)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des commentaires récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", ref="#/components/schemas/PaginatedResponse"),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="total_comments", type="integer", example=547),
     *                 @OA\Property(property="pending_comments", type="integer", example=23),
     *                 @OA\Property(property="approved_comments", type="integer", example=498),
     *                 @OA\Property(property="rejected_comments", type="integer", example=15),
     *                 @OA\Property(property="spam_comments", type="integer", example=11)
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
     * @OA\Get(
     *     path="/api/blog/posts/{blogPost}/comments",
     *     tags={"Blog", "Comments"},
     *     summary="Commentaires d'un article de blog",
     *     description="Récupère les commentaires approuvés d'un article de blog avec les réponses",
     *     @OA\Parameter(
     *         name="blogPost",
     *         in="path",
     *         description="ID de l'article de blog",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Numéro de page",
     *         @OA\Schema(type="integer", minimum=1, example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Commentaires récupérés avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", ref="#/components/schemas/PaginatedResponse")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article non trouvé",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     * 
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
     * @OA\Get(
     *     path="/api/admin/blog/comments/{blogComment}",
     *     tags={"Admin", "Blog", "Comments"},
     *     summary="Détails d'un commentaire de blog",
     *     description="Récupère les détails complets d'un commentaire avec signalements (Admin uniquement)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="blogComment",
     *         in="path",
     *         description="ID du commentaire",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails du commentaire récupérés avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", ref="#/components/schemas/BlogComment")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Commentaire non trouvé",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès non autorisé",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     * 
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
            if (in_array(Auth::user()->role, ['admin', 'Admin'])) {
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
     * @OA\Delete(
     *     path="/api/admin/blog/comments/{blogComment}",
     *     tags={"Admin", "Blog", "Comments"},
     *     summary="Supprimer un commentaire",
     *     description="Supprime définitivement un commentaire de blog (Admin uniquement)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="blogComment",
     *         in="path",
     *         description="ID du commentaire à supprimer",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Commentaire supprimé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Commentaire supprimé avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Pas d'autorisation pour supprimer ce commentaire",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Vous n'avez pas l'autorisation de supprimer ce commentaire")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Commentaire non trouvé",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     * 
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
     * @OA\Post(
     *     path="/api/admin/blog/comments/{blogComment}/moderate",
     *     tags={"Admin", "Blog", "Comments"},
     *     summary="Modérer un commentaire",
     *     description="Approuve, rejette ou marque comme spam un commentaire (Admin uniquement)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="blogComment",
     *         in="path",
     *         description="ID du commentaire à modérer",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Action de modération",
     *         @OA\JsonContent(
     *             required={"action"},
     *             @OA\Property(property="action", type="string", enum={"approve", "reject", "spam"}, example="approve", description="Action de modération"),
     *             @OA\Property(property="reason", type="string", maxLength=500, example="Contenu approprié", description="Raison de la modération (optionnel)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Commentaire modéré avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Commentaire approuvé avec succès"),
     *             @OA\Property(property="data", ref="#/components/schemas/BlogComment")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Commentaire non trouvé",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreurs de validation",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     * 
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
     * @OA\Patch(
     *     path="/api/admin/blog/comments/{blogComment}/toggle-pin",
     *     tags={"Admin", "Blog", "Comments"},
     *     summary="Épingler/Désépingler un commentaire",
     *     description="Épingle ou désépingle un commentaire (le met en évidence) (Admin uniquement)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="blogComment",
     *         in="path",
     *         description="ID du commentaire",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Statut d'épinglage modifié avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Commentaire épinglé"),
     *             @OA\Property(property="data", ref="#/components/schemas/BlogComment")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Commentaire non trouvé",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Accès non autorisé",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     * 
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
     * @OA\Get(
     *     path="/api/admin/blog/comments/statistics",
     *     tags={"Admin", "Blog", "Comments"},
     *     summary="Statistiques des commentaires",
     *     description="Récupère les statistiques complètes des commentaires de blog (Admin uniquement)",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Statistiques récupérées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="total_comments", type="integer", example=1247, description="Total des commentaires"),
     *                 @OA\Property(property="pending_comments", type="integer", example=23, description="Commentaires en attente"),
     *                 @OA\Property(property="approved_comments", type="integer", example=1156, description="Commentaires approuvés"),
     *                 @OA\Property(property="rejected_comments", type="integer", example=45, description="Commentaires rejetés"),
     *                 @OA\Property(property="spam_comments", type="integer", example=23, description="Commentaires spam"),
     *                 @OA\Property(property="reported_comments", type="integer", example=12, description="Commentaires signalés"),
     *                 @OA\Property(property="guest_comments", type="integer", example=589, description="Commentaires d'invités"),
     *                 @OA\Property(property="user_comments", type="integer", example=658, description="Commentaires d'utilisateurs"),
     *                 @OA\Property(property="most_liked", ref="#/components/schemas/BlogComment", description="Commentaire le plus liké"),
     *                 @OA\Property(property="most_replied", ref="#/components/schemas/BlogComment", description="Commentaire avec le plus de réponses"),
     *                 @OA\Property(property="recent_comments", type="array", @OA\Items(ref="#/components/schemas/BlogComment"), description="Commentaires récents"),
     *                 @OA\Property(property="comments_by_day", type="array", @OA\Items(type="object", @OA\Property(property="date", type="string", format="date"), @OA\Property(property="count", type="integer")), description="Commentaires par jour (7 derniers jours)")
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
