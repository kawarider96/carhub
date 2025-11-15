<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function update(User $user, User $target)
    {
        return $user->id === $target->id;
    }
}

