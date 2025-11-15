<?php

namespace App\Services;

use App\Repositories\CarModelRepository;
use App\Models\CarModel;

class CarModelService
{
    public function __construct(
        protected CarModelRepository $models
    ) {}

    /**
     * Az összes autómodell listázása.
     */
    public function all()
    {
        return $this->models->allWithBrand();
    }

    /**
     * Egy adott márkához tartozó típusok listája.
     */
    public function byBrand(int $brandId)
    {
        return $this->models->getByBrand($brandId);
    }

    /**
     * Új modell létrehozása.
     */
    public function create(array $data)
    {
        return $this->models->create($data);
    }

    /**
     * Egy modell lekérése ID alapján.
     */
    public function find(int $id): ?CarModel
    {
        return $this->models->find($id);
    }

    /**
     * Modell frissítése.
     */
    public function update(int $id, array $data)
    {
        return $this->models->update($id, $data);
    }

    /**
     * Modell törlése.
     */
    public function delete(int $id): bool
    {
        return $this->models->delete($id);
    }
}
