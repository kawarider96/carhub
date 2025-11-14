<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Models\CarBrand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CarBrandControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_can_list_car_brands(): void
    {
        CarBrand::factory()->count(3)->create();
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        Sanctum::actingAs($admin);

        $this->getJson('/api/car-brands')
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    #[Test]
    public function non_admin_cannot_list_car_brands(): void
    {
        CarBrand::factory()->count(1)->create();
        $user = User::factory()->create(['role' => 'user', 'is_active' => true]);

        Sanctum::actingAs($user);

        $this->getJson('/api/car-brands')
            ->assertStatus(403);
    }

    #[Test]
    public function guest_gets_unauthorized_for_car_brands(): void
    {
        $this->getJson('/api/car-brands')
            ->assertStatus(401);
    }
}

