<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Api\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
            'password' => Hash::make('Secret123!')
        ]);
    }

    private function makeUser(): User
    {
        return User::factory()->create([
            'role' => 'user',
            'is_active' => true,
            'password' => Hash::make('Secret123!')
        ]);
    }

    // ───────────────────────────────────────────────────────────────
    // INDEX
    // ───────────────────────────────────────────────────────────────
    #[Test]
    public function admin_can_list_users(): void
    {
        $admin = $this->makeAdmin();

        User::factory()->count(3)->create();

        $response = $this->actingAs($admin)
            ->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Felhasználók listája')
            ->assertJsonCount(4, 'data');
    }

    #[Test]
    public function user_cannot_list_users(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)
            ->getJson('/api/users');

        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_list_users(): void
    {
        $response = $this->getJson('/api/users');
        $response->assertStatus(401);
    }

    // ───────────────────────────────────────────────────────────────
    // STORE (adminStore)
    // ───────────────────────────────────────────────────────────────
    #[Test]
    public function admin_can_create_user(): void
    {
        $admin = $this->makeAdmin();

        $payload = [
            'full_name' => 'Teszt Elek',
            'username' => 'tesztuser',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'user',
        ];

        $response = $this->actingAs($admin)
            ->postJson('/api/users', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.username', 'tesztuser');

        $this->assertDatabaseHas('users', ['username' => 'tesztuser']);
    }

    #[Test]
    public function admin_create_user_validates_data(): void
    {
        $admin = $this->makeAdmin();

        $payload = [
            'full_name' => '',
            'username' => '',
            'password' => 'short',
            'password_confirmation' => 'diff'
        ];

        $response = $this->actingAs($admin)
            ->postJson('/api/users', $payload);

        $response->assertStatus(422);
    }

    #[Test]
    public function user_cannot_create_user(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)
            ->postJson('/api/users', [
                'username' => 'testuser',
                'password' => 'pw123456',
                'password_confirmation' => 'pw123456',
                'full_name' => 'Test User',
                'role' => 'user',
            ]);

        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_create_user(): void
    {
        $response = $this->postJson('/api/users', []);
        $response->assertStatus(401);
    }

    // ───────────────────────────────────────────────────────────────
    // SHOW
    // ───────────────────────────────────────────────────────────────
    #[Test]
    public function admin_can_show_user(): void
    {
        $admin = $this->makeAdmin();
        $user = $this->makeUser();

        $response = $this->actingAs($admin)
            ->getJson('/api/users/' . $user->id);

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $user->id);
    }

    #[Test]
    public function show_returns_404_if_not_found(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)
            ->getJson('/api/users/999999');

        $response->assertStatus(404);
    }

    #[Test]
    public function user_cannot_show_user(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)
            ->getJson('/api/users/' . $user->id);

        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_show_user(): void
    {
        $response = $this->getJson('/api/users/1');
        $response->assertStatus(401);
    }

    // ───────────────────────────────────────────────────────────────
    // UPDATE (saját profil)
    // ───────────────────────────────────────────────────────────────
    #[Test]
    public function user_can_update_own_profile(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)
            ->putJson('/api/users', [
                'full_name' => 'Új Név'
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.full_name', 'Új Név');
    }

    #[Test]
    public function update_validates_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->putJson('/api/users', [
                // ehelyett nem üres stringet adunk,
                // hanem mondjuk túl hosszút, hogy validációt triggereljen
                'full_name' => str_repeat('A', 600),
            ]);

        $response->assertStatus(422);
    }

    #[Test]
    public function guest_cannot_update_profile(): void
    {
        $response = $this->putJson('/api/users', []);
        $response->assertStatus(401);
    }

    // ───────────────────────────────────────────────────────────────
    // adminUpdate
    // ───────────────────────────────────────────────────────────────
    #[Test]
    public function admin_can_update_user(): void
    {
        $admin = $this->makeAdmin();
        $user = $this->makeUser();

        $response = $this->actingAs($admin)
            ->putJson('/api/users/' . $user->id, [
                'full_name' => 'Updated Name'
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.full_name', 'Updated Name');
    }

    #[Test]
    public function admin_update_validates_fields(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user  = User::factory()->create();

        $response = $this->actingAs($admin)
            ->putJson("/api/users/{$user->id}", [
                // valódi invalid -> túl hosszú név
                'full_name' => str_repeat('A', 600),
            ]);

        $response->assertStatus(422);
    }

    #[Test]
    public function admin_update_returns_404(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)
            ->putJson('/api/users/999999', [
                'full_name' => 'X'
            ]);

        $response->assertStatus(404);
    }

    #[Test]
    public function non_admin_cannot_adminUpdate(): void
    {
        $user = $this->makeUser();
        $target = $this->makeUser();

        $response = $this->actingAs($user)
            ->putJson('/api/users/' . $target->id, [
                'full_name' => 'X'
            ]);

        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_adminUpdate(): void
    {
        $response = $this->putJson('/api/users/1', []);
        $response->assertStatus(401);
    }

    // ───────────────────────────────────────────────────────────────
    // DESTROY
    // ───────────────────────────────────────────────────────────────
    #[Test]
    public function admin_can_delete_user(): void
    {
        $admin = $this->makeAdmin();
        $user = $this->makeUser();

        $response = $this->actingAs($admin)
            ->deleteJson('/api/users/' . $user->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    #[Test]
    public function delete_returns_404_if_not_found(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)
            ->deleteJson('/api/users/999999');

        $response->assertStatus(404);
    }

    #[Test]
    public function user_cannot_delete_user(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)
            ->deleteJson('/api/users/' . $user->id);

        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_delete_user(): void
    {
        $response = $this->deleteJson('/api/users/1');
        $response->assertStatus(401);
    }

    // ───────────────────────────────────────────────────────────────
    // LOCK
    // ───────────────────────────────────────────────────────────────
    #[Test]
    public function admin_can_lock_user(): void
    {
        $admin = $this->makeAdmin();
        $user = $this->makeUser();

        $response = $this->actingAs($admin)
            ->postJson("/api/users/{$user->id}/lock");

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => false
        ]);
    }

    #[Test]
    public function lock_returns_404(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)
            ->postJson('/api/users/999999/lock');

        $response->assertStatus(404);
    }

    #[Test]
    public function user_cannot_lock_users(): void
    {
        $user = $this->makeUser();
        $target = $this->makeUser();

        $response = $this->actingAs($user)
            ->postJson("/api/users/{$target->id}/lock");

        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_lock_users(): void
    {
        $response = $this->postJson('/api/users/1/lock');
        $response->assertStatus(401);
    }

    // ───────────────────────────────────────────────────────────────
    // UNLOCK
    // ───────────────────────────────────────────────────────────────
    #[Test]
    public function admin_can_unlock_user(): void
    {
        $admin = $this->makeAdmin();
        $user = $this->makeUser();
        $user->update(['is_active' => false]);

        $response = $this->actingAs($admin)
            ->postJson("/api/users/{$user->id}/unlock");

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => true
        ]);
    }

    #[Test]
    public function unlock_returns_404(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)
            ->postJson('/api/users/999999/unlock');

        $response->assertStatus(404);
    }

    #[Test]
    public function user_cannot_unlock_users(): void
    {
        $user = $this->makeUser();
        $target = $this->makeUser();

        $response = $this->actingAs($user)
            ->postJson("/api/users/{$target->id}/unlock");

        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_unlock_users(): void
    {
        $response = $this->postJson('/api/users/1/unlock');
        $response->assertStatus(401);
    }
}
