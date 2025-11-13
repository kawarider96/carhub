<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\CarModel;
use App\Models\FavoriteCar;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\CarbonImmutable;

class FavoriteCarFactory extends Factory
{
    protected $model = FavoriteCar::class;

    public function definition(): array
    {
        return [
            'user_id'      => User::factory(),
            'car_model_id' => CarModel::inRandomOrder()->value('id')
                              ?? CarModel::factory()->create()->id,
            'year'         => $this->faker->year(),
            'color'        => $this->faker->safeColorName(),
            'fuel'         => $this->faker->randomElement(['benzin', 'dízel', 'elektromos', 'hibrid']),
            'created_at'   => CarbonImmutable::now(),
            'updated_at'   => CarbonImmutable::now(),
        ];
    }
}
