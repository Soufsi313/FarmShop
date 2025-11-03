<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AccountCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Afficher le formulaire d'inscription
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Traiter l'inscription d'un nouvel utilisateur
     */
    public function register(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'terms' => 'required|accepted',
        ], [
            'username.required' => 'Le nom d\'utilisateur est obligatoire.',
            'username.unique' => 'Ce nom d\'utilisateur est déjà utilisé.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'terms.required' => 'Vous devez accepter les conditions d\'utilisation.',
            'terms.accepted' => 'Vous devez accepter les conditions d\'utilisation.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }

        // Créer l'utilisateur
        $user = User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'User', // Par défaut tous les nouveaux comptes sont des utilisateurs standards
            'newsletter_subscribed' => $request->has('newsletter'),
        ]);

        // Envoyer l'email de bienvenue (confirmation de création de compte)
        $user->notify(new AccountCreatedNotification());

        // Envoyer l'email de vérification (synchrone pour éviter les problèmes de queue)
        $user->sendEmailVerificationNotification();

        // Marquer qu'un changement d'authentification pourrait avoir lieu pour la synchronisation des cookies
        session()->put('auth_status_changed', true);

        // Stocker l'email de l'utilisateur en session pour l'afficher sur la page de confirmation
        session()->flash('user_email', $user->email);

        return redirect()->route('register.success');
    }

    /**
     * Afficher la page de confirmation de création de compte
     */
    public function success()
    {
        // Vérifier que la session contient bien les données (éviter accès direct)
        if (!session()->has('user_email')) {
            return redirect()->route('login');
        }

        return view('auth.register-success');
    }
}
