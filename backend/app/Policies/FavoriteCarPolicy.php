<?php

namespace App\Policies;

use App\Models\FavoriteCar;
use App\Models\User;

class FavoriteCarPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Csak a saját kedvenc autódat láthatod.
     */
    public function view(User $user, FavoriteCar $favoriteCar): bool
    {
        return $user->role === 'admin' || $favoriteCar->user_id === $user->id;
    }

    /**
     * Csak a sajátodat frissítheted.
     */
    public function update(User $user, FavoriteCar $favoriteCar): bool
    {
        return $favoriteCar->user_id === $user->id;
    }

    /**
     * Csak a sajátodat törölheted.
     */
    public function delete(User $user, FavoriteCar $favoriteCar): bool
    {
        return $favoriteCar->user_id === $user->id;
    }

    /**
     * Csak a saját kedvenc autódhoz tölthetsz fel képet.
     */
    public function create(User $user, FavoriteCar $favoriteCar): bool
    {
        return $favoriteCar->user_id === $user->id;
    }
}
