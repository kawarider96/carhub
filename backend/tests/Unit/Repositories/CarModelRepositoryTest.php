<?php

namespace Tests\Unit\Repositories;

use App\Models\CarBrand;
use App\Models\CarModel;
use App\Repositories\CarModelRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CarModelRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected CarModelRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new CarModelRepository(new CarModel);
    }

    #[Test]
    public function it_returns_models_only_for_given_brand()
    {
        $brandA = CarBrand::factory()->create();
        $brandB = CarBrand::factory()->create();

        // brand A modellek
        $m1 = CarModel::factory()->create([
            'car_brand_id' => $brandA->id,
            'name' => 'Amodel'
        ]);

        $m2 = CarModel::factory()->create([
            'car_brand_id' => $brandA->id,
            'name' => 'Bmodel'
        ]);

        // brand B modell
        CarModel::factory()->create([
            'car_brand_id' => $brandB->id,
            'name' => 'Xmodel'
        ]);

        $result = $this->repo->getByBrand($brandA->id);

        $this->assertCount(2, $result);
        $this->assertEqualsCanonicalizing(
            [$m1->id, $m2->id],
            $result->pluck('id')->toArray()
        );
    }

    #[Test]
    public function it_returns_models_ordered_by_name()
    {
        $brand = CarBrand::factory()->create();

        $m1 = CarModel::factory()->create([
            'car_brand_id' => $brand->id,
            'name' => 'Zorro'
        ]);

        $m2 = CarModel::factory()->create([
            'car_brand_id' => $brand->id,
            'name' => 'Alpha'
        ]);

        $result = $this->repo->getByBrand($brand->id);

        $this->assertEquals(
            ['Alpha', 'Zorro'],
            $result->pluck('name')->toArray()
        );
    }

    #[Test]
    public function it_checks_if_model_exists_for_brand()
    {
        $brand = CarBrand::factory()->create();

        CarModel::factory()->create([
            'car_brand_id' => $brand->id,
            'name' => 'Civic'
        ]);

        $this->assertTrue(
            $this->repo->existsForBrand($brand->id, 'Civic')
        );

        $this->assertFalse(
            $this->repo->existsForBrand($brand->id, 'Accord')
        );
    }

    #[Test]
    public function it_does_not_confuse_models_from_other_brands()
    {
        $brandA = CarBrand::factory()->create();
        $brandB = CarBrand::factory()->create();

        CarModel::factory()->create([
            'car_brand_id' => $brandA->id,
            'name' => 'Focus'
        ]);

        // Ugyanaz a név, más márka alatt
        CarModel::factory()->create([
            'car_brand_id' => $brandB->id,
            'name' => 'Focus'
        ]);

        $this->assertTrue(
            $this->repo->existsForBrand($brandA->id, 'Focus')
        );

        $this->assertTrue(
            $this->repo->existsForBrand($brandB->id, 'Focus')
        );

        // Ha rossz brand-id-t adsz, false legyen
        $this->assertFalse(
            $this->repo->existsForBrand(999999, 'Focus')
        );
    }
}
