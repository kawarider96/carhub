<?php

declare(strict_types=1);

namespace Tests\Unit\Repositories;

use App\Models\CarImage;
use App\Models\FavoriteCar;
use App\Repositories\CarImageRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CarImageRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected CarImageRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new CarImageRepository(new CarImage());
    }

    // ─────────────────────────────────────────────
    // 1) getByFavoriteCar – képek lekérése
    // ─────────────────────────────────────────────

    #[Test]
    public function test_get_by_favorite_car_returns_images(): void
    {
        $car = FavoriteCar::factory()->create();

        CarImage::factory()->count(3)->create([
            'favorite_car_id' => $car->id
        ]);

        $images = $this->repo->getByFavoriteCar($car->id);

        $this->assertCount(3, $images);
        $this->assertEquals($car->id, $images->first()->favorite_car_id);
    }

    // ─────────────────────────────────────────────
    // 2) getByFavoriteCar – üres lista ha nincs kép
    // ─────────────────────────────────────────────

    #[Test]
    public function test_get_by_favorite_car_when_no_images_returns_empty_collection(): void
    {
        $images = $this->repo->getByFavoriteCar(99999);

        $this->assertCount(0, $images);
    }

    // ─────────────────────────────────────────────
    // 3) getByFavoriteCar – ID szerinti rendezés
    // ─────────────────────────────────────────────

    #[Test]
    public function test_get_by_favorite_car_orders_by_id(): void
    {
        $car = FavoriteCar::factory()->create();

        $img3 = CarImage::factory()->create(['favorite_car_id' => $car->id, 'id' => 30]);
        $img1 = CarImage::factory()->create(['favorite_car_id' => $car->id, 'id' => 10]);
        $img2 = CarImage::factory()->create(['favorite_car_id' => $car->id, 'id' => 20]);

        $images = $this->repo->getByFavoriteCar($car->id);

        $this->assertEquals([10, 20, 30], $images->pluck('id')->toArray());
    }

    // ─────────────────────────────────────────────
    // 4) create – bináris tartalom + MIME
    // ─────────────────────────────────────────────

    #[Test]
    public function test_create_stores_binary_content_and_mime(): void
    {
        $car = FavoriteCar::factory()->create();

        $data = [
            'favorite_car_id' => $car->id,
            'content'         => 'BINARY_DATA',
            'mime'            => 'image/jpeg',
        ];

        $image = $this->repo->create($data);

        $this->assertDatabaseHas('car_images', [
            'id' => $image->id,
            'favorite_car_id' => $car->id,
            'mime' => 'image/jpeg'
        ]);

        $this->assertEquals('BINARY_DATA', $image->content);
    }

    // ─────────────────────────────────────────────
    // 5) create – MIME nélkül
    // ─────────────────────────────────────────────

    #[Test]
    public function test_create_with_no_mime_sets_null(): void
    {
        $car = FavoriteCar::factory()->create();

        $image = $this->repo->create([
            'favorite_car_id' => $car->id,
            'content'         => 'data123',
        ]);

        $this->assertDatabaseHas('car_images', [
            'id' => $image->id,
            'mime' => null
        ]);
    }

    // ─────────────────────────────────────────────
    // 6) create – hiányzó favorite_car_id → exception
    // ─────────────────────────────────────────────

    #[Test]
    public function test_create_throws_exception_if_missing_favorite_car_id(): void
    {
        $this->expectException(\Exception::class);

        $this->repo->create([
            'content' => 'asd',
            'mime' => 'image/png'
        ]);
    }

    // ─────────────────────────────────────────────
    // 7 – ADVANCED: getByFavoriteCar nem ad vissza más autó képeit
    // ─────────────────────────────────────────────

    #[Test]
    public function test_get_by_favorite_car_excludes_other_records(): void
    {
        $car1 = FavoriteCar::factory()->create();
        $car2 = FavoriteCar::factory()->create();

        CarImage::factory()->create(['favorite_car_id' => $car1->id]);
        CarImage::factory()->create(['favorite_car_id' => $car2->id]);

        $images = $this->repo->getByFavoriteCar($car1->id);

        $this->assertCount(1, $images);
        $this->assertEquals($car1->id, $images->first()->favorite_car_id);
    }

    // ─────────────────────────────────────────────
    // 8 – ADVANCED: create() CarImage instance
    // ─────────────────────────────────────────────

    #[Test]
    public function test_create_returns_carimage_instance(): void
    {
        $car = FavoriteCar::factory()->create();

        $image = $this->repo->create([
            'favorite_car_id' => $car->id,
            'content'         => 'dataxyz'
        ]);

        $this->assertInstanceOf(CarImage::class, $image);
    }
}
