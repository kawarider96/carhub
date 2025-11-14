<?php

namespace Tests\Unit\Repositories;

use App\Models\CarBrand;
use App\Repositories\CarBrandRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class BaseRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected CarBrandRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new CarBrandRepository(new CarBrand);
    }

    #[Test]
    public function test_it_creates_record()
    {
        $brand = $this->repo->create(['name' => 'BMW']);

        $this->assertDatabaseHas('car_brands', [
            'id'   => $brand->id,
            'name' => 'BMW',
        ]);
    }

    #[Test]
    public function test_it_finds_record_by_id()
    {
        $brand = CarBrand::factory()->create(['name' => 'Audi']);

        $found = $this->repo->find($brand->id);

        $this->assertEquals('Audi', $found->name);
    }

    #[Test]
    public function test_it_returns_all_records()
    {
        CarBrand::factory()->count(3)->create();

        $all = $this->repo->all();

        $this->assertCount(3, $all);
    }

    #[Test]
    public function test_it_updates_record()
    {
        $brand = CarBrand::factory()->create(['name' => 'OldName']);

        $updated = $this->repo->update($brand->id, ['name' => 'NewName']);

        $this->assertEquals('NewName', $updated->name);
        $this->assertDatabaseHas('car_brands', [
            'id'   => $brand->id,
            'name' => 'NewName',
        ]);
    }

    #[Test]
    public function test_it_deletes_record()
    {
        $brand = CarBrand::factory()->create();

        $this->repo->delete($brand->id);

        $this->assertDatabaseMissing('car_brands', [
            'id' => $brand->id,
        ]);
    }
}
