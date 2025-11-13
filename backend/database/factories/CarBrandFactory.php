<?php

namespace Database\Factories;

use App\Models\CarBrand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Database\Seeders\ExampleData;
use Carbon\CarbonImmutable;

class CarBrandFactory extends Factory
{
    protected $model = CarBrand::class;

    public function definition(): array
    {
        // kiválasztunk egy brandet ExampleData-ból
        $brandEntry = $this->faker->randomElement(ExampleData::$carData);

        return [
            'name'       => $brandEntry['brand'],
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ];
    }
}
