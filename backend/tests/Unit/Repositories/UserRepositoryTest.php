<?php

declare(strict_types=1);

namespace Tests\Unit\Repositories;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected UserRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new UserRepository(new User());
    }

    // ─────────────────────────────────────────────
    // 1–2) findByUsername
    // ─────────────────────────────────────────────

    #[Test]
    public function test_find_by_username_found(): void
    {
        User::factory()->create(['username' => 'neo']);

        $found = $this->repo->findByUsername('neo');

        $this->assertNotNull($found);
        $this->assertEquals('neo', $found->username);
    }

    #[Test]
    public function test_find_by_username_not_found(): void
    {
        $found = $this->repo->findByUsername('missing');

        $this->assertNull($found);
    }


    // ─────────────────────────────────────────────
    // 3–4) createUser
    // ─────────────────────────────────────────────

    #[Test]
    public function test_create_user_success(): void
    {
        $user = $this->repo->createUser([
            'full_name' => 'Test User',
            'username' => 'testuser',
            'password' => 'pw',
            'role' => 'user',
            'is_active' => true,
            'failed_logins' => 0
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'username' => 'testuser'
        ]);
    }

    #[Test]
    public function test_create_user_throws_on_duplicate_username(): void
    {
        User::factory()->create(['username' => 'duplicate']);

        $this->expectException(\Exception::class);

        $this->repo->createUser([
            'full_name' => 'X',
            'username' => 'duplicate',
            'password' => 'pw'
        ]);
    }


    // ─────────────────────────────────────────────
    // 5–6) lockUser
    // ─────────────────────────────────────────────

    #[Test]
    public function test_lock_user_success(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $updated = $this->repo->lockUser($user->id);

        $this->assertFalse($updated->is_active);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => false
        ]);
    }

    #[Test]
    public function test_lock_user_not_found(): void
    {
        $this->expectException(\Exception::class);

        $this->repo->lockUser(99999);
    }


    // ─────────────────────────────────────────────
    // 7–8) unlockUser
    // ─────────────────────────────────────────────

    #[Test]
    public function test_unlock_user_success(): void
    {
        $user = User::factory()->create([
            'is_active' => false,
            'failed_logins' => 5
        ]);

        $updated = $this->repo->unlockUser($user->id);

        $this->assertTrue($updated->is_active);
        $this->assertEquals(0, $updated->failed_logins);
    }

    #[Test]
    public function test_unlock_user_not_found(): void
    {
        $this->expectException(\Exception::class);

        $this->repo->unlockUser(99999);
    }


    // ─────────────────────────────────────────────
    // 9–10) incrementFailedLogins
    // ─────────────────────────────────────────────

    #[Test]
    public function test_increment_failed_logins_increments_value(): void
    {
        $user = User::factory()->create([
            'failed_logins' => 0,
            'is_active' => true
        ]);

        $updated = $this->repo->incrementFailedLogins($user);

        $this->assertEquals(1, $updated->failed_logins);
        $this->assertTrue($updated->is_active);
    }

    #[Test]
    public function test_increment_failed_logins_locks_after_5_attempts(): void
    {
        $user = User::factory()->create([
            'failed_logins' => 4,
            'is_active' => true
        ]);

        $updated = $this->repo->incrementFailedLogins($user);

        $this->assertEquals(5, $updated->failed_logins);
        $this->assertFalse($updated->is_active);
    }


    // ─────────────────────────────────────────────
    // 11–12) resetFailedLogins
    // ─────────────────────────────────────────────

    #[Test]
    public function test_reset_failed_logins_works(): void
    {
        $user = User::factory()->create(['failed_logins' => 3]);

        $updated = $this->repo->resetFailedLogins($user);

        $this->assertEquals(0, $updated->failed_logins);
    }

    #[Test]
    public function test_reset_failed_logins_when_already_zero(): void
    {
        $user = User::factory()->create(['failed_logins' => 0]);

        $updated = $this->repo->resetFailedLogins($user);

        $this->assertEquals(0, $updated->failed_logins);
    }


    // ─────────────────────────────────────────────
    // Extra 13–15) Spy & find delegáció
    // ─────────────────────────────────────────────

    #[Test]
    public function test_increment_failed_logins_calls_save_once(): void
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->id = 1;
        $user->failed_logins = 0;
        $user->is_active = true;

        $user->shouldReceive('save')->once();

        $this->repo->incrementFailedLogins($user);
    }

    #[Test]
    public function test_reset_failed_logins_calls_save_once(): void
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->id = 1;
        $user->failed_logins = 4;

        $user->shouldReceive('save')->once();

        $this->repo->resetFailedLogins($user);
    }

    #[Test]
    public function test_lock_user_calls_find_once(): void
    {
        $mock = Mockery::mock(UserRepository::class)->makePartial();
        $mock->shouldReceive('find')->once()->andReturn(
            User::factory()->create(['is_active' => true])
        );

        $mock->lockUser(1);
    }
}
