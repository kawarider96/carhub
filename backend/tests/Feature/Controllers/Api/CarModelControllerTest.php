<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Api;

use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CarModelControllerTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::factory()->create([
            'role'      => 'admin',
            'is_active' => true,
            'password'  => Hash::make('Secret123!'),
        ]);
    }

    private function makeUser(): User
    {
        return User::factory()->create([
            'role'      => 'user',
            'is_active' => true,
            'password'  => Hash::make('Secret123!'),
        ]);
    }

    private function makeLockedUser(): User
    {
        return User::factory()->create([
            'role'          => 'user',
            'is_active'     => false,
            'failed_logins' => 5,
            'password'      => Hash::make('Secret123!'),
        ]);
    }

    // ───────────────────────────────────────────────
    // INDEX
    // ───────────────────────────────────────────────

    #[Test]
    public function admin_can_list_models(): void
    {
        $admin = $this->makeAdmin();

        CarModel::factory()->count(3)->create();

        $response = $this->actingAs($admin)
            ->getJson('/api/car-models');

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Autó típusok listája')
            ->assertJsonCount(3, 'data');
    }

    #[Test]
    public function user_can_list_models(): void
    {
        $user = $this->makeUser();

        CarModel::factory()->count(2)->create();

        $response = $this->actingAs($user)
            ->getJson('/api/car-models');

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Autó típusok listája')
            ->assertJsonCount(2, 'data');
    }

    #[Test]
    public function guest_cannot_list_models(): void
    {
        $response = $this->getJson('/api/car-models');

        $response->assertStatus(401);
    }

    #[Test]
    public function locked_user_cannot_list_models(): void
    {
        $user = $this->makeLockedUser();

        $response = $this->actingAs($user)
            ->getJson('/api/car-models');

        $response->assertStatus(423);
    }

    // ───────────────────────────────────────────────
    // STORE
    // ───────────────────────────────────────────────

    #[Test]
    public function admin_can_store_model(): void
    {
        $admin = $this->makeAdmin();
        $brand = CarBrand::factory()->create();

        $response = $this->actingAs($admin)
            ->postJson('/api/car-models', [
                'car_brand_id' => $brand->id,
                'name'         => 'A4',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('message', 'Autó típus létrehozva')
            ->assertJsonPath('data.name', 'A4')
            ->assertJsonPath('data.car_brand_id', $brand->id);

        $this->assertDatabaseHas('car_models', [
            'name' => 'A4',
            'car_brand_id' => $brand->id,
        ]);
    }

    #[Test]
    public function user_can_store_model(): void
    {
        $user  = $this->makeUser();
        $brand = CarBrand::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/car-models', [
                'car_brand_id' => $brand->id,
                'name'         => 'Focus',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('message', 'Autó típus létrehozva')
            ->assertJsonPath('data.name', 'Focus')
            ->assertJsonPath('data.car_brand_id', $brand->id);

        $this->assertDatabaseHas('car_models', [
            'name'         => 'Focus',
            'car_brand_id' => $brand->id,
        ]);
    }

    #[Test]
    public function guest_cannot_store_model(): void
    {
        $brand = CarBrand::factory()->create();

        $response = $this->postJson('/api/car-models', [
            'car_brand_id' => $brand->id,
            'name'         => 'Civic',
        ]);

        $response->assertStatus(401);
    }

    #[Test]
    public function locked_user_cannot_store_model(): void
    {
        $user  = $this->makeLockedUser();
        $brand = CarBrand::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/car-models', [
                'car_brand_id' => $brand->id,
                'name'         => 'Octavia',
            ]);

        $response->assertStatus(423);
    }

    #[Test]
    public function store_validation_errors_return_422(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)
            ->postJson('/api/car-models', [
                'car_brand_id' => null,
                'name'         => '',
            ]);

        $response->assertStatus(422);
    }

    // ───────────────────────────────────────────────
    // SHOW
    // ───────────────────────────────────────────────

    #[Test]
    public function admin_can_show_model(): void
    {
        $admin = $this->makeAdmin();
        $model = CarModel::factory()->create();

        $response = $this->actingAs($admin)
            ->getJson("/api/car-models/{$model->id}");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Autó típus adatai')
            ->assertJsonPath('data.id', $model->id);
    }

    #[Test]
    public function user_can_show_model(): void
    {
        $user  = $this->makeUser();
        $model = CarModel::factory()->create();

        $response = $this->actingAs($user)
            ->getJson("/api/car-models/{$model->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $model->id);
    }

    #[Test]
    public function guest_cannot_show_model(): void
    {
        $model = CarModel::factory()->create();

        $response = $this->getJson("/api/car-models/{$model->id}");

        $response->assertStatus(401);
    }

    #[Test]
    public function locked_user_cannot_show_model(): void
    {
        $user  = $this->makeLockedUser();
        $model = CarModel::factory()->create();

        $response = $this->actingAs($user)
            ->getJson("/api/car-models/{$model->id}");

        $response->assertStatus(423);
    }

    #[Test]
    public function showing_nonexistent_model_returns_404(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)
            ->getJson('/api/car-models/9999');

        $response->assertStatus(404);
    }

    // ───────────────────────────────────────────────
    // UPDATE
    // ───────────────────────────────────────────────

    #[Test]
    public function admin_can_update_model(): void
    {
        $admin = $this->makeAdmin();
        $model = CarModel::factory()->create();

        $response = $this->actingAs($admin)
            ->putJson("/api/car-models/{$model->id}", [
                'name'         => 'UpdatedModel',
                'car_brand_id' => $model->car_brand_id,
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Autó típus frissítve')
            ->assertJsonPath('data.name', 'UpdatedModel');
    }

    #[Test]
    public function user_cannot_update_model(): void
    {
        $user  = $this->makeUser();
        $model = CarModel::factory()->create();

        $response = $this->actingAs($user)
            ->putJson("/api/car-models/{$model->id}", [
                'name' => 'FailUpdate',
                'car_brand_id' => $model->car_brand_id,
            ]);

        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_update_model(): void
    {
        $model = CarModel::factory()->create();

        $response = $this->putJson("/api/car-models/{$model->id}", [
            'name'         => 'Touring',
            'car_brand_id' => $model->car_brand_id,
        ]);

        $response->assertStatus(401);
    }

    #[Test]
    public function locked_user_cannot_update_model(): void
    {
        $user  = $this->makeLockedUser();
        $model = CarModel::factory()->create();

        $response = $this->actingAs($user)
            ->putJson("/api/car-models/{$model->id}", [
                'name'         => 'X',
                'car_brand_id' => $model->car_brand_id,
            ]);

        $response->assertStatus(423);
    }

    #[Test]
    public function update_validation_errors_return_422(): void
    {
        $admin = $this->makeAdmin();
        $model = CarModel::factory()->create();

        $response = $this->actingAs($admin)
            ->putJson("/api/car-models/{$model->id}", [
                'name'         => '',
                'car_brand_id' => null,
            ]);

        $response->assertStatus(422);
    }

    #[Test]
    public function update_nonexistent_model_returns_404(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)
            ->putJson('/api/car-models/9999', [
                'name' => 'Test',
                'car_brand_id' => 1,
            ]);

        $response->assertStatus(404);
    }

    // ───────────────────────────────────────────────
    // DELETE
    // ───────────────────────────────────────────────

    #[Test]
    public function admin_can_delete_model(): void
    {
        $admin = $this->makeAdmin();
        $model = CarModel::factory()->create();

        $response = $this->actingAs($admin)
            ->deleteJson("/api/car-models/{$model->id}");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Típus sikeresen törölve');

        $this->assertDatabaseMissing('car_models', ['id' => $model->id]);
    }

    #[Test]
    public function user_cannot_delete_model(): void
    {
        $user  = $this->makeUser();
        $model = CarModel::factory()->create();

        $response = $this->actingAs($user)
            ->deleteJson("/api/car-models/{$model->id}");

        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_delete_model(): void
    {
        $model = CarModel::factory()->create();

        $response = $this->deleteJson("/api/car-models/{$model->id}");

        $response->assertStatus(401);
    }

    #[Test]
    public function locked_user_cannot_delete_model(): void
    {
        $user  = $this->makeLockedUser();
        $model = CarModel::factory()->create();

        $response = $this->actingAs($user)
            ->deleteJson("/api/car-models/{$model->id}");

        $response->assertStatus(423);
    }

    #[Test]
    public function delete_nonexistent_model_returns_404(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)
            ->deleteJson('/api/car-models/9999');

        $response->assertStatus(404);
    }
}
