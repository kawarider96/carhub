<?php

namespace App\Services;

use App\Repositories\CarModelRepository;

class CarModelService
{
    public function __construct(
        protected CarModelRepository $models
    ) {}

    public function byBrand(int $brandId)
    {
        return $this->models->getByBrand($brandId);
    }

    public function create(array $data)
    {
        if ($this->models->existsForBrand($data['car_brand_id'], $data['name'])) {
            return ['status' => false, 'error' => 'exists'];
        }

        return $this->models->create($data);
    }
}
