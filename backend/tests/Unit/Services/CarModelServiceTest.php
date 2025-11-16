<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\CarModel;
use App\Repositories\CarModelRepository;
use App\Services\CarModelService;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Support\Collection;

class CarModelServiceTest extends TestCase
{
    private CarModelRepository $repo;
    private CarModelService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repo = Mockery::mock(CarModelRepository::class);
        $this->service = new CarModelService($this->repo);
    }

    // ───────────────────────────────────────────────
    // all()
    // ───────────────────────────────────────────────

    #[Test]
    public function it_returns_all_models_with_brand(): void
    {
        $expected = collect(['m1', 'm2']); // ✔ Collection, nem array

        $this->repo
            ->shouldReceive('allWithBrand')
            ->once()
            ->andReturn($expected);

        $result = $this->service->all();

        $this->assertSame($expected, $result);
    }

    // ───────────────────────────────────────────────
    // byBrand()
    // ───────────────────────────────────────────────

    #[Test]
    public function it_returns_models_by_brand(): void
    {
        $expected = ['x', 'y'];

        $this->repo
            ->shouldReceive('getByBrand')
            ->once()
            ->with(5)
            ->andReturn($expected);

        $result = $this->service->byBrand(5);

        $this->assertSame($expected, $result);
    }

    // ───────────────────────────────────────────────
    // create()
    // ───────────────────────────────────────────────

    #[Test]
    public function it_creates_a_model(): void
    {
        $data = ['car_brand_id' => 1, 'name' => 'A4'];

        $model = new CarModel();
        $model->id = 10;

        $this->repo
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($model);

        $result = $this->service->create($data);

        $this->assertSame($model, $result);
    }

    // ───────────────────────────────────────────────
    // find()
    // ───────────────────────────────────────────────

    #[Test]
    public function it_finds_a_model_by_id(): void
    {
        $model = new CarModel();
        $model->id = 7;

        $this->repo
            ->shouldReceive('find')
            ->once()
            ->with(7)
            ->andReturn($model);

        $result = $this->service->find(7);

        $this->assertSame($model, $result);
    }

    // ───────────────────────────────────────────────
    // update()
    // ───────────────────────────────────────────────

    #[Test]
    public function it_updates_a_model(): void
    {
        $data = ['name' => 'Updated'];
        $updated = new CarModel();
        $updated->id = 9;

        $this->repo
            ->shouldReceive('update')
            ->once()
            ->with(9, $data)
            ->andReturn($updated);

        $result = $this->service->update(9, $data);

        $this->assertSame($updated, $result);
    }

    // ───────────────────────────────────────────────
    // delete()
    // ───────────────────────────────────────────────

    #[Test]
    public function it_deletes_a_model(): void
    {
        $this->repo
            ->shouldReceive('delete')
            ->once()
            ->with(11)
            ->andReturn(true);

        $result = $this->service->delete(11);

        $this->assertTrue($result);
    }
}
