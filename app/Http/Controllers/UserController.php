<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Afficher la liste des utilisateurs (Admin seulement)
     */
    public function index()
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()?->isAdmin()) {
            abort(403, 'Accès refusé. Privilèges administrateur requis.');
        }

        $users = User::withTrashed()->paginate(15);
        
        return response()->json([
            'success' => true,
            'data' => $users,
            'message' => 'Liste des utilisateurs récupérée avec succès'
        ]);
    }

    /**
     * Créer un nouvel utilisateur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['sometimes', Rule::in(['Admin', 'User'])],
            'newsletter_subscribed' => 'boolean'
        ]);

        // Seuls les admins peuvent créer d'autres admins
        if (isset($validated['role']) && $validated['role'] === 'Admin') {
            if (!Auth::user()?->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Seuls les administrateurs peuvent créer des comptes admin'
                ], 403);
            }
        }

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = $validated['role'] ?? 'User';

        $user = User::create($validated);

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Utilisateur créé avec succès'
        ], 201);
    }

    /**
     * Afficher un utilisateur spécifique
     */
    public function show(User $user)
    {
        // Un utilisateur peut voir son propre profil, ou admin peut voir tous
        if (!Auth::user()?->isAdmin() && Auth::id() !== $user->id) {
            abort(403, 'Accès refusé.');
        }

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Utilisateur récupéré avec succès'
        ]);
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(Request $request, User $user)
    {
        // Un utilisateur peut modifier son propre profil, ou admin peut modifier tous
        if (!Auth::user()?->isAdmin() && Auth::id() !== $user->id) {
            abort(403, 'Accès refusé.');
        }

        $validated = $request->validate([
            'username' => ['sometimes', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'name' => 'sometimes|nullable|string|max:255',
            'email' => ['sometimes', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'sometimes|string|min:8|confirmed',
            'role' => ['sometimes', Rule::in(['Admin', 'User'])],
            'newsletter_subscribed' => 'sometimes|boolean'
        ]);

        // Seuls les admins peuvent modifier les rôles
        if (isset($validated['role']) && !Auth::user()?->isAdmin()) {
            unset($validated['role']);
        }

        // Hacher le mot de passe si fourni
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'data' => $user->fresh(),
            'message' => 'Utilisateur mis à jour avec succès'
        ]);
    }

    /**
     * Supprimer un utilisateur (soft delete)
     */
    public function destroy(User $user)
    {
        $currentUser = Auth::user();

        // Vérifier les permissions de suppression
        if (!$user->canBeDeletedBy($currentUser)) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'avez pas le droit de supprimer cet utilisateur'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur supprimé avec succès'
        ]);
    }

    /**
     * Restaurer un utilisateur supprimé (Admin seulement)
     */
    public function restore($id)
    {
        if (!Auth::user()?->isAdmin()) {
            abort(403, 'Accès refusé. Privilèges administrateur requis.');
        }

        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Utilisateur restauré avec succès'
        ]);
    }

    /**
     * S'abonner à la newsletter
     */
    public function subscribeNewsletter()
    {
        $user = Auth::user();
        $user->subscribeToNewsletter();

        return response()->json([
            'success' => true,
            'message' => 'Abonnement à la newsletter effectué avec succès'
        ]);
    }

    /**
     * Se désabonner de la newsletter
     */
    public function unsubscribeNewsletter()
    {
        $user = Auth::user();
        $user->unsubscribeFromNewsletter();

        return response()->json([
            'success' => true,
            'message' => 'Désabonnement de la newsletter effectué avec succès'
        ]);
    }

    /**
     * Télécharger les données utilisateur (RGPD)
     */
    public function downloadData()
    {
        $user = Auth::user();
        
        $userData = [
            'informations_personnelles' => [
                'username' => $user->username,
                'nom' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'abonne_newsletter' => $user->newsletter_subscribed,
                'date_creation' => $user->created_at,
                'derniere_modification' => $user->updated_at,
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $userData,
            'message' => 'Données utilisateur exportées avec succès'
        ]);
    }

    /**
     * Auto-suppression du compte utilisateur
     */
    public function selfDelete()
    {
        $user = Auth::user();

        // Un admin ne peut pas se supprimer lui-même
        if ($user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Un administrateur ne peut pas supprimer son propre compte'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Votre compte a été supprimé avec succès'
        ]);
    }
}
