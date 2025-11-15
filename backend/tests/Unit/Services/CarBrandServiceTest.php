<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\CarBrandService;
use App\Repositories\CarBrandRepository;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CarBrandServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // ───────────────────────────────────────────────
    // 1) all()
    // ───────────────────────────────────────────────

    #[Test]
    public function test_all_calls_repository_all(): void
    {
        $repo = Mockery::mock(CarBrandRepository::class);
        $repo->shouldReceive('all')->once()->andReturn(['BMW', 'Audi']);

        $service = new CarBrandService($repo);

        $res = $service->all();

        $this->assertEquals(['BMW', 'Audi'], $res);
    }

    // ───────────────────────────────────────────────
    // 2) create()
    // ───────────────────────────────────────────────

    #[Test]
    public function test_create_calls_repository_create_with_data(): void
    {
        $repo = Mockery::mock(CarBrandRepository::class);

        $repo->shouldReceive('create')
            ->once()
            ->with(['name' => 'BMW'])
            ->andReturn(['id' => 10, 'name' => 'BMW']);

        $service = new CarBrandService($repo);

        $res = $service->create(['name' => 'BMW']);

        $this->assertSame(['id' => 10, 'name' => 'BMW'], $res);
    }

    // ───────────────────────────────────────────────
    // 3) update()
    // ───────────────────────────────────────────────

    #[Test]
    public function test_update_calls_repository_update(): void
    {
        $repo = Mockery::mock(CarBrandRepository::class);

        $repo->shouldReceive('update')
            ->once()
            ->with(5, ['name' => 'Audi'])
            ->andReturn(['id' => 5, 'name' => 'Audi']);

        $service = new CarBrandService($repo);

        $res = $service->update(5, ['name' => 'Audi']);

        $this->assertSame(['id' => 5, 'name' => 'Audi'], $res);
    }

    // ───────────────────────────────────────────────
    // 4) delete()
    // ───────────────────────────────────────────────

    #[Test]
    public function test_delete_calls_repository_delete(): void
    {
        $repo = Mockery::mock(CarBrandRepository::class);

        $repo->shouldReceive('delete')
            ->once()
            ->with(77)
            ->andReturn(true);

        $service = new CarBrandService($repo);

        $res = $service->delete(77);

        $this->assertTrue($res);
    }

    // ───────────────────────────────────────────────
    // 5) find()
    // ───────────────────────────────────────────────

    #[Test]
    public function test_find_calls_repository_find(): void
    {
        $repo = Mockery::mock(CarBrandRepository::class);

        $repo->shouldReceive('find')
            ->once()
            ->with(12)
            ->andReturn(['id' => 12, 'name' => 'Kia']);

        $service = new CarBrandService($repo);

        $res = $service->find(12);

        $this->assertSame(['id' => 12, 'name' => 'Kia'], $res);
    }


    // ───────────────────────────────────────────────
    // 6) ADVANCED: exception passthrough
    // ───────────────────────────────────────────────

    #[Test]
    public function test_create_throws_exception_if_repository_throws(): void
    {
        $this->expectException(\Exception::class);

        $repo = Mockery::mock(CarBrandRepository::class);

        $repo->shouldReceive('create')
            ->once()
            ->andThrow(new \Exception('DB error'));

        $service = new CarBrandService($repo);

        $service->create(['name' => 'BMW']);
    }

    // ───────────────────────────────────────────────
    // 7) ADVANCED: return value passthrough
    // ───────────────────────────────────────────────

    #[Test]
    public function test_update_returns_exact_repository_value(): void
    {
        $repo = Mockery::mock(CarBrandRepository::class);

        $expected = (object)['id' => 9, 'name' => 'Toyota'];

        $repo->shouldReceive('update')
            ->once()
            ->andReturn($expected);

        $service = new CarBrandService($repo);

        $res = $service->update(9, ['name' => 'Toyota']);

        $this->assertSame($expected, $res);
    }
}
