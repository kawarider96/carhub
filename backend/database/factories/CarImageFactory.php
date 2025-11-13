<?php

namespace Database\Factories;

use App\Models\FavoriteCar;
use App\Models\CarImage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\CarbonImmutable;

class CarImageFactory extends Factory
{
    protected $model = CarImage::class;

    public function definition(): array
    {
        return [
            'favorite_car_id' => FavoriteCar::factory(),
            'content'         => base64_encode($this->faker->image()),
            'mime'            => 'image/jpeg',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ];
    }
}
