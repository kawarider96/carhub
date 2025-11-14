<?php

namespace App\Policies;

use App\Models\FavoriteCar;
use App\Models\User;

class FavoriteCarPolicy
{
    public function viewAny(User $user): bool
    {
        // Csak admin listázhatja az összes kedvenc autót
        return $user->isAdmin();
    }

    public function view(User $user, FavoriteCar $favoriteCar): bool
    {
        return $user->isAdmin() || $favoriteCar->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        // Csak aktív felhasználó hozhat létre kedvenc autót
        return $user->is_active === true;
    }

    public function update(User $user, FavoriteCar $favoriteCar): bool
    {
        // Admin nem módosíthat más tulajdonában lévő kedvenc autót
        return $favoriteCar->user_id === $user->id;
    }

    public function delete(User $user, FavoriteCar $favoriteCar): bool
    {
        // Admin nem módosíthat (törölhet) user tulajdont
        return $favoriteCar->user_id === $user->id;
    }

    public function restore(User $user, FavoriteCar $favoriteCar): bool
    {
        // Nem engedélyezett művelet
        return false;
    }

    public function forceDelete(User $user, FavoriteCar $favoriteCar): bool
    {
        return false;
    }
}

