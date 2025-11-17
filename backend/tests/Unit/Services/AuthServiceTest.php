<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\AuthService;
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

    // ───────────────────────────────────────────────
    // REGISTER
    // ───────────────────────────────────────────────

    #[Test]
    public function test_register_success(): void
    {
        Hash::shouldReceive('make')
            ->once()
            ->with('secret123')
            ->andReturn('hashed_pass');

        $repo = Mockery::mock(UserRepository::class);

        $repo->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($data) {
                return $data['password'] === 'hashed_pass'
                    && $data['is_active'] === true
                    && $data['failed_logins'] === 0;
            }))
            ->andReturn(['id' => 1, 'username' => 'neo']);

        $service = new AuthService($repo);

        $res = $service->register([
            'username' => 'neo',
            'password' => 'secret123'
        ]);

        $this->assertEquals(['id' => 1, 'username' => 'neo'], $res);
    }

    #[Test]
    public function test_register_repository_throws_exception(): void
    {
        Hash::shouldReceive('make')->andReturn('hashed');

        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('create')->andThrow(new \Exception('DB error'));

        $service = new AuthService($repo);

        $this->expectException(\Exception::class);

        $service->register(['username' => 'x', 'password' => 'y']);
    }

    // ───────────────────────────────────────────────
    // LOGIN – USER NOT FOUND
    // ───────────────────────────────────────────────

    #[Test]
    public function test_login_user_not_found(): void
    {
        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('findByUsername')->once()->with('neo')->andReturn(null);

        $service = new AuthService($repo);

        $res = $service->login('neo', 'pw');

        $this->assertFalse($res['status']);
        $this->assertEquals('invalid', $res['error']);
    }

    // ───────────────────────────────────────────────
    // LOGIN – USER LOCKED
    // ───────────────────────────────────────────────

    #[Test]
    public function test_login_user_locked(): void
    {
        $user = new User();
        $user->is_active = false;

        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('findByUsername')->once()->andReturn($user);

        $service = new AuthService($repo);

        $res = $service->login('neo', 'pw');

        $this->assertFalse($res['status']);
        $this->assertEquals('locked', $res['error']);
    }

    // ───────────────────────────────────────────────
    // LOGIN – WRONG PASSWORD (<5 hibánál)
    // ───────────────────────────────────────────────

    #[Test]
    public function test_login_wrong_password_normal_fail(): void
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->is_active = true;
        $user->failed_logins = 3;
        $user->password = 'stored_hash';

        Hash::shouldReceive('check')
        ->once()
        ->with('pw', Mockery::any())
        ->andReturn(false);

        $user->shouldReceive('save')->once();

        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('findByUsername')->once()->andReturn($user);

        $service = new AuthService($repo);

        $res = $service->login('neo', 'pw');

        $this->assertFalse($res['status']);
        $this->assertEquals('invalid', $res['error']);
        $this->assertEquals(4, $user->failed_logins);
        $this->assertTrue($user->is_active);
    }

    // ───────────────────────────────────────────────
    // LOGIN – WRONG PASSWORD → ACCOUNT LOCK (5. hibánál)
    // ───────────────────────────────────────────────

    #[Test]
    public function test_login_wrong_password_causes_lockout(): void
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->is_active = true;
        $user->failed_logins = 4;
        $user->password = 'hash';

        Hash::shouldReceive('check')->once()->andReturn(false);

        $user->shouldReceive('save')->once();

        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('findByUsername')->once()->andReturn($user);

        $service = new AuthService($repo);

        $res = $service->login('neo', 'pw');

        $this->assertFalse($res['status']);
        $this->assertEquals('invalid', $res['error']);
        $this->assertEquals(5, $user->failed_logins);
        $this->assertFalse($user->is_active);
    }

    // ───────────────────────────────────────────────
    // LOGIN – SUCCESS (failed_logins = 0)
    // ───────────────────────────────────────────────

    #[Test]
    public function test_login_success(): void
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->is_active = true;
        $user->failed_logins = 0;
        $user->password = 'hash';

        Hash::shouldReceive('check')->once()->andReturn(true);

        $user->shouldReceive('save')->once();

        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('findByUsername')->once()->andReturn($user);

        $service = new AuthService($repo);

        $res = $service->login('neo', 'pw');

        $this->assertTrue($res['status']);
        $this->assertSame($user, $res['user']);
        $this->assertEquals(0, $user->failed_logins);
    }

    // ───────────────────────────────────────────────
    // LOGIN – SUCCESS (reseteli a hibákat)
    // ───────────────────────────────────────────────

    #[Test]
    public function test_login_success_resets_failed_logins(): void
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->is_active = true;
        $user->failed_logins = 3;
        $user->password = 'hash';

        Hash::shouldReceive('check')->once()->andReturn(true);

        $user->shouldReceive('save')->once();

        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('findByUsername')->once()->andReturn($user);

        $service = new AuthService($repo);

        $res = $service->login('neo', 'pw');

        $this->assertTrue($res['status']);
        $this->assertEquals(0, $user->failed_logins);
    }
}
