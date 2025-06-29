<?php

namespace App\Policies;

use App\Models\AdminMessage;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminMessagePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, AdminMessage $adminMessage)
    {
        return $user->hasRole('admin') || $user->id === $adminMessage->user_id;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, AdminMessage $adminMessage)
    {
        return $user->hasRole('admin');
    }

    public function reply(User $user, AdminMessage $adminMessage)
    {
        return $user->hasRole('admin') || $user->id === $adminMessage->user_id;
    }

    public function delete(User $user, AdminMessage $adminMessage)
    {
        return $user->hasRole('admin');
    }

    public function restore(User $user, AdminMessage $adminMessage)
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, AdminMessage $adminMessage)
    {
        return $user->hasRole('admin');
    }
}
