<?php

namespace App\Repositories;

use App\Models\FavoriteCar;

class FavoriteCarRepository extends BaseRepository
{
    public function __construct(FavoriteCar $model)
    {
        parent::__construct($model);
    }

    public function getByUser(int $userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->with(['carModel', 'carModel.brand'])
            ->get();
    }
}
