<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Repositories\FavoriteCarRepository;
use App\Services\FavoriteCarService;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FavoriteCarServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[Test]
    public function test_for_user_lists_favorites_for_user(): void
    {
        $repo = Mockery::mock(FavoriteCarRepository::class);
        $repo->shouldReceive('getByUser')->once()->with(3)->andReturn(collect([(object)['id' => 1], (object)['id' => 2]]));

        $service = new FavoriteCarService($repo);
        $res = $service->forUser(3);

        $this->assertCount(2, $res);
    }

    #[Test]
    public function test_create_calls_repository_create(): void
    {
        $repo = Mockery::mock(FavoriteCarRepository::class);
        $input = ['user_id' => 3, 'car_model_id' => 9, 'year' => 2020];
        $repo->shouldReceive('create')->once()->with($input)->andReturn((object)['id' => 11] );

        $service = new FavoriteCarService($repo);
        $res = $service->create($input);
        $this->assertEquals(11, $res->id);
    }

    #[Test]
    public function test_update_calls_repository_update(): void
    {
        $repo = Mockery::mock(FavoriteCarRepository::class);
        $repo->shouldReceive('update')->once()->with(11, ['color' => 'Red'])->andReturn((object)['id' => 11, 'color' => 'Red']);

        $service = new FavoriteCarService($repo);
        $res = $service->update(11, ['color' => 'Red']);
        $this->assertEquals('Red', $res->color);
    }

    #[Test]
    public function test_delete_calls_repository_delete(): void
    {
        $repo = Mockery::mock(FavoriteCarRepository::class);
        $repo->shouldReceive('delete')->once()->with(15)->andReturnTrue();

        $service = new FavoriteCarService($repo);
        $this->assertTrue($service->delete(15));
    }
}

