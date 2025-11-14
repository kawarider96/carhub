<?php

namespace App\Policies;

use App\Models\CarBrand;
use App\Models\User;

class CarBrandPolicy
{
    public function viewAny(User $user): bool
    {
        // Csak admin listázhatja az összes márkát
        return $user->isAdmin();
    }

    public function view(?User $user, CarBrand $brand): bool
    {
        // bárki megtekintheti a márkát
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, CarBrand $brand): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, CarBrand $brand): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, CarBrand $brand): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, CarBrand $brand): bool
    {
        return false;
    }
}

