<?php

namespace App\Policies;

use App\Models\AdminMessage;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminMessagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AdminMessage $adminMessage)
    {
        // L'admin peut voir tous les messages, l'utilisateur seulement ses propres messages
        return $user->hasRole('admin') || $user->id === $adminMessage->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        // Tous les utilisateurs authentifiés peuvent créer des messages
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AdminMessage $adminMessage)
    {
        // Seul l'admin peut modifier le statut des messages
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can reply to the model.
     */
    public function reply(User $user, AdminMessage $adminMessage)
    {
        // L'admin peut répondre à tous les messages, l'utilisateur à ses propres messages
        return $user->hasRole('admin') || $user->id === $adminMessage->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AdminMessage $adminMessage)
    {
        // Seul l'admin peut supprimer les messages
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AdminMessage $adminMessage)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AdminMessage $adminMessage)
    {
        return $user->hasRole('admin');
    }
}
