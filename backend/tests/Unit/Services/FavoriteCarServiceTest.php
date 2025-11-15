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

    // ───────────────────────────────────────────────
    // 1) forUser()
    // ───────────────────────────────────────────────

    #[Test]
    public function test_for_user_calls_repository_get_by_user(): void
    {
        $repo = Mockery::mock(FavoriteCarRepository::class);

        $repo->shouldReceive('getByUser')
            ->once()
            ->with(10)
            ->andReturn(['car1', 'car2']);

        $service = new FavoriteCarService($repo);

        $res = $service->forUser(10);

        $this->assertEquals(['car1', 'car2'], $res);
    }

    // ───────────────────────────────────────────────
    // 2) create()
    // ───────────────────────────────────────────────

    #[Test]
    public function test_create_calls_repository_create_with_data(): void
    {
        $repo = Mockery::mock(FavoriteCarRepository::class);

        $repo->shouldReceive('create')
            ->once()
            ->with(['user_id' => 1, 'car_model_id' => 5])
            ->andReturn(['id' => 99]);

        $service = new FavoriteCarService($repo);

        $res = $service->create(['user_id' => 1, 'car_model_id' => 5]);

        $this->assertEquals(['id' => 99], $res);
    }

    // ───────────────────────────────────────────────
    // 3) update()
    // ───────────────────────────────────────────────

    #[Test]
    public function test_update_calls_repository_update(): void
    {
        $repo = Mockery::mock(FavoriteCarRepository::class);

        $repo->shouldReceive('update')
            ->once()
            ->with(15, ['color' => 'red'])
            ->andReturn(['id' => 15, 'color' => 'red']);

        $service = new FavoriteCarService($repo);

        $res = $service->update(15, ['color' => 'red']);

        $this->assertEquals(['id' => 15, 'color' => 'red'], $res);
    }

    // ───────────────────────────────────────────────
    // 4) delete()
    // ───────────────────────────────────────────────

    #[Test]
    public function test_delete_calls_repository_delete(): void
    {
        $repo = Mockery::mock(FavoriteCarRepository::class);

        $repo->shouldReceive('delete')
            ->once()
            ->with(44)
            ->andReturn(true);

        $service = new FavoriteCarService($repo);

        $res = $service->delete(44);

        $this->assertTrue($res);
    }

    // ───────────────────────────────────────────────
    // 5) ADVANCED: exception passthrough
    // ───────────────────────────────────────────────

    #[Test]
    public function test_create_throws_if_repository_throws(): void
    {
        $this->expectException(\Exception::class);

        $repo = Mockery::mock(FavoriteCarRepository::class);

        $repo->shouldReceive('create')
            ->once()
            ->andThrow(new \Exception('DB error'));

        $service = new FavoriteCarService($repo);

        $service->create(['x' => 'y']);
    }

    // ───────────────────────────────────────────────
    // 6) ADVANCED: return passthrough
    // ───────────────────────────────────────────────

    #[Test]
    public function test_for_user_returns_exact_repository_result(): void
    {
        $repo = Mockery::mock(FavoriteCarRepository::class);

        $expected = (object)['foo' => 'bar'];

        $repo->shouldReceive('getByUser')
            ->once()
            ->andReturn($expected);

        $service = new FavoriteCarService($repo);

        $res = $service->forUser(1);

        $this->assertSame($expected, $res);
    }

    // ───────────────────────────────────────────────
    // 7) ADVANCED: return type consistency
    // ───────────────────────────────────────────────

    #[Test]
    public function test_delete_returns_exact_bool_or_value(): void
    {
        $repo = Mockery::mock(FavoriteCarRepository::class);

        $repo->shouldReceive('delete')
            ->once()
            ->andReturn(false);

        $service = new FavoriteCarService($repo);

        $res = $service->delete(55);

        $this->assertFalse($res);
    }
}
