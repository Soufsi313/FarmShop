<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
