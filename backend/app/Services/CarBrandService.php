<?php

namespace App\Services;

use App\Repositories\CarBrandRepository;

class CarBrandService
{
    public function __construct(
        protected CarBrandRepository $brands
    ) {}

    public function all()
    {
        return $this->brands->all();
    }

    public function create(array $data)
    {
        return $this->brands->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->brands->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->brands->delete($id);
    }

    public function find(int $id)
    {
        return $this->brands->find($id);
    }
}
