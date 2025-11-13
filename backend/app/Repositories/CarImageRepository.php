<?php

namespace App\Repositories;

use App\Models\CarImage;

class CarImageRepository extends BaseRepository
{
    public function __construct(CarImage $model)
    {
        parent::__construct($model);
    }

    public function getByFavoriteCar(int $favoriteCarId)
    {
        return $this->model
            ->where('favorite_car_id', $favoriteCarId)
            ->get();
    }
}
