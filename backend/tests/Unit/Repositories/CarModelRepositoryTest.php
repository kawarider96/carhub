<?php

declare(strict_types=1);

namespace Tests\Unit\Repositories;

use App\Models\CarBrand;
use App\Models\CarModel;
use App\Repositories\CarModelRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CarModelRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected CarModelRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new CarModelRepository(new CarModel());
    }

    // ─────────────────────────────────────────────
    // 1) getByBrand – visszaadja a brand modelljeit
    // ─────────────────────────────────────────────

    #[Test]
    public function test_get_by_brand_returns_correct_models(): void
    {
        $brand = CarBrand::factory()->create();

        CarModel::factory()->count(3)->create([
            'car_brand_id' => $brand->id,
        ]);

        $models = $this->repo->getByBrand($brand->id);

        $this->assertCount(3, $models);
        $this->assertEquals($brand->id, $models->first()->car_brand_id);
    }

    // ─────────────────────────────────────────────
    // 2) getByBrand – nincs modell → üres lista
    // ─────────────────────────────────────────────

    #[Test]
    public function test_get_by_brand_returns_empty_when_no_models(): void
    {
        $models = $this->repo->getByBrand(99999);

        $this->assertCount(0, $models);
    }

    // ─────────────────────────────────────────────
    // 3) getByBrand – ABC sorrend
    // ─────────────────────────────────────────────

    #[Test]
    public function test_get_by_brand_returns_models_sorted_by_name(): void
    {
        $brand = CarBrand::factory()->create();

        CarModel::factory()->create([
            'car_brand_id' => $brand->id,
            'name' => 'C-Class',
        ]);

        CarModel::factory()->create([
            'car_brand_id' => $brand->id,
            'name' => 'A-Class',
        ]);

        CarModel::factory()->create([
            'car_brand_id' => $brand->id,
            'name' => 'B-Class',
        ]);

        $models = $this->repo->getByBrand($brand->id);

        $this->assertEquals(
            ['A-Class', 'B-Class', 'C-Class'],
            $models->pluck('name')->toArray()
        );
    }

    // ─────────────────────────────────────────────
    // 4) getByBrand – más márka modelljeit kizárja
    // ─────────────────────────────────────────────

    #[Test]
    public function test_get_by_brand_excludes_other_brands_models(): void
    {
        $brand1 = CarBrand::factory()->create();
        $brand2 = CarBrand::factory()->create();

        CarModel::factory()->create([
            'car_brand_id' => $brand1->id,
            'name' => 'Model-A',
        ]);

        CarModel::factory()->create([
            'car_brand_id' => $brand2->id,
            'name' => 'Model-B',
        ]);

        $models = $this->repo->getByBrand($brand1->id);

        $this->assertCount(1, $models);
        $this->assertEquals($brand1->id, $models->first()->car_brand_id);
    }

    // ─────────────────────────────────────────────
    // 5 – ADVANCED: minden elem CarModel instance
    // ─────────────────────────────────────────────

    #[Test]
    public function test_get_by_brand_returns_car_model_instances(): void
    {
        $brand = CarBrand::factory()->create();

        CarModel::factory()->count(2)->create([
            'car_brand_id' => $brand->id
        ]);

        $models = $this->repo->getByBrand($brand->id);

        $models->each(function ($item) {
            $this->assertInstanceOf(CarModel::class, $item);
        });
    }

    // ─────────────────────────────────────────────
    // 6 – ADVANCED: 1 query fusson csak
    // ─────────────────────────────────────────────

    #[Test]
    public function test_get_by_brand_executes_single_query(): void
    {
        $brand = CarBrand::factory()->create();

        CarModel::factory()->count(3)->create([
            'car_brand_id' => $brand->id
        ]);

        \DB::enableQueryLog();

        $this->repo->getByBrand($brand->id);

        $queries = \DB::getQueryLog();

        $this->assertCount(1, $queries, 'getByBrand should execute exactly 1 query');
    }
}
