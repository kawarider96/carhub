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
     * Az összes autómodell listázása márkával betöltve.
     *
     * @return Collection<int, CarModel>
     */
    public function all()
    {
        return $this->models->allWithBrand();
    }

    /**
     * Egy adott márkához tartozó típusok listája.
     *
     * @return Collection<int, CarModel>
     */
    public function byBrand(int $brandId)
    {
        return $this->models->getByBrand($brandId);
    }

    /**
     * Új modell létrehozása.
     *
     * @param array<string, mixed> $data
     * @return CarModel
     */
    public function create(array $data)
    {
        return $this->models->create($data);
    }

    /**
     * Egy modell lekérése ID alapján.
     *
     * @return CarModel
     */
    public function find(int $id): ?CarModel
    {
        return $this->models->find($id);
    }

    /**
     * Modell frissítése.
     *
     * @param array<string, mixed> $data
     * @return CarModel
     */
    public function update(int $id, array $data)
    {
        return $this->models->update($id, $data);
    }

    /**
     * Modell törlése.
     *
     * @return bool|null
     */
    public function delete(int $id)
    {
        return $this->models->delete($id);
    }
}
