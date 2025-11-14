<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CarModelControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_can_list_car_models(): void
    {
        $brand = CarBrand::factory()->create();
        CarModel::factory()->count(2)->create(['car_brand_id' => $brand->id]);

        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        Sanctum::actingAs($admin);

        $this->getJson('/api/car-models')
            ->assertOk()
            ->assertJsonStructure(['data' => [['id','car_brand_id','name']]]);
    }

    #[Test]
    public function non_admin_cannot_list_car_models(): void
    {
        $user = User::factory()->create(['role' => 'user', 'is_active' => true]);
        Sanctum::actingAs($user);

        $this->getJson('/api/car-models')
            ->assertStatus(403);
    }

    #[Test]
    public function guest_gets_unauthorized_for_car_models(): void
    {
        $this->getJson('/api/car-models')
            ->assertStatus(401);
    }
}

