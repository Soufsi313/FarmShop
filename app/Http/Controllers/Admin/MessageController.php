<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Mail\VisitorMessageReply;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    /**
     * Afficher tous les messages pour l'admin
     */
    public function index(Request $request)
    {
        // Vérifier que l'utilisateur est admin
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }
            abort(403, 'Accès refusé. Privilèges administrateur requis.');
        }

        $query = Message::with(['user', 'sender']);

        // Filtres
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }

        if ($request->has('priority') && !empty($request->priority)) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('is_important') && $request->is_important !== '') {
            $query->where('is_important', (bool) $request->is_important);
        }

        // Filtre par auteur
        if ($request->has('author') && !empty($request->author)) {
            $author = $request->author;
            $query->where(function($q) use ($author) {
                $q->whereHas('sender', function($userQuery) use ($author) {
                    $userQuery->where('name', 'like', "%{$author}%")
                             ->orWhere('email', 'like', "%{$author}%");
                })->orWhere('metadata->sender_name', 'like', "%{$author}%")
                  ->orWhere('metadata->sender_email', 'like', "%{$author}%");
            });
        }

        // Filtre par date
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filtre par raison/motif
        if ($request->has('reason') && !empty($request->reason)) {
            $query->where('metadata->contact_reason', $request->reason);
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Nombre d'éléments par page (personnalisable)
        $perPage = $request->get('per_page', 15);
        // Limiter les valeurs acceptées pour éviter les abus
        $allowedPerPage = [10, 15, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 15;
        }

        // Si c'est une requête API, retourner JSON avec pagination
        if ($request->expectsJson()) {
            $messages = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $messages,
                'statistics' => $this->getMessageStatistics()
            ]);
        }

        // Sinon, retourner la vue pour l'interface web
        $messages = $query->paginate($perPage);
        $statistics = $this->getMessageStatistics();
        return view('admin.messages.index', compact('messages', 'statistics'));
    }

    /**
     * Afficher un message spécifique (Admin)
     */
    public function show(Request $request, Message $message)
    {
        // Vérifier que l'utilisateur est admin
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }
            abort(403, 'Accès refusé. Privilèges administrateur requis.');
        }

        $message->load(['user', 'sender']);

        // Marquer comme lu si c'est un message pour l'admin
        if ($message->user_id === auth()->id() && !$message->read_at) {
            $message->update(['read_at' => now()]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $message
            ]);
        }

        return view('admin.messages.show', compact('message'));
    }

    /**
     * Répondre à un message (Admin)
     */
    public function respond(Request $request, Message $message): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur est admin
            if (!auth()->user() || !auth()->user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            // Validation
            $validator = Validator::make($request->all(), [
                'response' => 'required|string|min:10|max:5000',
            ], [
                'response.required' => 'La réponse est obligatoire',
                'response.min' => 'La réponse doit contenir au moins 10 caractères',
                'response.max' => 'La réponse ne peut pas dépasser 5000 caractères'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données de validation incorrectes',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Si c'est un message de visiteur (type contact), utiliser le système d'email
            if ($message->type === 'contact' && isset($message->metadata['sender_email'])) {
                return $this->replyToVisitorFromAdmin($request, $message);
            }

            // Sinon, traitement normal pour les utilisateurs connectés
            // Créer un nouveau message de réponse pour l'utilisateur
            $responseMessage = Message::create([
                'user_id' => $message->user_id ?: $this->getUserIdFromMetadata($message),
                'sender_id' => auth()->id(),
                'type' => 'admin_response',
                'subject' => 'Réponse : ' . $message->subject,
                'content' => "Réponse à votre message :\n\n" . $request->response . "\n\n--- Message original ---\n" . $message->content,
                'status' => 'unread',
                'priority' => $message->priority,
                'is_important' => true,
                'metadata' => [
                    'original_message_id' => $message->id,
                    'admin_response' => true,
                    'response_date' => now()->toISOString()
                ]
            ]);

            // Marquer le message original comme traité
            $message->update([
                'read_at' => now(),
                'status' => 'read',
                'metadata' => array_merge($message->metadata ?? [], [
                    'admin_responded' => true,
                    'response_message_id' => $responseMessage->id,
                    'responded_at' => now()->toISOString(),
                    'responded_by' => auth()->id()
                ])
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Réponse envoyée avec succès',
                'data' => [
                    'original_message' => $message->fresh(),
                    'response_message' => $responseMessage
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la réponse au message: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de la réponse'
            ], 500);
        }
    }

    /**
     * Marquer un message comme lu
     */
    public function markAsRead(Request $request, Message $message): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur est admin
            if (!auth()->user() || !auth()->user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            // Marquer comme lu
            $message->update([
                'read_at' => now(),
                'status' => 'read'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Message marqué comme lu'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors du marquage comme lu: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du marquage comme lu'
            ], 500);
        }
    }

    /**
     * Archiver un message
     */
    public function archive(Request $request, Message $message): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur est admin
            if (!auth()->user() || !auth()->user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            // Archiver le message
            $message->update([
                'status' => 'archived',
                'archived_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Message archivé avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'archivage: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'archivage'
            ], 500);
        }
    }

    /**
     * Obtenir les statistiques des messages
     */
    public function statistics(): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur est admin
            if (!auth()->user() || !auth()->user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            $statistics = $this->getMessageStatistics();

            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des statistiques: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques'
            ], 500);
        }
    }

    /**
     * Supprimer un message (Admin)
     */
    public function destroy(Message $message): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur est admin
            if (!auth()->user() || !auth()->user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            $message->delete();

            return response()->json([
                'success' => true,
                'message' => 'Message supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du message: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du message'
            ], 500);
        }
    }

    /**
     * Calculer les statistiques des messages
     */
    private function getMessageStatistics(): array
    {
        $total = Message::count();
        $unread = Message::where('status', 'unread')->count();
        $important = Message::where('is_important', true)->count();
        $contactMessages = Message::where('type', 'contact')->count();
        $adminResponses = Message::where('type', 'admin_response')->count();

        $byPriority = [];
        $priorities = ['low', 'normal', 'high', 'urgent'];
        foreach ($priorities as $priority) {
            $byPriority[$priority] = Message::where('priority', $priority)->count();
        }

        $byType = [];
        $types = Message::select('type')->distinct()->pluck('type');
        foreach ($types as $type) {
            $byType[$type] = Message::where('type', $type)->count();
        }

        return [
            'total' => $total,
            'unread' => $unread,
            'read' => $total - $unread,
            'important' => $important,
            'contact_messages' => $contactMessages,
            'admin_responses' => $adminResponses,
            'by_priority' => $byPriority,
            'by_type' => $byType,
            'recent_contacts' => Message::where('type', 'contact')
                                     ->where('created_at', '>=', now()->subDays(7))
                                     ->count(),
            'response_rate' => $contactMessages > 0 ? round(($adminResponses / $contactMessages) * 100, 2) : 0
        ];
    }

    /**
     * Récupérer l'ID utilisateur depuis les métadonnées si pas d'utilisateur connecté
     */
    private function getUserIdFromMetadata(Message $message): ?int
    {
        if (!$message->metadata) {
            return null;
        }

        $metadata = is_string($message->metadata) ? json_decode($message->metadata, true) : $message->metadata;
        
        if (isset($metadata['sender_email'])) {
            $user = User::where('email', $metadata['sender_email'])->first();
            return $user ? $user->id : null;
        }

        return null;
    }

    /**
     * Répondre à un message de visiteur par email depuis l'interface admin
     */
    private function replyToVisitorFromAdmin(Request $request, Message $message): JsonResponse
    {
        try {
            $user = auth()->user();
            
            // Créer la réponse dans la table messages
            $replyMessage = Message::create([
                'user_id' => $message->user_id, // Admin qui répond
                'sender_id' => $user->id,
                'type' => 'admin_reply',
                'subject' => 'Re: ' . $message->subject,
                'content' => $request->response,
                'status' => 'read',
                'priority' => $message->priority,
                'metadata' => [
                    'original_message_id' => $message->id,
                    'visitor_email' => $message->metadata['sender_email'],
                    'visitor_name' => $message->metadata['sender_name'] ?? 'Visiteur',
                    'reply_type' => 'visitor_email_response'
                ]
            ]);

            // Envoyer l'email au visiteur
            $visitorEmail = $message->metadata['sender_email'];
            $adminName = $user->first_name . ' ' . $user->last_name;
            
            Mail::to($visitorEmail)->send(new VisitorMessageReply(
                $message, 
                $request->response, 
                $adminName
            ));

            // Marquer le message original comme traité
            $message->update([
                'status' => 'read',
                'read_at' => now(),
                'metadata' => array_merge($message->metadata ?? [], [
                    'resolved_at' => now()->toISOString(),
                    'resolved_by' => $user->id,
                    'admin_response_sent' => true,
                    'admin_responded' => true,
                    'responded_at' => now()->toISOString(),
                    'responded_by' => $user->id
                ])
            ]);

            // Log de l'action
            Log::info('Réponse envoyée à un visiteur depuis l\'admin', [
                'admin_id' => $user->id,
                'original_message_id' => $message->id,
                'visitor_email' => $visitorEmail,
                'reply_message_id' => $replyMessage->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Réponse envoyée par email à ' . $visitorEmail,
                'data' => [
                    'reply_message_id' => $replyMessage->id,
                    'email_sent_to' => $visitorEmail,
                    'original_message_status' => $message->fresh()->status
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de la réponse au visiteur depuis l\'admin', [
                'error' => $e->getMessage(),
                'message_id' => $message->id,
                'admin_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de l\'email de réponse'
            ], 500);
        }
    }
}
