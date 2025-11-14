<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Repositories\CarBrandRepository;
use App\Services\CarBrandService;
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

    #[Test]
    public function test_create_brand_fails_when_exists(): void
    {
        $repo = Mockery::mock(CarBrandRepository::class);
        $repo->shouldReceive('existsByName')->once()->with('BMW')->andReturn(true);

        $service = new CarBrandService($repo);
        $res = $service->create(['name' => 'BMW']);

        $this->assertIsArray($res);
        $this->assertFalse($res['status']);
        $this->assertEquals('exists', $res['error']);
    }

    #[Test]
    public function test_create_brand_when_not_exists_calls_repository(): void
    {
        $repo = Mockery::mock(CarBrandRepository::class);
        $repo->shouldReceive('existsByName')->once()->with('Audi')->andReturn(false);
        $repo->shouldReceive('create')->once()->with(['name' => 'Audi'])->andReturn((object)['id' => 1, 'name' => 'Audi']);

        $service = new CarBrandService($repo);
        $res = $service->create(['name' => 'Audi']);

        $this->assertIsObject($res);
        $this->assertEquals('Audi', $res->name);
    }
}

