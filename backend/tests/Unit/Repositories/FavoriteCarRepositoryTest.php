<?php

declare(strict_types=1);

namespace Tests\Unit\Repositories;

use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\FavoriteCar;
use App\Models\User;
use App\Repositories\FavoriteCarRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FavoriteCarRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected FavoriteCarRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new FavoriteCarRepository(new FavoriteCar());
    }

    // ─────────────────────────────────────────────
    // 1) Visszaadja a user saját kedvenc autóit
    // ─────────────────────────────────────────────

    #[Test]
    public function test_get_by_user_returns_users_favorite_cars(): void
    {
        $user = User::factory()->create();

        FavoriteCar::factory()->count(3)->create(['user_id' => $user->id]);

        $cars = $this->repo->getByUser($user->id);

        $this->assertCount(3, $cars);
        $this->assertEquals($user->id, $cars->first()->user_id);
    }

    // ─────────────────────────────────────────────
    // 2) Üres lista
    // ─────────────────────────────────────────────

    #[Test]
    public function test_get_by_user_returns_empty_collection_when_no_cars(): void
    {
        $cars = $this->repo->getByUser(99999);

        $this->assertCount(0, $cars);
    }

    // ─────────────────────────────────────────────
    // 3) Más felhasználó autóit kizárja
    // ─────────────────────────────────────────────

    #[Test]
    public function test_get_by_user_excludes_other_users_cars(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        FavoriteCar::factory()->create(['user_id' => $user1->id]);
        FavoriteCar::factory()->create(['user_id' => $user2->id]);

        $cars = $this->repo->getByUser($user1->id);

        $this->assertCount(1, $cars);
        $this->assertEquals($user1->id, $cars->first()->user_id);
    }

    // ─────────────────────────────────────────────
    // 4) carModel eager loaded
    // ─────────────────────────────────────────────

    #[Test]
    public function test_get_by_user_eager_loads_car_model(): void
    {
        $user = User::factory()->create();
        $brand = CarBrand::factory()->create();
        $model = CarModel::factory()->create(['car_brand_id' => $brand->id]);

        FavoriteCar::factory()->create([
            'user_id' => $user->id,
            'car_model_id' => $model->id,
        ]);

        $cars = $this->repo->getByUser($user->id);

        $this->assertNotNull($cars->first()->carModel);
        $this->assertEquals($model->id, $cars->first()->carModel->id);
    }

    // ─────────────────────────────────────────────
    // 5) carModel.brand eager loaded
    // ─────────────────────────────────────────────

    #[Test]
    public function test_get_by_user_eager_loads_brand(): void
    {
        $user = User::factory()->create();
        $brand = CarBrand::factory()->create();
        $model = CarModel::factory()->create(['car_brand_id' => $brand->id]);

        FavoriteCar::factory()->create([
            'user_id' => $user->id,
            'car_model_id' => $model->id,
        ]);

        $cars = $this->repo->getByUser($user->id);

        $this->assertNotNull($cars->first()->carModel->brand);
        $this->assertEquals($brand->id, $cars->first()->carModel->brand->id);
    }

    // ─────────────────────────────────────────────
    // 6) Query count (3 query)
    // ─────────────────────────────────────────────

    #[Test]
    public function test_get_by_user_executes_three_queries(): void
    {
        $user = User::factory()->create();
        $brand = CarBrand::factory()->create();
        $model = CarModel::factory()->create(['car_brand_id' => $brand->id]);

        FavoriteCar::factory()->create([
            'user_id' => $user->id,
            'car_model_id' => $model->id,
        ]);

        \DB::enableQueryLog();

        $this->repo->getByUser($user->id);

        $queries = \DB::getQueryLog();

        $this->assertCount(3, $queries);
    }

    // ─────────────────────────────────────────────
    // 7 – ADV: minden elem FavoriteCar instance
    // ─────────────────────────────────────────────

    #[Test]
    public function test_get_by_user_returns_favoritecar_instances(): void
    {
        $user = User::factory()->create();

        FavoriteCar::factory()->count(2)->create(['user_id' => $user->id]);

        $cars = $this->repo->getByUser($user->id);

        $cars->each(function ($car) {
            $this->assertInstanceOf(FavoriteCar::class, $car);
        });
    }

    // ─────────────────────────────────────────────
    // 8 – ADV: Eager load tényleges ellenőrzése extra query nélkül
    // ─────────────────────────────────────────────

    #[Test]
    public function test_eager_loaded_prevents_n_plus_one(): void
    {
        $user = User::factory()->create();
        $brand = CarBrand::factory()->create();
        $model = CarModel::factory()->create(['car_brand_id' => $brand->id]);

        FavoriteCar::factory()->count(3)->create([
            'user_id' => $user->id,
            'car_model_id' => $model->id,
        ]);

        \DB::enableQueryLog();

        $cars = $this->repo->getByUser($user->id);

        // Access relationships:
        $cars->each(fn($car) => $car->carModel->brand->name);

        $queries = \DB::getQueryLog();

        // STILL only 3 queries
        $this->assertCount(3, $queries);
    }
}
