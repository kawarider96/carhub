<?php

namespace App\Services;

use App\Repositories\CarBrandRepository;
use App\Models\CarBrand;
use Illuminate\Support\Collection;

class CarBrandService
{
    public function __construct(
        protected CarBrandRepository $brands
    ) {}

    /**
     * @return Collection<int, CarBrand>
     */
    public function all()
    {
        return $this->brands->all();
    }

    /**
     * @param array<string, mixed> $data
     * @return CarBrand
     */
    public function create(array $data)
    {
        return $this->brands->create($data);
    }

    /**
     * @param int $id
     * @param array<string, mixed> $data
     * @return CarBrand
     */
    public function update(int $id, array $data)
    {
        return $this->brands->update($id, $data);
    }

    /**
     * @return bool|null
     */
    public function delete(int $id)
    {
        return $this->brands->delete($id);
    }

    /**
     * @return CarBrand
     */
    public function find(int $id)
    {
        return $this->brands->find($id);
    }
}
