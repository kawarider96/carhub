<?php

declare(strict_types=1);

namespace Tests\Unit\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BaseRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected BaseRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        // User modellt használjuk mint teszt-model
        $this->repo = new class(new User) extends BaseRepository {};
    }

    // ─────────────────────────────────────────────
    // 1–2) all()
    // ─────────────────────────────────────────────

    #[Test]
    public function test_all_returns_all_records(): void
    {
        User::factory()->count(3)->create();

        $result = $this->repo->all();

        $this->assertCount(3, $result);
    }

    #[Test]
    public function test_all_returns_empty_collection_when_no_records(): void
    {
        $result = $this->repo->all();

        $this->assertCount(0, $result);
    }


    // ─────────────────────────────────────────────
    // 3–4) find()
    // ─────────────────────────────────────────────

    #[Test]
    public function test_find_returns_correct_record(): void
    {
        $user = User::factory()->create();

        $found = $this->repo->find($user->id);

        $this->assertEquals($user->id, $found->id);
    }

    #[Test]
    public function test_find_throws_exception_for_missing_record(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repo->find(999999);
    }


    // ─────────────────────────────────────────────
    // 5) create()
    // ─────────────────────────────────────────────

    #[Test]
    public function test_create_inserts_record_into_database(): void
    {
        $created = $this->repo->create([
            'full_name' => 'Test User',
            'username' => 'testuser',
            'password' => 'pw',
            'role' => 'user',
            'is_active' => true,
            'failed_logins' => 0
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $created->id,
            'username' => 'testuser'
        ]);
    }


    // ─────────────────────────────────────────────
    // 6–7) update()
    // ─────────────────────────────────────────────

    #[Test]
    public function test_update_modifies_existing_record(): void
    {
        $user = User::factory()->create(['full_name' => 'Old Name']);

        $updated = $this->repo->update($user->id, ['full_name' => 'New Name']);

        $this->assertEquals('New Name', $updated->full_name);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'full_name' => 'New Name'
        ]);
    }

    #[Test]
    public function test_update_throws_exception_for_missing_record(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repo->update(999999, ['full_name' => 'Something']);
    }


    // ─────────────────────────────────────────────
    // 8–9) delete()
    // ─────────────────────────────────────────────

    #[Test]
    public function test_delete_removes_record_from_database(): void
    {
        $user = User::factory()->create();

        $this->repo->delete($user->id);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }

    #[Test]
    public function test_delete_throws_exception_for_missing_record(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repo->delete(999999);
    }
}
