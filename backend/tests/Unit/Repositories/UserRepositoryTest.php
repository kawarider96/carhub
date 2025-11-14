<?php

namespace Tests\Unit\Repositories;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected UserRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new UserRepository(new User);
    }

    #[Test]
    public function test_it_creates_user()
    {
        $user = $this->repo->create([
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
    public function test_find_by_username()
    {
        User::factory()->create(['username' => 'john']);

        $found = $this->repo->findByUsername('john');

        $this->assertNotNull($found);
        $this->assertEquals('john', $found->username);
    }

    #[Test]
    public function test_lock_user()
    {
        $user = User::factory()->create(['is_active' => true]);

        $updated = $this->repo->lockUser($user->id);

        $this->assertFalse($updated->is_active);
    }

    #[Test]
    public function test_unlock_user()
    {
        $user = User::factory()->create([
            'is_active' => false,
            'failed_logins' => 5
        ]);

        $updated = $this->repo->unlockUser($user->id);

        $this->assertTrue($updated->is_active);
        $this->assertEquals(0, $updated->failed_logins);
    }
}
