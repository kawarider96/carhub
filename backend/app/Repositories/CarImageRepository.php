<?php

namespace App\Repositories;

use App\Models\CarImage;

/**
 * @extends BaseRepository<CarImage>
 */
class CarImageRepository extends BaseRepository
{
    public function __construct(CarImage $model)
    {
        parent::__construct($model);
    }

    /**
     * Képek lekérése egy kedvenc autóhoz.
     * @return \Illuminate\Database\Eloquent\Collection<int, CarImage>
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
     * @param array<string, mixed> $data
     * @return CarImage
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
