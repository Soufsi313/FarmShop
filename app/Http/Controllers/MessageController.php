<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\VisitorMessageReply;
use App\Mail\VisitorContactConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    /**
     * Afficher la liste des messages de l'utilisateur
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->messages()->with('sender')->latest();

        // Filtres
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->has('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        if ($request->has('important') && $request->important === 'true') {
            $query->where('is_important', true);
        }

        $messages = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $messages,
            'unread_count' => $user->getUnreadMessagesCount(),
            'message' => 'Messages récupérés avec succès'
        ]);
    }

    /**
     * Afficher un message spécifique
     */
    public function show(Message $message)
    {
        // Vérifier que le message appartient à l'utilisateur connecté
        if ($message->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé'
            ], 403);
        }

        // Marquer comme lu si ce n'est pas déjà fait
        if ($message->isUnread()) {
            $message->markAsRead();
        }

        return response()->json([
            'success' => true,
            'data' => $message->load('sender'),
            'message' => 'Message récupéré avec succès'
        ]);
    }

    /**
     * Marquer un message comme lu
     */
    public function markAsRead(Message $message)
    {
        if ($message->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé'
            ], 403);
        }

        $message->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Message marqué comme lu'
        ]);
    }

    /**
     * Marquer un message comme non lu
     */
    public function markAsUnread(Message $message)
    {
        if ($message->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé'
            ], 403);
        }

        $message->markAsUnread();

        return response()->json([
            'success' => true,
            'message' => 'Message marqué comme non lu'
        ]);
    }

    /**
     * Archiver un message
     */
    public function archive(Message $message)
    {
        if ($message->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé'
            ], 403);
        }

        $message->archive();

        return response()->json([
            'success' => true,
            'message' => 'Message archivé'
        ]);
    }

    /**
     * Désarchiver un message
     */
    public function unarchive(Message $message)
    {
        if ($message->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé'
            ], 403);
        }

        $message->unarchive();

        return response()->json([
            'success' => true,
            'message' => 'Message désarchivé'
        ]);
    }

    /**
     * Basculer l'importance d'un message
     */
    public function toggleImportant(Message $message)
    {
        if ($message->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé'
            ], 403);
        }

        $isImportant = $message->toggleImportant();

        return response()->json([
            'success' => true,
            'is_important' => $isImportant,
            'message' => $isImportant ? 'Message marqué comme important' : 'Message retiré des importants'
        ]);
    }

    /**
     * Supprimer un message (soft delete)
     */
    public function destroy(Message $message)
    {
        if ($message->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé'
            ], 403);
        }

        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Message supprimé'
        ]);
    }

    /**
     * Marquer tous les messages comme lus
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        $count = $user->messages()->unread()->update([
            'status' => 'read',
            'read_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'marked_count' => $count,
            'message' => "Tous les messages ont été marqués comme lus ({$count} messages)"
        ]);
    }

    /**
     * Obtenir les statistiques des messages
     */
    public function getStats()
    {
        $user = Auth::user();
        $messages = $user->messages();

        $stats = [
            'total' => $messages->count(),
            'unread' => $messages->unread()->count(),
            'read' => $messages->read()->count(),
            'archived' => $messages->archived()->count(),
            'important' => $messages->important()->count(),
            'by_type' => [
                'system' => $messages->byType('system')->count(),
                'admin' => $messages->byType('admin')->count(),
                'order' => $messages->byType('order')->count(),
                'notification' => $messages->byType('notification')->count(),
            ],
            'by_priority' => [
                'low' => $messages->byPriority('low')->count(),
                'normal' => $messages->byPriority('normal')->count(),
                'high' => $messages->byPriority('high')->count(),
                'urgent' => $messages->byPriority('urgent')->count(),
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Statistiques des messages récupérées'
        ]);
    }

    /**
     * Actions en lot
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'message_ids' => 'required|array',
            'message_ids.*' => 'exists:messages,id',
            'action' => 'required|in:mark_read,mark_unread,archive,unarchive,delete'
        ]);

        $user = Auth::user();
        $messages = Message::whereIn('id', $validated['message_ids'])
            ->where('user_id', $user->id)
            ->get();

        if ($messages->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun message trouvé'
            ], 404);
        }

        $count = 0;
        foreach ($messages as $message) {
            switch ($validated['action']) {
                case 'mark_read':
                    $message->markAsRead();
                    $count++;
                    break;
                case 'mark_unread':
                    $message->markAsUnread();
                    $count++;
                    break;
                case 'archive':
                    $message->archive();
                    $count++;
                    break;
                case 'unarchive':
                    $message->unarchive();
                    $count++;
                    break;
                case 'delete':
                    $message->delete();
                    $count++;
                    break;
            }
        }

        return response()->json([
            'success' => true,
            'processed_count' => $count,
            'message' => "Action appliquée à {$count} messages"
        ]);
    }

    /**
     * Obtenir les alertes de stock pour l'admin
     */
    public function getStockAlerts(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé'
            ], 403);
        }

        $query = Message::where('type', 'stock_alert')
                        ->where('user_id', $user->id)
                        ->with('sender')
                        ->latest();

        // Filtrer par type d'alerte
        if ($request->has('alert_type')) {
            $query->where('metadata->alert_type', $request->alert_type);
        }

        // Filtrer par statut
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filtrer par priorité
        if ($request->has('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        $alerts = $query->paginate($request->get('per_page', 15));

        // Statistiques des alertes
        $alertStats = [
            'total' => Message::where('type', 'stock_alert')->where('user_id', $user->id)->count(),
            'unread' => Message::where('type', 'stock_alert')->where('user_id', $user->id)->where('status', 'unread')->count(),
            'by_type' => Message::where('type', 'stock_alert')
                               ->where('user_id', $user->id)
                               ->selectRaw('JSON_EXTRACT(metadata, "$.alert_type") as alert_type, COUNT(*) as count')
                               ->groupBy('alert_type')
                               ->pluck('count', 'alert_type'),
            'by_priority' => Message::where('type', 'stock_alert')
                                   ->where('user_id', $user->id)
                                   ->selectRaw('priority, COUNT(*) as count')
                                   ->groupBy('priority')
                                   ->pluck('count', 'priority')
        ];

        return response()->json([
            'success' => true,
            'data' => $alerts,
            'statistics' => $alertStats,
            'message' => 'Alertes de stock récupérées'
        ]);
    }

    /**
     * Marquer toutes les alertes de stock comme lues
     */
    public function markStockAlertsAsRead()
    {
        $user = Auth::user();
        
        if (!$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé'
            ], 403);
        }

        $updated = Message::where('type', 'stock_alert')
                         ->where('user_id', $user->id)
                         ->where('status', 'unread')
                         ->update([
                             'status' => 'read',
                             'read_at' => now()
                         ]);

        return response()->json([
            'success' => true,
            'message' => "{$updated} alerte(s) marquée(s) comme lue(s)",
            'updated_count' => $updated
        ]);
    }

    /**
     * Obtenir le résumé des alertes non lues pour le badge
     */
    public function getUnreadAlertsCount()
    {
        $user = Auth::user();
        
        if (!$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé'
            ], 403);
        }

        $counts = [
            'stock_alerts' => Message::where('type', 'stock_alert')
                                    ->where('user_id', $user->id)
                                    ->where('status', 'unread')
                                    ->count(),
            'urgent_alerts' => Message::where('type', 'stock_alert')
                                     ->where('user_id', $user->id)
                                     ->where('status', 'unread')
                                     ->where('priority', 'high')
                                     ->count(),
            'total_unread' => Message::where('user_id', $user->id)
                                    ->where('status', 'unread')
                                    ->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $counts
        ]);
    }

    /**
     * Répondre à un message de visiteur (Admin seulement)
     */
    public function replyToVisitor(Request $request, Message $message)
    {
        $user = Auth::user();
        
        // Vérifier que c'est un admin
        if (!$user || !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé'
            ], 403);
        }

        // Vérifier que c'est un message de visiteur (type contact)
        if ($message->type !== 'contact' || !isset($message->metadata['sender_email'])) {
            return response()->json([
                'success' => false,
                'message' => 'Ce message ne provient pas d\'un visiteur ou n\'a pas d\'email'
            ], 400);
        }

        $request->validate([
            'reply_content' => 'required|string|min:10|max:5000',
            'mark_as_resolved' => 'boolean'
        ]);

        try {
            // Créer la réponse dans la table messages
            $replyMessage = Message::create([
                'user_id' => $message->user_id, // Admin qui répond
                'sender_id' => $user->id,
                'type' => 'admin_reply',
                'subject' => 'Re: ' . $message->subject,
                'content' => $request->reply_content,
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
                $request->reply_content, 
                $adminName
            ));

            // Marquer le message original comme traité si demandé
            if ($request->mark_as_resolved) {
                $message->update([
                    'status' => 'read',
                    'metadata' => array_merge($message->metadata ?? [], [
                        'resolved_at' => now()->toISOString(),
                        'resolved_by' => $user->id,
                        'admin_response_sent' => true
                    ])
                ]);
            }

            // Log de l'action
            Log::info('Réponse envoyée à un visiteur', [
                'admin_id' => $user->id,
                'original_message_id' => $message->id,
                'visitor_email' => $visitorEmail,
                'reply_message_id' => $replyMessage->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Réponse envoyée avec succès à ' . $visitorEmail,
                'data' => [
                    'reply_message_id' => $replyMessage->id,
                    'email_sent_to' => $visitorEmail,
                    'original_message_status' => $message->fresh()->status
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de la réponse au visiteur', [
                'error' => $e->getMessage(),
                'message_id' => $message->id,
                'admin_id' => $user->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de la réponse: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les messages de visiteurs pour l'admin
     */
    public function getVisitorMessages(Request $request)
    {
        $user = Auth::user();
        
        if (!$user || !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé'
            ], 403);
        }

        $query = Message::where('type', 'contact')
                       ->whereNotNull('metadata->sender_email')
                       ->with('sender')
                       ->latest();

        // Filtres
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        $messages = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $messages,
            'statistics' => [
                'total' => Message::where('type', 'contact')->count(),
                'pending' => Message::where('type', 'contact')->where('status', 'unread')->count(),
                'resolved' => Message::where('type', 'contact')->where('status', 'resolved')->count(),
                'urgent' => Message::where('type', 'contact')->where('priority', 'high')->count()
            ]
        ]);
    }

    /**
     * Créer un message de contact pour un visiteur (Route publique)
     */
    public function createVisitorMessage(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:2000',
            'reason' => 'required|in:mon_profil,mes_achats,mes_locations,mes_donnees,support_technique,partenariat,autre',
            'priority' => 'nullable|in:low,normal,high,urgent'
        ]);

        try {
            // Créer le message pour l'admin (user_id = 1)
            $adminMessage = Message::create([
                'user_id' => 1, // Admin user ID
                'sender_id' => null, // Pas d'utilisateur connecté pour un visiteur
                'type' => 'contact',
                'subject' => $request->subject,
                'content' => $request->message,
                'status' => 'unread',
                'priority' => $request->priority ?? 'normal',
                'metadata' => [
                    'sender_name' => $request->name,
                    'sender_email' => $request->email,
                    'sender_phone' => $request->phone,
                    'contact_reason' => $request->reason,
                    'visitor_message' => true,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]
            ]);

            // Référence pour la réponse
            $reference = 'MSG-' . str_pad($adminMessage->id, 6, '0', STR_PAD_LEFT);
            
            // Note: Message de confirmation désactivé temporairement
            // pour éviter les problèmes de base de données

            // Log de l'action
            Log::info('Nouveau message de contact de visiteur', [
                'visitor_email' => $request->email,
                'visitor_name' => $request->name,
                'subject' => $request->subject,
                'reason' => $request->reason,
                'message_id' => $adminMessage->id,
                'ip' => $request->ip()
            ]);

            // Envoyer l'email de confirmation au visiteur
            try {
                Mail::to($request->email)->send(new VisitorContactConfirmation([
                    'visitor_name' => $request->name,
                    'visitor_email' => $request->email,
                    'subject' => $request->subject,
                    'message' => $request->message,
                    'reason' => $request->reason,
                    'priority' => $request->priority ?? 'normal',
                    'reference' => $reference,
                    'message_id' => $adminMessage->id
                ]));

                Log::info('Email de confirmation envoyé au visiteur', [
                    'visitor_email' => $request->email,
                    'reference' => $reference
                ]);

            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi de l\'email de confirmation', [
                    'error' => $e->getMessage(),
                    'visitor_email' => $request->email,
                    'reference' => $reference
                ]);
                // On continue même si l'email n'a pas pu être envoyé
            }

            return response()->json([
                'success' => true,
                'message' => 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.',
                'data' => [
                    'message_id' => $adminMessage->id,
                    'reference' => $reference,
                    'estimated_response_time' => '24-48 heures'
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du message de contact visiteur', [
                'error' => $e->getMessage(),
                'visitor_email' => $request->email,
                'visitor_name' => $request->name
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer.'
            ], 500);
        }
    }
}
