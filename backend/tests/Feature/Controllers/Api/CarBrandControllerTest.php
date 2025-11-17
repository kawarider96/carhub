<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Api\Admin;

use App\Models\CarBrand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CarBrandControllerTest extends TestCase
{
    use RefreshDatabase;

    //────────────────────────────────────────────
    // Helper – create admin / user
    //────────────────────────────────────────────
    private function makeAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    private function makeUser(): User
    {
        return User::factory()->create([
            'role' => 'user',
            'is_active' => true,
        ]);
    }

    //────────────────────────────────────────────
    // INDEX
    //────────────────────────────────────────────

    #[Test]
    public function admin_can_list_brands(): void
    {
        $admin = $this->makeAdmin();
        CarBrand::factory()->count(3)->create();

        $response = $this->actingAs($admin)
            ->getJson('/api/car-brands');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [['id', 'name']]
            ]);
    }

    #[Test]
    public function user_can_list_brands(): void
    {
        $user = $this->makeUser();
        CarBrand::factory()->count(2)->create();

        $response = $this->actingAs($user)
            ->getJson('/api/car-brands');

        $response->assertStatus(200);
    }

    #[Test]
    public function guest_cannot_list_brands(): void
    {
        $response = $this->getJson('/api/car-brands');

        $response->assertStatus(401);
    }

    //────────────────────────────────────────────
    // STORE
    //────────────────────────────────────────────

    #[Test]
    public function admin_can_create_brand(): void
    {
        $admin = $this->makeAdmin();

        $payload = ['name' => 'Volvo'];

        $response = $this->actingAs($admin)
            ->postJson('/api/car-brands', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Volvo');

        $this->assertDatabaseHas('car_brands', ['name' => 'Volvo']);
    }

    #[Test]
    public function user_cannot_create_brand(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)
            ->postJson('/api/car-brands', ['name' => 'BMW']);

        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_create_brand(): void
    {
        $response = $this->postJson('/api/car-brands', ['name' => 'Tesla']);

        $response->assertStatus(401);
    }

    #[Test]
    public function cannot_create_brand_with_empty_name(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)
            ->postJson('/api/car-brands', ['name' => '']);

        $response->assertStatus(422);
    }

    #[Test]
    public function cannot_create_brand_with_too_long_name(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)
            ->postJson('/api/car-brands', [
                'name' => str_repeat('A', 256)
            ]);

        $response->assertStatus(422);
    }

    #[Test]
    public function cannot_create_duplicate_brand_name(): void
    {
        $admin = $this->makeAdmin();

        CarBrand::create(['name' => 'Audi']);

        $response = $this->actingAs($admin)
            ->postJson('/api/car-brands', ['name' => 'Audi']);

        $response->assertStatus(422);
    }

    //────────────────────────────────────────────
    // SHOW
    //────────────────────────────────────────────

    #[Test]
    public function admin_can_show_brand(): void
    {
        $admin = $this->makeAdmin();
        $brand = CarBrand::factory()->create();

        $response = $this->actingAs($admin)
            ->getJson('/api/car-brands/' . $brand->id);

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $brand->id);
    }

    #[Test]
    public function user_can_show_brand(): void
    {
        $user = $this->makeUser();
        $brand = CarBrand::factory()->create();

        $response = $this->actingAs($user)
            ->getJson('/api/car-brands/' . $brand->id);

        $response->assertStatus(200);
    }

    #[Test]
    public function guest_cannot_show_brand(): void
    {
        $brand = CarBrand::factory()->create();

        $response = $this->getJson('/api/car-brands/' . $brand->id);

        $response->assertStatus(401);
    }

    #[Test]
    public function show_returns_404_if_brand_not_found(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)
            ->getJson('/api/car-brands/999999');

        $response->assertStatus(404);
    }

    //────────────────────────────────────────────
    // UPDATE
    //────────────────────────────────────────────

    #[Test]
    public function admin_can_update_brand(): void
    {
        $admin = $this->makeAdmin();
        $brand = CarBrand::factory()->create(['name' => 'Old']);

        $response = $this->actingAs($admin)
            ->putJson('/api/car-brands/' . $brand->id, ['name' => 'NewName']);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'NewName');

        $this->assertDatabaseHas('car_brands', ['name' => 'NewName']);
    }

    #[Test]
    public function user_cannot_update_brand(): void
    {
        $user = $this->makeUser();
        $brand = CarBrand::factory()->create();

        $response = $this->actingAs($user)
            ->putJson('/api/car-brands/' . $brand->id, ['name' => 'X']);

        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_update_brand(): void
    {
        $brand = CarBrand::factory()->create();

        $response = $this->putJson('/api/car-brands/' . $brand->id, ['name' => 'X']);

        $response->assertStatus(401);
    }

    #[Test]
    public function update_returns_422_when_invalid(): void
    {
        $admin = $this->makeAdmin();
        $brand = CarBrand::factory()->create();

        $response = $this->actingAs($admin)
            ->putJson('/api/car-brands/' . $brand->id, ['name' => '']);

        $response->assertStatus(422);
    }

    #[Test]
    public function update_returns_404_when_not_found(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)
            ->putJson('/api/car-brands/999999', ['name' => 'X']);

        $response->assertStatus(404);
    }

    #[Test]
    public function update_returns_422_for_duplicate_name(): void
    {
        $admin = $this->makeAdmin();

        CarBrand::create(['name' => 'Honda']);
        $brand = CarBrand::create(['name' => 'Mazda']);

        $response = $this->actingAs($admin)
            ->putJson('/api/car-brands/' . $brand->id, ['name' => 'Honda']);

        $response->assertStatus(422);
    }

    //────────────────────────────────────────────
    // DESTROY
    //────────────────────────────────────────────

    #[Test]
    public function admin_can_delete_brand(): void
    {
        $admin = $this->makeAdmin();
        $brand = CarBrand::factory()->create();

        $response = $this->actingAs($admin)
            ->deleteJson('/api/car-brands/' . $brand->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('car_brands', ['id' => $brand->id]);
    }

    #[Test]
    public function user_cannot_delete_brand(): void
    {
        $user = $this->makeUser();
        $brand = CarBrand::factory()->create();

        $response = $this->actingAs($user)
            ->deleteJson('/api/car-brands/' . $brand->id);

        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_delete_brand(): void
    {
        $brand = CarBrand::factory()->create();

        $response = $this->deleteJson('/api/car-brands/' . $brand->id);

        $response->assertStatus(401);
    }

    #[Test]
    public function destroy_returns_404_if_not_found(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)
            ->deleteJson('/api/car-brands/999999');

        $response->assertStatus(404);
    }
}
