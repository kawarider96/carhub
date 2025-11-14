<?php

namespace App\Repositories;

use App\Models\CarBrand;

class CarBrandRepository extends BaseRepository
{
    public function __construct(CarBrand $model)
    {
        parent::__construct($model);
    }

    public function existsByName(string $name): bool
    {
        return $this->model->where('name', $name)->exists();
    }

    public function findByName(string $name): ?CarBrand
    {
        return $this->model->where('name', $name)->first();
    }
}
