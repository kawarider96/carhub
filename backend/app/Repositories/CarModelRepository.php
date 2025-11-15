<?php

namespace App\Repositories;

use App\Models\CarModel;

class CarModelRepository extends BaseRepository
{
    public function __construct(CarModel $model)
    {
        parent::__construct($model);
    }

    public function getByBrand(int $brandId)
    {
        return $this->model
            ->where('car_brand_id', $brandId)
            ->orderBy('name')
            ->get();
    }
}
