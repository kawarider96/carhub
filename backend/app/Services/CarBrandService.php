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
        if ($this->brands->existsByName($data['name'])) {
            return ['status' => false, 'error' => 'exists'];
        }

        return $this->brands->create($data);
    }
}
