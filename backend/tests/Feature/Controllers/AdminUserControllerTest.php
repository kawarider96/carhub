<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminUserControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_can_list_users(): void
    {
        User::factory()->count(2)->create();
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        Sanctum::actingAs($admin);

        $this->getJson('/api/users')
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    #[Test]
    public function non_admin_cannot_list_users(): void
    {
        $user = User::factory()->create(['role' => 'user', 'is_active' => true]);
        Sanctum::actingAs($user);

        $this->getJson('/api/users')
            ->assertStatus(403);
    }

    #[Test]
    public function guest_gets_unauthorized(): void
    {
        $this->getJson('/api/users')
            ->assertStatus(401);
    }
}

