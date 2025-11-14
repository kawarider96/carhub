<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\AuthService;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[Test]
    public function test_login_fails_with_invalid_username(): void
    {
        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('findByUsername')->once()->with('john')->andReturn(null);

        $service = new AuthService($repo);

        $res = $service->login('john', 'secret');

        $this->assertFalse($res['status']);
        $this->assertEquals('invalid', $res['error']);
    }

    #[Test]
    public function test_login_fails_when_user_inactive(): void
    {
        $user = new class {
            public bool $is_active = false;
        };

        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('findByUsername')->once()->with('jane')->andReturn($user);

        $service = new AuthService($repo);

        $res = $service->login('jane', 'secret');

        $this->assertFalse($res['status']);
        $this->assertEquals('locked', $res['error']);
    }

    #[Test]
    public function test_login_increments_failed_logins_and_locks_after_5(): void
    {
        $user = new class {
            public int $failed_logins = 4;
            public bool $is_active = true;
            public string $password = '$2y$10$dummyhash';
            public int $saved = 0;
            public function save(): void { $this->saved++; }
        };

        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('findByUsername')->once()->with('john')->andReturn($user);

        Hash::shouldReceive('check')->once()->with('wrong', $user->password)->andReturn(false);

        $service = new AuthService($repo);

        $res = $service->login('john', 'wrong');

        $this->assertFalse($res['status']);
        $this->assertEquals('invalid', $res['error']);
        $this->assertEquals(5, $user->failed_logins);
        $this->assertFalse($user->is_active);
        $this->assertEquals(1, $user->saved);
    }

    #[Test]
    public function test_successful_login_resets_failed_logins(): void
    {
        $user = new class {
            public int $failed_logins = 3;
            public bool $is_active = true;
            public string $password = '$2y$10$dummyhash';
            public int $saved = 0;
            public function save(): void { $this->saved++; }
        };

        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('findByUsername')->once()->with('john')->andReturn($user);

        Hash::shouldReceive('check')->once()->with('right', $user->password)->andReturn(true);

        $service = new AuthService($repo);

        $res = $service->login('john', 'right');

        $this->assertTrue($res['status']);
        $this->assertSame($user, $res['user']);
        $this->assertEquals(0, $user->failed_logins);
        $this->assertEquals(1, $user->saved);
    }

    #[Test]
    public function test_register_hashes_password_and_sets_defaults(): void
    {
        $data = ['username' => 'john', 'password' => 'Plain123!'];

        Hash::shouldReceive('make')->once()->with('Plain123!')->andReturn('HASHED');

        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($arg) {
                return $arg['username'] === 'john'
                    && $arg['password'] === 'HASHED'
                    && $arg['is_active'] === true
                    && $arg['failed_logins'] === 0;
            }))
            ->andReturn((object)['id' => 1]);

        $service = new AuthService($repo);
        $res = $service->register($data);

        $this->assertEquals(1, $res->id);
    }
}

