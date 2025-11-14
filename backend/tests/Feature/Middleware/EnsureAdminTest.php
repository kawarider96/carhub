<?php

declare(strict_types=1);

namespace Tests\Feature\Middleware;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnsureAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_gets_unauthorized(): void
    {
        $this->getJson('/api/users')
            ->assertStatus(401);
    }

    public function test_non_admin_gets_forbidden(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->getJson('/api/users')
            ->assertStatus(403);
    }

    public function test_admin_can_access(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        \Laravel\Sanctum\Sanctum::actingAs($admin);

        $this->getJson('/api/users')
            ->assertOk()
            ->assertJsonStructure(['data']);
    }
}
