<?php

namespace App\Services;

use App\Repositories\FavoriteCarRepository;
use App\Models\FavoriteCar;
use Illuminate\Support\Collection;

class FavoriteCarService
{
    public function __construct(
        protected FavoriteCarRepository $favorites
    ) {}

    /**
     * A felhasználó saját kedvenc autóinak listája.
     *
     * @return Collection<int, FavoriteCar>
     */
    public function forUser(int $userId)
    {
        return $this->favorites->getByUser($userId);
    }

    /**
     * Új kedvenc autó létrehozása.
     *
     * @param array<string, mixed> $data
     * @return FavoriteCar
     */
    public function create(array $data)
    {
        $data['user_id'] = auth()->id();
        return $this->favorites->create($data);
    }

    /**
     * Kedvenc autó frissítése.
     *
     * @param int $id
     * @param array<string, mixed> $data
     * @return FavoriteCar
     */
    public function update(int $id, array $data)
    {
        return $this->favorites->update($id, $data);
    }

    /**
     * Kedvenc autó törlése.
     *
     * @return bool|null
     */
    public function delete(int $id)
    {
        return $this->favorites->delete($id);
    }
}
