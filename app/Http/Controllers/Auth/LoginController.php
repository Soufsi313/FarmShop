<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Afficher le formulaire de connexion
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Traiter la tentative de connexion
     */
    public function login(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        // Tentative de connexion
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Marquer qu'un changement d'authentification a eu lieu APRÈS la régénération
            session(['auth_status_changed' => true]);
            
            // Redirection vers la page demandée ou la page d'accueil
            $redirectTo = $request->input('redirect_to', '/');
            
            return redirect()->intended($redirectTo)->with('success', 'Connexion réussie ! Bienvenue sur FarmShop.');
        }

        // Échec de la connexion
        return redirect()->back()
            ->withErrors(['email' => 'Les identifiants fournis ne correspondent à aucun compte.'])
            ->withInput($request->only('email'));
    }

    /**
     * Déconnecter l'utilisateur
     */
    public function logout(Request $request)
    {
        // Marquer qu'un changement d'authentification a eu lieu pour la synchronisation des cookies
        $request->session()->put('auth_status_changed', true);
        
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Vous avez été déconnecté avec succès.');
    }
}
