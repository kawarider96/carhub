<?php

namespace App\Repositories;

use App\Models\CarModel;
use Illuminate\Support\Collection;

/**
 * @extends BaseRepository<CarModel>
 */
class CarModelRepository extends BaseRepository
{
    public function __construct(CarModel $model)
    {
        parent::__construct($model);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, CarModel>
     */
    public function getByBrand(int $brandId)
    {
        return $this->model
            ->where('car_brand_id', $brandId)
            ->orderBy('name')
            ->get();
    }

    /**
     * Márkával együtt betöltve (index / lista nézethez).
     * @return \Illuminate\Database\Eloquent\Collection<int, CarModel>
     */
    public function allWithBrand(): Collection
    {
        return CarModel::with('brand')
            ->orderBy('name')
            ->get();
    }
}
