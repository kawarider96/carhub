<?php

namespace Tests\Unit\Repositories;

use App\Models\CarImage;
use App\Models\FavoriteCar;
use App\Models\User;
use App\Models\CarModel;
use App\Models\CarBrand;
use App\Repositories\CarImageRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarImageRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected CarImageRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new CarImageRepository(new CarImage);
    }

    public function test_it_creates_car_image()
    {
        $brand = CarBrand::factory()->create();
        $model = CarModel::factory()->create(['car_brand_id' => $brand->id]);
        $user  = User::factory()->create();

        $favorite = FavoriteCar::factory()->create([
            'user_id'       => $user->id,
            'car_model_id'  => $model->id,
            'year'          => 2012,
            'color'         => 'Blue',
            'fuel'          => 'Petrol',
        ]);

        $image = $this->repo->create([
            'favorite_car_id' => $favorite->id,
            'content'         => 'BLOB-CONTENT',
            'mime'            => 'image/jpeg',
            'created_at'      => now(),
        ]);

        $this->assertDatabaseHas('car_images', [
            'id'              => $image->id,
            'favorite_car_id' => $favorite->id,
            'mime'            => 'image/jpeg',
        ]);
    }

    public function test_it_gets_images_by_favorite_car()
    {
        $brand = CarBrand::factory()->create();
        $model = CarModel::factory()->create(['car_brand_id' => $brand->id]);
        $user  = User::factory()->create();

        $favorite = FavoriteCar::factory()->create([
            'user_id'       => $user->id,
            'car_model_id'  => $model->id,
            'year'          => 2010,
            'color'         => 'Black',
            'fuel'          => 'Diesel',
        ]);

        CarImage::factory()->count(3)->create([
            'favorite_car_id' => $favorite->id,
        ]);

        $result = $this->repo->getByFavoriteCar($favorite->id);

        $this->assertCount(3, $result);
        $this->assertTrue(
            $result->every(fn ($img) => $img->favorite_car_id === $favorite->id)
        );
    }
}
