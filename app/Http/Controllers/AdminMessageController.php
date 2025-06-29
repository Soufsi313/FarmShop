<?php

namespace App\Http\Controllers;

use App\Models\AdminMessage;
use App\Models\AdminMessageReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Envoyer un message à l'admin (depuis le profil utilisateur)
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        $adminMessage = AdminMessage::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Votre message a été envoyé à l\'administrateur.'
        ]);
    }

    /**
     * Liste des messages pour l'admin (avec pagination)
     */
    public function index()
    {
        $this->authorize('viewAny', AdminMessage::class);

        $messages = AdminMessage::with(['user', 'replies'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.messages.index', compact('messages'));
    }

    /**
     * Afficher un message spécifique avec ses réponses
     */
    public function show(AdminMessage $adminMessage)
    {
        $this->authorize('view', $adminMessage);

        // Marquer comme lu
        if (!$adminMessage->read_at) {
            $adminMessage->update(['read_at' => now()]);
        }

        $adminMessage->load(['user', 'replies.user']);

        return view('admin.messages.show', compact('adminMessage'));
    }

    /**
     * Répondre à un message
     */
    public function reply(Request $request, AdminMessage $adminMessage)
    {
        $this->authorize('reply', $adminMessage);

        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        AdminMessageReply::create([
            'admin_message_id' => $adminMessage->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_admin_reply' => Auth::user()->hasRole('admin')
        ]);

        // Marquer le message comme traité si c'est l'admin qui répond
        if (Auth::user()->hasRole('admin')) {
            $adminMessage->update([
                'status' => 'resolved',
                'resolved_at' => now()
            ]);
        }

        return redirect()->back()->with('success', 'Réponse envoyée avec succès.');
    }

    /**
     * Marquer un message comme résolu
     */
    public function markAsResolved(AdminMessage $adminMessage)
    {
        $this->authorize('update', $adminMessage);

        $adminMessage->update([
            'status' => 'resolved',
            'resolved_at' => now()
        ]);

        return redirect()->back()->with('success', 'Message marqué comme résolu.');
    }

    /**
     * Supprimer un message
     */
    public function destroy(AdminMessage $adminMessage)
    {
        $this->authorize('delete', $adminMessage);

        $adminMessage->delete();

        return redirect()->route('admin.messages.index')
            ->with('success', 'Message supprimé avec succès.');
    }
}
