<?php

namespace App\Policies;

use App\Models\Cookie;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CookiePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Cookie $cookie): bool
    {
        // Admin peut voir tous les cookies
        if ($user->hasRole('admin')) {
            return true;
        }

        // Un utilisateur peut voir ses propres cookies
        return $cookie->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Tout utilisateur peut créer ses préférences de cookies
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Cookie $cookie): bool
    {
        // Admin peut modifier tous les cookies
        if ($user->hasRole('admin')) {
            return true;
        }

        // Un utilisateur peut modifier ses propres cookies
        return $cookie->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Cookie $cookie): bool
    {
        // Seuls les admins peuvent supprimer des cookies
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Cookie $cookie): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Cookie $cookie): bool
    {
        return false;
    }
}
