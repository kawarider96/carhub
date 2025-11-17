<?php

namespace App\Repositories;

use App\Models\CarBrand;

/**
 * @extends BaseRepository<CarBrand>
 */
class CarBrandRepository extends BaseRepository
{
    public function __construct(CarBrand $model)
    {
        parent::__construct($model);
    }

    /**
     * Ellenőrzi, hogy létezik-e márka a nevével.
     */
    public function existsByName(string $name): bool
    {
        return $this->model->where('name', $name)->exists();
    }

    /**
     * Megkeres egy márkát a neve alapján.
     *
     * @return CarBrand|null
     */
    public function findByName(string $name): ?CarBrand
    {
        return $this->model->where('name', $name)->first();
    }
}
