<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\CarBrand;
use App\Models\CarModel;
use PHPUnit\Framework\Attributes\Test;

class CarBrandTest extends TestCase
{
    use RefreshDatabase;

    
    #[Test]
    public function it_can_create_a_car_brand()
    {
        $brand = CarBrand::factory()->create([
            'name' => 'BMW'
        ]);

        $this->assertDatabaseHas('car_brands', [
            'name' => 'BMW'
        ]);
    }

    #[Test]
    public function it_has_models_relationship()
    {
        $brand = CarBrand::factory()->create();
        $models = CarModel::factory()->count(3)->create([
            'car_brand_id' => $brand->id
        ]);

        $this->assertCount(3, $brand->models);
        $this->assertInstanceOf(CarModel::class, $brand->models->first());
    }

    #[Test]
    public function it_casts_dates_correctly()
    {
        $brand = CarBrand::factory()->create();

        $this->assertInstanceOf(
            \Carbon\CarbonImmutable::class,
            $brand->created_at
        );
    }
}
