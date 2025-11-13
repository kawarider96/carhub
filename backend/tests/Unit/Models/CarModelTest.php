<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\CarBrand;
use App\Models\CarModel;
use PHPUnit\Framework\Attributes\Test;

class CarModelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_car_model()
    {
        $brand = CarBrand::factory()->create();

        $model = CarModel::factory()->create([
            'car_brand_id' => $brand->id,
            'name' => 'M3'
        ]);

        $this->assertDatabaseHas('car_models', [
            'name' => 'M3',
            'car_brand_id' => $brand->id
        ]);
    }

    #[Test]
    public function it_belongs_to_a_brand()
    {
        $brand = CarBrand::factory()->create();
        $model = CarModel::factory()->create(['car_brand_id' => $brand->id]);

        $this->assertInstanceOf(CarBrand::class, $model->brand);
        $this->assertEquals($brand->id, $model->brand->id);
    }

    #[Test]
    public function it_casts_dates_correctly()
    {
        $model = CarModel::factory()->create();

        $this->assertInstanceOf(
            \Carbon\CarbonImmutable::class,
            $model->created_at
        );
    }
}
