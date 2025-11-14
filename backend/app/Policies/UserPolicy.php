<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, User $target): bool
    {
        return $user->isAdmin() || $user->id === $target->id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, User $target): bool
    {
        return $user->isAdmin() || $user->id === $target->id;
    }

    public function delete(User $user, User $target): bool
    {
        if ($user->id === $target->id) {
            return false;
        }
        return $user->isAdmin();
    }

    public function restore(User $user, User $target): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, User $target): bool
    {
        return false;
    }

    public function lock(User $user, User $target): bool
    {
        return $user->isAdmin();
    }

    public function unlock(User $user, User $target): bool
    {
        return $user->isAdmin();
    }
}

