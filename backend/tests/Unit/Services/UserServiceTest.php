<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
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

    // ───────────────────────────────────────────────
    // 1–6: Delegáló metódusok (all, find, update, delete, lock, unlock)
    // ───────────────────────────────────────────────

    #[Test]
    public function test_all_delegates_to_repository(): void
    {
        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('all')->once()->andReturn(['u1', 'u2']);

        $service = new UserService($repo);

        $res = $service->all();

        $this->assertEquals(['u1', 'u2'], $res);
    }

    #[Test]
    public function test_find_delegates_to_repository(): void
    {
        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('find')
            ->once()
            ->with(10)
            ->andReturn(['id' => 10]);

        $service = new UserService($repo);

        $res = $service->find(10);

        $this->assertEquals(['id' => 10], $res);
    }

    #[Test]
    public function test_update_delegates_to_repository(): void
    {
        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('update')
            ->once()
            ->with(5, ['full_name' => 'Neo'])
            ->andReturn(['id' => 5, 'full_name' => 'Neo']);

        $service = new UserService($repo);

        $res = $service->update(5, ['full_name' => 'Neo']);

        $this->assertEquals(['id' => 5, 'full_name' => 'Neo'], $res);
    }

    #[Test]
    public function test_delete_delegates_to_repository(): void
    {
        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('delete')
            ->once()
            ->with(3)
            ->andReturn(true);

        $service = new UserService($repo);

        $res = $service->delete(3);

        $this->assertTrue($res);
    }

    #[Test]
    public function test_lock_delegates_to_repository(): void
    {
        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('lockUser')
            ->once()
            ->with(7)
            ->andReturn(['id' => 7, 'is_active' => false]);

        $service = new UserService($repo);

        $res = $service->lock(7);

        $this->assertEquals(['id' => 7, 'is_active' => false], $res);
    }

    #[Test]
    public function test_unlock_delegates_to_repository(): void
    {
        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('unlockUser')
            ->once()
            ->with(8)
            ->andReturn(['id' => 8, 'is_active' => true]);

        $service = new UserService($repo);

        $res = $service->unlock(8);

        $this->assertEquals(['id' => 8, 'is_active' => true], $res);
    }

    // ───────────────────────────────────────────────
    // LOGIN – áganként
    // ───────────────────────────────────────────────

    // 7) user not found
    #[Test]
    public function test_login_returns_wrong_credentials_when_user_not_found(): void
    {
        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('findByUsername')
            ->once()
            ->with('neo')
            ->andReturn(null);

        Hash::shouldReceive('check')->never();

        $service = new UserService($repo);

        $res = $service->login([
            'username' => 'neo',
            'password' => 'pw',
        ]);

        $this->assertEquals('wrong_credentials', $res);
    }

    // 8) user inactive
    #[Test]
    public function test_login_returns_locked_when_user_inactive(): void
    {
        $user = new User();
        $user->is_active = false;

        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('findByUsername')
            ->once()
            ->with('neo')
            ->andReturn($user);

        Hash::shouldReceive('check')->never();

        $service = new UserService($repo);

        $res = $service->login([
            'username' => 'neo',
            'password' => 'pw',
        ]);

        $this->assertEquals('locked', $res);
    }

    // 9) wrong pw → incrementFailedLogins + wrong_credentials
    #[Test]
    public function test_login_wrong_password_increments_failed_logins_and_returns_wrong_credentials(): void
    {
        $user = new User();
        $user->is_active = true;
        $user->password = 'hashed';

        Hash::shouldReceive('check')
            ->once()
            ->with('pw', Mockery::any())
            ->andReturn(false);

        $repo = Mockery::mock(UserRepository::class);

        $repo->shouldReceive('findByUsername')
            ->once()
            ->with('neo')
            ->andReturn($user);

        $repo->shouldReceive('incrementFailedLogins')
            ->once()
            ->with($user);

        $repo->shouldReceive('resetFailedLogins')->never();

        $userMock = Mockery::mock($user)->makePartial();
        $userMock->shouldReceive('createToken')->never();

        $service = new UserService($repo);

        $res = $service->login([
            'username' => 'neo',
            'password' => 'pw',
        ]);

        $this->assertEquals('wrong_credentials', $res);
    }

    // 10) wrong pw – incrementFailedLogins exception
    #[Test]
    public function test_login_throws_if_increment_failed_logins_throws(): void
    {
        $this->expectException(\Exception::class);

        $user = new User();
        $user->is_active = true;
        $user->password = 'hashed';

        $repo = Mockery::mock(UserRepository::class);

        $repo->shouldReceive('findByUsername')->andReturn($user);

        Hash::shouldReceive('check')->andReturn(false);

        $repo->shouldReceive('incrementFailedLogins')
            ->once()
            ->with($user)
            ->andThrow(new \Exception('increment error'));

        $service = new UserService($repo);

        $service->login([
            'username' => 'neo',
            'password' => 'pw',
        ]);
    }

    // 11) success → resetFailedLogins hívódik
    #[Test]
    public function test_login_success_resets_failed_logins(): void
    {
        $user = new User();
        $user->id = 1;
        $user->full_name = 'Neo';
        $user->role = 'user';
        $user->is_active = true;
        $user->password = 'hashed';

        $tokenObj = new class {
            public string $plainTextToken = 'login-token';
        };

        $userMock = Mockery::mock($user)->makePartial();
        $userMock->shouldReceive('createToken')
            ->once()
            ->with('auth_token')
            ->andReturn($tokenObj);

        Hash::shouldReceive('check')->once()->andReturn(true);

        $repo = Mockery::mock(UserRepository::class);

        $repo->shouldReceive('findByUsername')
            ->once()
            ->with('neo')
            ->andReturn($userMock);

        $repo->shouldReceive('resetFailedLogins')
            ->once()
            ->with($userMock);

        $repo->shouldReceive('incrementFailedLogins')->never();

        $service = new UserService($repo);

        $res = $service->login([
            'username' => 'neo',
            'password' => 'pw',
        ]);

        $this->assertEquals('login-token', $res['token']);
        $this->assertEquals('Neo', $res['user']['full_name']);
    }

    // 12) success – struktúra ellenőrzés (token + user)
    #[Test]
    public function test_login_success_returns_token_and_user_structure(): void
    {
        $user = new User();
        $user->id = 2;
        $user->full_name = 'Test User';
        $user->role = 'admin';
        $user->is_active = true;
        $user->password = 'hashed';

        $tokenObject = new class {
            public string $plainTextToken = 'xyz-token';
        };

        $userMock = Mockery::mock($user)->makePartial();
        $userMock->shouldReceive('createToken')
            ->once()
            ->with('auth_token')
            ->andReturn($tokenObject);

        $repo = Mockery::mock(UserRepository::class);

        $repo->shouldReceive('findByUsername')->andReturn($userMock);
        $repo->shouldReceive('resetFailedLogins')->once()->with($userMock);

        Hash::shouldReceive('check')->once()->andReturn(true);

        $service = new UserService($repo);

        $res = $service->login([
            'username' => 'test',
            'password' => 'pw',
        ]);

        $this->assertArrayHasKey('token', $res);
        $this->assertArrayHasKey('user', $res);
        $this->assertEquals('xyz-token', $res['token']);
        $this->assertEquals(2, $res['user']['id']);
        $this->assertEquals('Test User', $res['user']['full_name']);
        $this->assertEquals('admin', $res['user']['role']);
    }

    // 13) resetFailedLogins exception
    #[Test]
    public function test_login_throws_if_reset_failed_logins_fails(): void
    {
        $this->expectException(\Exception::class);

        $user = new User();
        $user->is_active = true;
        $user->password = 'hashed';

        $repo = Mockery::mock(UserRepository::class);

        $repo->shouldReceive('findByUsername')->andReturn($user);

        Hash::shouldReceive('check')->andReturn(true);

        $repo->shouldReceive('resetFailedLogins')
            ->once()
            ->with($user)
            ->andThrow(new \Exception('reset error'));

        $service = new UserService($repo);

        $service->login([
            'username' => 'neo',
            'password' => 'pw',
        ]);
    }

    // 14) createToken exception
    #[Test]
    public function test_login_throws_if_create_token_fails(): void
    {
        $this->expectException(\Exception::class);

        $user = Mockery::mock(User::class)->makePartial();
        $user->is_active = true;
        $user->password = 'hashed';

        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('findByUsername')->andReturn($user);
        $repo->shouldReceive('resetFailedLogins')->once()->with($user);

        Hash::shouldReceive('check')->andReturn(true);

        $user->shouldReceive('createToken')
            ->once()
            ->with('auth_token')
            ->andThrow(new \Exception('token error'));

        $service = new UserService($repo);

        $service->login([
            'username' => 'neo',
            'password' => 'pw',
        ]);
    }

    // 15) wrong pw esetén sose hívódjon createToken
    #[Test]
    public function test_login_never_calls_create_token_on_wrong_password(): void
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->is_active = true;
        $user->password = 'hashed';

        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('findByUsername')->andReturn($user);
        $repo->shouldReceive('incrementFailedLogins')->once()->with($user);

        Hash::shouldReceive('check')->andReturn(false);

        $user->shouldReceive('createToken')->never();

        $service = new UserService($repo);

        $res = $service->login([
            'username' => 'neo',
            'password' => 'wrong',
        ]);

        $this->assertEquals('wrong_credentials', $res);
    }

    // ───────────────────────────────────────────────
    // REGISTER – 16–21
    // ───────────────────────────────────────────────

    // 16) Hash::make hívódik
    #[Test]
    public function test_register_hashes_password_with_hash_make(): void
    {
        Hash::shouldReceive('make')
            ->once()
            ->with('plainpw')
            ->andReturn('hashedpw');

        $user = Mockery::mock(User::class)->makePartial();
        $user->id = 1;
        $user->full_name = 'John Doe';
        $user->username = 'jdoe';
        $user->role = 'user';

        $tokenObj = new class {
            public string $plainTextToken = 'reg-token';
        };

        $user->shouldReceive('createToken')
            ->once()
            ->with('auth_token')
            ->andReturn($tokenObj);

        $repo = Mockery::mock(UserRepository::class);

        $repo->shouldReceive('createUser')
            ->once()
            ->andReturn($user);

        $service = new UserService($repo);

        $res = $service->register([
            'full_name' => 'John Doe',
            'username' => 'jdoe',
            'password' => 'plainpw',
        ]);

        $this->assertEquals('reg-token', $res['token']);
    }

    // 17) createUser helyes adattal
    #[Test]
    public function test_register_calls_create_user_with_correct_data(): void
    {
        $repo = Mockery::mock(UserRepository::class);

        Hash::shouldReceive('make')->andReturn('hashedpw');

        $user = Mockery::mock(User::class)->makePartial();
        $user->id = 2;
        $user->full_name = 'Jane Doe';
        $user->username = 'jane';
        $user->role = 'user';

        $repo->shouldReceive('createUser')
            ->once()
            ->with(Mockery::on(function ($data) {
                return $data['full_name'] === 'Jane Doe'
                    && $data['username'] === 'jane'
                    && $data['password'] === 'hashedpw'
                    && $data['role'] === 'user'
                    && $data['is_active'] === true
                    && $data['failed_logins'] === 0;
            }))
            ->andReturn($user);

        $tokenObject = new class {
            public string $plainTextToken = 'jane-token';
        };

        $user->shouldReceive('createToken')
            ->once()
            ->with('auth_token')
            ->andReturn($tokenObject);

        $service = new UserService($repo);

        $res = $service->register([
            'full_name' => 'Jane Doe',
            'username'  => 'jane',
            'password'  => 'pw',
        ]);

        $this->assertEquals('Jane Doe', $res['full_name']);
        $this->assertEquals('jane', $res['username']);
        $this->assertEquals('user', $res['role']);
        $this->assertEquals('jane-token', $res['token']);
    }

    // 18) teljes response struktúra
    #[Test]
    public function test_register_returns_expected_structure(): void
    {
        $repo = Mockery::mock(UserRepository::class);

        Hash::shouldReceive('make')->andReturn('hashed');

        $user = Mockery::mock(User::class)->makePartial();
        $user->id = 3;
        $user->full_name = 'Max Payne';
        $user->username = 'max';
        $user->role = 'user';

        $repo->shouldReceive('createUser')->andReturn($user);

        $tokenObject = new class {
            public string $plainTextToken = 'max-token';
        };

        $user->shouldReceive('createToken')
            ->once()
            ->with('auth_token')
            ->andReturn($tokenObject);

        $service = new UserService($repo);

        $res = $service->register([
            'full_name' => 'Max Payne',
            'username'  => 'max',
            'password'  => 'pw',
        ]);

        $this->assertEquals(3, $res['id']);
        $this->assertEquals('Max Payne', $res['full_name']);
        $this->assertEquals('max', $res['username']);
        $this->assertEquals('user', $res['role']);
        $this->assertEquals('max-token', $res['token']);
    }

    // 19) createUser exception
    #[Test]
    public function test_register_throws_if_create_user_fails(): void
    {
        $this->expectException(\Exception::class);

        $repo = Mockery::mock(UserRepository::class);

        Hash::shouldReceive('make')->andReturn('hashedpw');

        $repo->shouldReceive('createUser')
            ->once()
            ->andThrow(new \Exception('create error'));

        $service = new UserService($repo);

        $service->register([
            'full_name' => 'X',
            'username'  => 'x',
            'password'  => 'pw',
        ]);
    }

    // 20) Hash::make exception
    #[Test]
    public function test_register_throws_if_hash_make_fails(): void
    {
        $this->expectException(\Exception::class);

        $repo = Mockery::mock(UserRepository::class);

        Hash::shouldReceive('make')
            ->once()
            ->andThrow(new \Exception('hash error'));

        $service = new UserService($repo);

        $service->register([
            'full_name' => 'X',
            'username'  => 'x',
            'password'  => 'pw',
        ]);
    }

    // 21) createToken exception
    #[Test]
    public function test_register_throws_if_create_token_fails(): void
    {
        $this->expectException(\Exception::class);

        $repo = Mockery::mock(UserRepository::class);

        Hash::shouldReceive('make')->andReturn('hashed');

        $user = Mockery::mock(User::class)->makePartial();
        $user->id = 4;
        $user->full_name = 'Token Fail';
        $user->username = 'tok';
        $user->role = 'user';

        $repo->shouldReceive('createUser')->andReturn($user);

        $user->shouldReceive('createToken')
            ->once()
            ->with('auth_token')
            ->andThrow(new \Exception('token fail'));

        $service = new UserService($repo);

        $service->register([
            'full_name' => 'Token Fail',
            'username'  => 'tok',
            'password'  => 'pw',
        ]);
    }
}
