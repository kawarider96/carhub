<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Repositories\UserRepository;
use App\Services\UserService;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[Test]
    public function test_update_invokes_repository_update(): void
    {
        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('update')->once()->with(10, ['full_name' => 'John Doe'])->andReturn((object)['id' => 10]);

        $service = new UserService($repo);
        $res = $service->update(10, ['full_name' => 'John Doe']);

        $this->assertEquals(10, $res->id);
    }

    #[Test]
    public function test_lock_calls_lockUser(): void
    {
        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('lockUser')->once()->with(5)->andReturn((object)['id' => 5, 'is_active' => false]);

        $service = new UserService($repo);
        $res = $service->lock(5);

        $this->assertFalse($res->is_active);
    }

    #[Test]
    public function test_unlock_calls_unlockUser_and_resets_failed_logins(): void
    {
        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('unlockUser')->once()->with(7)->andReturn((object)['id' => 7, 'is_active' => true, 'failed_logins' => 0]);

        $service = new UserService($repo);
        $res = $service->unlock(7);

        $this->assertTrue($res->is_active);
        $this->assertEquals(0, $res->failed_logins);
    }

    #[Test]
    public function test_delete_invokes_repository_delete(): void
    {
        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('delete')->once()->with(9)->andReturnTrue();

        $service = new UserService($repo);
        $this->assertTrue($service->delete(9));
    }
}

