<?php

namespace App\Services;

use App\Repositories\CarImageRepository;

class CarImageService
{
    public function __construct(
        protected CarImageRepository $images
    ) {}

    public function byFavoriteCar(int $favoriteCarId)
    {
        return $this->images->getByFavoriteCar($favoriteCarId);
    }

    public function upload(array $data)
    {
        return $this->images->create($data);
    }

    public function delete(int $id)
    {
        return $this->images->delete($id);
    }
}
