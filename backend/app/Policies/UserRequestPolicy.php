<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserRequest;

class UserRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, UserRequest $request): bool
    {
        return $user->isAdmin() || $request->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        // Csak aktív felhasználó hozhat létre kérelmet (további tulajdonosi ellenőrzés a controllerben)
        return $user->is_active === true;
    }

    public function update(User $user, UserRequest $request): bool
    {
        // Állapot módosítása csak adminnak
        return $user->isAdmin();
    }

    public function delete(User $user, UserRequest $request): bool
    {
        return $user->isAdmin();
    }

    public function approve(User $user, UserRequest $request): bool
    {
        return $user->isAdmin();
    }

    public function reject(User $user, UserRequest $request): bool
    {
        return $user->isAdmin();
    }
}

