<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\FavoriteCar;
use App\Models\CarModel;

class FavoriteCarSeeder extends Seeder
{
    public function run(): void
    {
        $models = CarModel::all();

        User::all()->each(function (User $user) use ($models) {

            FavoriteCar::create([
                'user_id'      => $user->id,
                'car_model_id' => $models->random()->id,
                'year'         => rand(1995, 2025),
                'color'        => fake()->safeColorName(),
                'fuel'         => fake()->randomElement(['benzin', 'dízel', 'elektromos', 'hibrid']),
            ]);
        });
    }
}
