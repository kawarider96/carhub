<?php

namespace Database\Factories;

use App\Models\CarBrand;
use App\Models\CarModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\CarbonImmutable;

class CarModelFactory extends Factory
{
    protected $model = CarModel::class;

    public function definition(): array
    {
        $brand = CarBrand::inRandomOrder()->first()
                 ?? CarBrand::factory()->create();
                 
        return [
            'car_brand_id' => $brand->id,
            'name'         => $this->faker->word(),
            'created_at'   => CarbonImmutable::now(),
            'updated_at'   => CarbonImmutable::now(),
        ];
    }
}
