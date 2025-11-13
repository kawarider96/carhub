<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\CarModel;
use App\Models\FavoriteCar;
use PHPUnit\Framework\Attributes\Test;

class FavoriteCarTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_favorite_car()
    {
        $user = User::factory()->create();
        $model = CarModel::factory()->create();

        $car = FavoriteCar::create([
            'user_id' => $user->id,
            'car_model_id' => $model->id,
            'year' => 2020,
            'color' => 'blue',
            'fuel' => 'benzin'
        ]);

        $this->assertDatabaseHas('favorite_cars', [
            'user_id' => $user->id,
            'car_model_id' => $model->id
        ]);
    }

    #[Test]
    public function it_has_user_relationship()
    {
        $user = User::factory()->create();
        $model = CarModel::factory()->create();

        $car = FavoriteCar::create([
            'user_id' => $user->id,
            'car_model_id' => $model->id
        ]);

        $this->assertInstanceOf(
            User::class,
            $car->user
        );
    }

    #[Test]
    public function it_casts_year_as_integer()
    {
        $car = FavoriteCar::factory()->create([
            'year' => 2018
        ]);

        $this->assertIsInt($car->year);
    }
}
