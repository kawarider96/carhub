<?php

namespace App\Repositories;

use App\Models\CarImage;

class CarImageRepository extends BaseRepository
{
    public function __construct(CarImage $model)
    {
        parent::__construct($model);
    }

    /**
     * Képek lekérése egy kedvenc autóhoz.
     */
    public function getByFavoriteCar(int $favoriteCarId)
    {
        return $this->model
            ->where('favorite_car_id', $favoriteCarId)
            ->orderBy('id')
            ->get();
    }

    /**
     * Egy kép létrehozása – bináris tartalommal és MIME típussal.
     */
    public function create(array $data): CarImage
    {
        return $this->model->create([
            'favorite_car_id' => $data['favorite_car_id'],
            'content'         => $data['content'],
            'mime'            => $data['mime'] ?? null,
        ]);
    }
}
