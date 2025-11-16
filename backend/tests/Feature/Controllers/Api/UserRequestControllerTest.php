<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Api;

use App\Models\User;
use App\Models\UserRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserRequestControllerTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::factory()->create([
            'role'      => 'admin',
            'is_active' => true,
            'password'  => Hash::make('Secret123!')
        ]);
    }

    private function makeUser(): User
    {
        return User::factory()->create([
            'role'      => 'user',
            'is_active' => true,
            'password'  => Hash::make('Secret123!')
        ]);
    }

    // ───────────────────────────────────────────────
    // INDEX (admin only)
    // ───────────────────────────────────────────────

    #[Test]
    public function admin_can_list_requests(): void
    {
        $admin = $this->makeAdmin();

        UserRequest::factory()->count(3)->create();

        $response = $this->actingAs($admin)
            ->getJson('/api/requests');

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Kérések listája')
            ->assertJsonCount(3, 'data');
    }

    #[Test]
    public function user_cannot_list_requests(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)
            ->getJson('/api/requests');

        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_list_requests(): void
    {
        $response = $this->getJson('/api/requests');
        $response->assertStatus(401);
    }

    // ───────────────────────────────────────────────
    // STORE – user creates own deletion request
    // ───────────────────────────────────────────────

    #[Test]
    public function user_can_create_request(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)
            ->postJson('/api/requests', [
                'type' => 'delete_account',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('message', 'Kérés sikeresen létrehozva')
            ->assertJsonPath('data.user_id', $user->id);

        $this->assertDatabaseHas('user_requests', [
            'user_id' => $user->id,
            'status'  => 'open',
            'type'    => 'delete_account',
        ]);
    }

    #[Test]
    public function user_cannot_create_duplicate_request(): void
    {
        $user = $this->makeUser();

        // első OPEN request ugyanarra a userre + type-ra
        UserRequest::factory()->create([
            'user_id' => $user->id,
            'status'  => 'open',
            'type'    => 'delete_account',
        ]);

        $response = $this->actingAs($user)
            ->postJson('/api/requests', [
                'type' => 'delete_account',
            ]);

        $response->assertStatus(409)
            ->assertJsonPath('message', 'Már létezik nyitott kérés');
    }

    #[Test]
    public function admin_cannot_create_request(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)
            ->postJson('/api/requests', [
                'type' => 'delete_account',
            ]);

        // üzleti logika szerint TILOS adminnak kérelmet létrehozni
        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_create_request(): void
    {
        $response = $this->postJson('/api/requests', [
            'type' => 'delete_account',
        ]);

        $response->assertStatus(401);
    }

    // ───────────────────────────────────────────────
    // APPROVE – admin approves request
    // ───────────────────────────────────────────────

    #[Test]
    public function admin_can_approve_request(): void
    {
        $admin = $this->makeAdmin();
        $user  = $this->makeUser();

        $request = UserRequest::factory()->create([
            'user_id' => $user->id,
            'status'  => 'open',
            'type'    => 'delete_account',
        ]);

        $response = $this->actingAs($admin)
            ->postJson("/api/requests/{$request->id}/approve");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Kérés jóváhagyva')
            ->assertJsonPath('data.status', 'approved')
            ->assertJsonPath('data.handled_by', $admin->id);

        // user törlődik
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    #[Test]
    public function user_cannot_approve_request(): void
    {
        $user  = $this->makeUser();
        $other = $this->makeUser();

        $request = UserRequest::factory()->create([
            'user_id' => $other->id,
            'status'  => 'pending',
        ]);

        $response = $this->actingAs($user)
            ->postJson("/api/requests/{$request->id}/approve");

        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_approve_request(): void
    {
        $request = UserRequest::factory()->create();

        $response = $this->postJson("/api/requests/{$request->id}/approve");

        $response->assertStatus(401);
    }

    #[Test]
    public function approve_request_returns_404_if_not_found(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)
            ->postJson('/api/requests/999999/approve');

        $response->assertStatus(404);
    }

    // ───────────────────────────────────────────────
    // REJECT – admin rejects request
    // ───────────────────────────────────────────────

    #[Test]
    public function admin_can_reject_request(): void
    {
        $admin = $this->makeAdmin();
        $user  = $this->makeUser();

        $request = UserRequest::factory()->create([
            'user_id' => $user->id,
            'status'  => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->postJson("/api/requests/{$request->id}/reject");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Kérés elutasítva')
            ->assertJsonPath('data.status', 'rejected')
            ->assertJsonPath('data.handled_by', $admin->id);

        // user NEM törlődik
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    #[Test]
    public function user_cannot_reject_request(): void
    {
        $user  = $this->makeUser();
        $other = $this->makeUser();

        $request = UserRequest::factory()->create([
            'user_id' => $other->id,
            'status'  => 'pending',
        ]);

        $response = $this->actingAs($user)
            ->postJson("/api/requests/{$request->id}/reject");

        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_reject_request(): void
    {
        $request = UserRequest::factory()->create();

        $response = $this->postJson("/api/requests/{$request->id}/reject");

        $response->assertStatus(401);
    }

    #[Test]
    public function reject_request_returns_404_if_not_found(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)
            ->postJson('/api/requests/999999/reject');

        $response->assertStatus(404);
    }
}
