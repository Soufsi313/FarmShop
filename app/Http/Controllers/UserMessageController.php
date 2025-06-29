<?php

namespace App\Http\Controllers;

use App\Models\AdminMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMessageController extends Controller
{
    /**
     * Afficher les messages de l'utilisateur
     */
    public function index()
    {
        $user = Auth::user();
        
        $messages = AdminMessage::where('user_id', $user->id)
            ->with(['replies'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('profile.messages', compact('messages'));
    }

    /**
     * Afficher un message spécifique
     */
    public function show(AdminMessage $message)
    {
        $user = Auth::user();
        
        // Vérifier que le message appartient à l'utilisateur
        if ($message->user_id !== $user->id) {
            abort(403);
        }
        
        // Marquer le message comme lu par l'utilisateur
        $message->update(['is_read_by_user' => true]);
        
        $message->load(['replies.user']);
        
        return view('profile.message-detail', compact('message'));
    }
}
