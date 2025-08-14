<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailVerificationController extends Controller
{
    /**
     * Afficher la page de vérification d'email
     */
    public function show()
    {
        return view('auth.verify-email');
    }

    /**
     * Traiter la vérification d'email via le lien cliqué
     */
    public function verify(EmailVerificationRequest $request)
    {
        // Marquer l'email comme vérifié
        $request->fulfill();
        
        Log::info('Email vérifié avec succès', [
            'user_id' => $request->user()->id,
            'email' => $request->user()->email,
            'timestamp' => now()
        ]);

        // Afficher la page de confirmation
        return view('auth.email-verified');
    }

    /**
     * Renvoyer l'email de vérification
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return back()->with('message', 'Votre email est déjà vérifié !');
        }

        $request->user()->sendEmailVerificationNotification();

        Log::info('Email de vérification renvoyé', [
            'user_id' => $request->user()->id,
            'email' => $request->user()->email,
            'timestamp' => now()
        ]);

        return back()->with('message', 'Lien de vérification envoyé !');
    }
}
