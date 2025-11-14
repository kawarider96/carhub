<?php

namespace Tests\Unit\Repositories;

use App\Models\FavoriteCar;
use App\Models\User;
use App\Models\CarModel;
use App\Repositories\FavoriteCarRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class FavoriteCarRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected FavoriteCarRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new FavoriteCarRepository(new FavoriteCar);
    }

    #[Test]
    public function test_create_favorite_car()
    {
        $user = User::factory()->create();
        $model = CarModel::factory()->create();

        $car = $this->repo->create([
            'user_id' => $user->id,
            'car_model_id' => $model->id,
            'year' => 2005,
            'color' => 'Black',
            'fuel' => 'Petrol'
        ]);

        $this->assertDatabaseHas('favorite_cars', [
            'id' => $car->id
        ]);
    }

    #[Test]
    public function test_get_favorite_cars_by_user()
    {
        $user = User::factory()->create();

        FavoriteCar::factory()->count(2)->create([
            'user_id' => $user->id
        ]);

        $result = $this->repo->getByUser($user->id);

        $this->assertCount(2, $result);
    }
}
