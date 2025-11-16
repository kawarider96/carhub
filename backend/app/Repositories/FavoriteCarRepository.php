<?php

namespace App\Repositories;

use App\Models\FavoriteCar;

/**
 * @extends BaseRepository<FavoriteCar>
 */
class FavoriteCarRepository extends BaseRepository
{
    public function __construct(FavoriteCar $model)
    {
        parent::__construct($model);
    }

    /**
     * Visszaadja a felhasználó összes kedvenc autóját.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection<int, FavoriteCar>
     */
    public function getByUser(int $userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->with(['carModel', 'carModel.brand'])
            ->get();
    }
}
