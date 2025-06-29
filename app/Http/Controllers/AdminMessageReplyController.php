<?php

namespace App\Http\Controllers;

use App\Models\AdminMessage;
use App\Models\AdminMessageReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMessageReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher les réponses d'un message spécifique (API)
     */
    public function index(AdminMessage $adminMessage)
    {
        $this->authorize('view', $adminMessage);

        $replies = $adminMessage->replies()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'replies' => $replies
        ]);
    }

    /**
     * Créer une nouvelle réponse
     */
    public function store(Request $request, AdminMessage $adminMessage)
    {
        $this->authorize('reply', $adminMessage);

        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $reply = AdminMessageReply::create([
            'admin_message_id' => $adminMessage->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_admin_reply' => Auth::user()->hasRole('admin')
        ]);

        // Si c'est l'admin qui répond, marquer le message comme en cours
        if (Auth::user()->hasRole('admin')) {
            $adminMessage->update(['status' => 'in_progress']);
        }

        $reply->load('user');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Réponse ajoutée avec succès.',
                'reply' => $reply
            ]);
        }

        return redirect()->back()->with('success', 'Réponse ajoutée avec succès.');
    }

    /**
     * Afficher une réponse spécifique
     */
    public function show(AdminMessage $adminMessage, AdminMessageReply $reply)
    {
        $this->authorize('view', $adminMessage);

        if ($reply->admin_message_id !== $adminMessage->id) {
            abort(404);
        }

        return response()->json([
            'success' => true,
            'reply' => $reply->load('user')
        ]);
    }

    /**
     * Modifier une réponse (seulement par son auteur ou admin)
     */
    public function update(Request $request, AdminMessage $adminMessage, AdminMessageReply $reply)
    {
        $this->authorize('view', $adminMessage);

        // Seul l'auteur de la réponse ou un admin peut la modifier
        if ($reply->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403, 'Vous ne pouvez pas modifier cette réponse.');
        }

        if ($reply->admin_message_id !== $adminMessage->id) {
            abort(404);
        }

        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $reply->update([
            'message' => $request->message
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Réponse modifiée avec succès.',
                'reply' => $reply->load('user')
            ]);
        }

        return redirect()->back()->with('success', 'Réponse modifiée avec succès.');
    }

    /**
     * Supprimer une réponse (seulement par admin)
     */
    public function destroy(AdminMessage $adminMessage, AdminMessageReply $reply)
    {
        $this->authorize('view', $adminMessage);

        // Seul un admin peut supprimer les réponses
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Seul un administrateur peut supprimer les réponses.');
        }

        if ($reply->admin_message_id !== $adminMessage->id) {
            abort(404);
        }

        $reply->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Réponse supprimée avec succès.'
            ]);
        }

        return redirect()->back()->with('success', 'Réponse supprimée avec succès.');
    }

    /**
     * Marquer toutes les réponses d'un message comme lues (pour l'utilisateur)
     */
    public function markAsRead(AdminMessage $adminMessage)
    {
        $this->authorize('view', $adminMessage);

        // Marquer le message principal comme lu si ce n'est pas déjà fait
        if (!$adminMessage->read_at && Auth::user()->hasRole('admin')) {
            $adminMessage->update(['read_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Messages marqués comme lus.'
        ]);
    }
}
