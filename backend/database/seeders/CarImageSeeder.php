<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FavoriteCar;
use App\Models\CarImage;

class CarImageSeeder extends Seeder
{
    public function run(): void
    {
        FavoriteCar::all()->each(function (FavoriteCar $car) {
            CarImage::factory()
                ->count(rand(1, 3))
                ->create([
                    'favorite_car_id' => $car->id,
                ]);
        });
    }
}
