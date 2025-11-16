<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Api;

use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\FavoriteCar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FavoriteCarControllerTest extends TestCase
{
    use RefreshDatabase;

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

    private function makeFavoriteCar(User $user): FavoriteCar
    {
        $brand = CarBrand::factory()->create();
        $model = CarModel::factory()->create([
            'car_brand_id' => $brand->id,
        ]);

        return FavoriteCar::factory()->create([
            'user_id'        => $user->id,
            'car_model_id'   => $model->id,
            'year'           => 2020,
            'color'          => 'Red',
            'fuel'           => 'Diesel',
        ]);
    }

    // ───────────────────────────────────────────────
    // INDEX
    // ───────────────────────────────────────────────

    #[Test]
    public function user_can_list_his_own_favorites(): void
    {
        $user = $this->makeUser();

        $this->makeFavoriteCar($user);
        $this->makeFavoriteCar($user);

        $response = $this->actingAs($user)
            ->getJson('/api/favorite-cars');

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Kedvenc autók listája')
            ->assertJsonCount(2, 'data');
    }

    #[Test]
    public function user_sees_only_his_own_favorites(): void
    {
        $user1 = $this->makeUser();
        $user2 = $this->makeUser();

        $this->makeFavoriteCar($user1);
        $this->makeFavoriteCar($user2);

        $response = $this->actingAs($user1)
            ->getJson('/api/favorite-cars');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    #[Test]
    public function guest_cannot_list_favorites(): void
    {
        $response = $this->getJson('/api/favorite-cars');

        $response->assertStatus(401);
    }

    #[Test]
    public function locked_user_cannot_list_favorites(): void
    {
        $user = $this->makeLockedUser();

        $response = $this->actingAs($user)
            ->getJson('/api/favorite-cars');

        $response->assertStatus(423);
    }

    // ───────────────────────────────────────────────
    // STORE
    // ───────────────────────────────────────────────

    #[Test]
    public function user_can_create_favorite_car(): void
    {
        $user  = $this->makeUser();
        $brand = CarBrand::factory()->create();
        $model = CarModel::factory()->create(['car_brand_id' => $brand->id]);

        $response = $this->actingAs($user)
            ->postJson('/api/favorite-cars', [
                'car_model_id' => $model->id,
                'year'         => 2022,
                'color'        => 'Blue',
                'fuel'         => 'Petrol',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('message', 'Kedvenc autó létrehozva')
            ->assertJsonPath('data.car_model_id', $model->id)
            ->assertJsonPath('data.year', 2022);
    }

    #[Test]
    public function guest_cannot_create_favorite(): void
    {
        $response = $this->postJson('/api/favorite-cars', []);

        $response->assertStatus(401);
    }

    #[Test]
    public function locked_user_cannot_create_favorite(): void
    {
        $user = $this->makeLockedUser();

        $response = $this->actingAs($user)
            ->postJson('/api/favorite-cars', []);

        $response->assertStatus(423);
    }

    #[Test]
    public function store_validation_error_returns_422(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)
            ->postJson('/api/favorite-cars', [
                'car_model_id' => null,
                'year'         => 'invalid',
            ]);

        $response->assertStatus(422);
    }

    // ───────────────────────────────────────────────
    // SHOW
    // ───────────────────────────────────────────────

    #[Test]
    public function user_can_view_own_favorite(): void
    {
        $user  = $this->makeUser();
        $fav   = $this->makeFavoriteCar($user);

        $response = $this->actingAs($user)
            ->getJson("/api/favorite-cars/{$fav->id}");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Kedvenc autó adatai')
            ->assertJsonPath('data.id', $fav->id);
    }

    #[Test]
    public function user_cannot_view_others_favorite(): void
    {
        $user1 = $this->makeUser();
        $user2 = $this->makeUser();

        $foreign = $this->makeFavoriteCar($user2);

        $response = $this->actingAs($user1)
            ->getJson("/api/favorite-cars/{$foreign->id}");

        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_view_favorite(): void
    {
        $fav = FavoriteCar::factory()->create();

        $response = $this->getJson("/api/favorite-cars/{$fav->id}");

        $response->assertStatus(401);
    }

    #[Test]
    public function locked_user_cannot_view_favorite(): void
    {
        $user = $this->makeLockedUser();
        $fav  = FavoriteCar::factory()->create();

        $response = $this->actingAs($user)
            ->getJson("/api/favorite-cars/{$fav->id}");

        $response->assertStatus(423);
    }

    #[Test]
    public function show_nonexistent_favorite_returns_404(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)
            ->getJson("/api/favorite-cars/9999");

        $response->assertStatus(404);
    }

    // ───────────────────────────────────────────────
    // UPDATE
    // ───────────────────────────────────────────────

    #[Test]
    public function user_can_update_own_favorite(): void
    {
        $user  = $this->makeUser();
        $fav   = $this->makeFavoriteCar($user);

        $response = $this->actingAs($user)
            ->putJson("/api/favorite-cars/{$fav->id}", [
                'color' => 'Black',
                'year'  => 2021,
                'fuel'  => 'Electric',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Kedvenc autó frissítve')
            ->assertJsonPath('data.color', 'Black');
    }

    #[Test]
    public function user_cannot_update_others_favorite(): void
    {
        $user1 = $this->makeUser();
        $user2 = $this->makeUser();

        $foreign = $this->makeFavoriteCar($user2);

        $response = $this->actingAs($user1)
            ->putJson("/api/favorite-cars/{$foreign->id}", [
                'color' => 'Black',
            ]);

        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_update_favorite(): void
    {
        $fav = FavoriteCar::factory()->create();

        $response = $this->putJson("/api/favorite-cars/{$fav->id}", []);

        $response->assertStatus(401);
    }

    #[Test]
    public function locked_user_cannot_update_favorite(): void
    {
        $user = $this->makeLockedUser();
        $fav  = FavoriteCar::factory()->create();

        $response = $this->actingAs($user)
            ->putJson("/api/favorite-cars/{$fav->id}", [
                'color' => 'X',
            ]);

        $response->assertStatus(423);
    }

    #[Test]
    public function update_validation_error_returns_422(): void
    {
        $user = $this->makeUser();
        $fav  = $this->makeFavoriteCar($user);

        $response = $this->actingAs($user)
            ->putJson("/api/favorite-cars/{$fav->id}", [
                'year' => 'abc',
            ]);

        $response->assertStatus(422);
    }

    #[Test]
    public function update_nonexistent_favorite_returns_404(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)
            ->putJson("/api/favorite-cars/9999", [
                'color' => 'Black',
            ]);

        $response->assertStatus(404);
    }

    // ───────────────────────────────────────────────
    // DELETE
    // ───────────────────────────────────────────────

    #[Test]
    public function user_can_delete_his_own_favorite(): void
    {
        $user = $this->makeUser();
        $fav  = $this->makeFavoriteCar($user);

        $response = $this->actingAs($user)
            ->deleteJson("/api/favorite-cars/{$fav->id}");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Kedvenc autó törölve');

        $this->assertDatabaseMissing('favorite_cars', ['id' => $fav->id]);
    }

    #[Test]
    public function user_cannot_delete_others_favorite(): void
    {
        $user1 = $this->makeUser();
        $user2 = $this->makeUser();

        $foreign = $this->makeFavoriteCar($user2);

        $response = $this->actingAs($user1)
            ->deleteJson("/api/favorite-cars/{$foreign->id}");

        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_delete_favorite(): void
    {
        $fav = FavoriteCar::factory()->create();

        $response = $this->deleteJson("/api/favorite-cars/{$fav->id}");

        $response->assertStatus(401);
    }

    #[Test]
    public function locked_user_cannot_delete_favorite(): void
    {
        $user = $this->makeLockedUser();
        $fav  = FavoriteCar::factory()->create();

        $response = $this->actingAs($user)
            ->deleteJson("/api/favorite-cars/{$fav->id}");

        $response->assertStatus(423);
    }

    #[Test]
    public function delete_nonexistent_favorite_returns_404(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)
            ->deleteJson("/api/favorite-cars/9999");

        $response->assertStatus(404);
    }
}
