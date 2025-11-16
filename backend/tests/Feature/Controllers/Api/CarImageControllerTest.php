<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Api;

use App\Models\User;
use App\Models\FavoriteCar;
use App\Models\CarImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CarImageControllerTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(): User
    {
        return User::factory()->create([
            'password' => Hash::make('Secret123!'),
            'is_active' => true
        ]);
    }

    private function makeFavoriteCar(User $user): FavoriteCar
    {
        return FavoriteCar::factory()->create([
            'user_id' => $user->id
        ]);
    }

    private function makeCarImage(FavoriteCar $favoriteCar): CarImage
    {
        return CarImage::factory()->create([
            'favorite_car_id' => $favoriteCar->id,
            'content'         => 'binary-test-data',
            'mime'            => 'image/jpeg',
        ]);
    }

    // ───────────────────────────────────────────────
    // INDEX – képek listázása
    // ───────────────────────────────────────────────

    #[Test]
    public function user_can_list_own_images(): void
    {
        $user = $this->makeUser();
        Sanctum::actingAs($user);

        $favoriteCar = $this->makeFavoriteCar($user);

        CarImage::factory()->count(3)->create([
            'favorite_car_id' => $favoriteCar->id
        ]);

        $response = $this->getJson("/api/favorite-cars/{$favoriteCar->id}/images");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Képek listája')
            ->assertJsonCount(3, 'data');
    }

    #[Test]
    public function user_cannot_list_others_images(): void
    {
        $user1 = $this->makeUser();
        $user2 = $this->makeUser();

        Sanctum::actingAs($user1);

        $foreignCar = $this->makeFavoriteCar($user2);

        $response = $this->getJson("/api/favorite-cars/{$foreignCar->id}/images");

        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_list_images(): void
    {
        $response = $this->getJson('/api/favorite-cars/999/images');
        $response->assertStatus(401);
    }

    #[Test]
    public function index_returns_404_if_favorite_car_not_found(): void
    {
        $user = $this->makeUser();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/favorite-cars/999/images');

        $response->assertStatus(404);
    }


    // ───────────────────────────────────────────────
    // STORE – képek feltöltése
    // ───────────────────────────────────────────────

    #[Test]
    public function user_can_upload_images(): void
    {
        Storage::fake('local');

        $user = $this->makeUser();
        Sanctum::actingAs($user);

        $favoriteCar = $this->makeFavoriteCar($user);

        $file1 = UploadedFile::fake()->create('pic1.jpg', 100, 'image/jpeg');
        $file2 = UploadedFile::fake()->create('pic2.jpg', 100, 'image/jpeg');

        $response = $this->postJson("/api/favorite-cars/{$favoriteCar->id}/images", [
            'images' => [$file1, $file2],
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('message', 'Képek sikeresen feltöltve');

        $this->assertDatabaseCount('car_images', 2);
    }

    #[Test]
    public function cannot_upload_images_to_others_favorite_car(): void
    {
        Storage::fake('local');

        $user1 = $this->makeUser();
        $user2 = $this->makeUser();

        Sanctum::actingAs($user1);

        $foreignCar = $this->makeFavoriteCar($user2);

        $file = UploadedFile::fake()->create('x.jpg', 100, 'image/jpeg');

        $response = $this->postJson("/api/favorite-cars/{$foreignCar->id}/images", [
            'images' => [$file],
        ]);

        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_upload_images(): void
    {
        $file = UploadedFile::fake()->create('x.jpg', 100, 'image/jpeg');

        $response = $this->postJson('/api/favorite-cars/1/images', [
            'images' => [$file],
        ]);

        $response->assertStatus(401);
    }

    #[Test]
    public function upload_validation_fails_if_no_images(): void
    {
        $user = $this->makeUser();
        Sanctum::actingAs($user);

        $car = $this->makeFavoriteCar($user);

        $response = $this->postJson("/api/favorite-cars/{$car->id}/images", []);

        $response->assertStatus(422);
    }


    // ───────────────────────────────────────────────
    // SHOW – bináris kép lekérése
    // ───────────────────────────────────────────────

    #[Test]
    public function user_can_view_own_image(): void
    {
        $user = $this->makeUser();
        Sanctum::actingAs($user);

        $car = $this->makeFavoriteCar($user);
        $image = $this->makeCarImage($car);

        $response = $this->get("/api/images/{$image->id}");

        $response->assertStatus(200);
        $this->assertEquals('binary-test-data', $response->streamedContent());
    }

    #[Test]
    public function user_cannot_view_others_image(): void
    {
        $user1 = $this->makeUser();
        $user2 = $this->makeUser();

        Sanctum::actingAs($user1);

        $car2 = $this->makeFavoriteCar($user2);
        $image = $this->makeCarImage($car2);

        $response = $this->get("/api/images/{$image->id}");

        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_view_image(): void
    {
        $response = $this->get("/api/images/1");
        $response->assertStatus(401);
    }

    #[Test]
    public function show_returns_404_if_image_not_found(): void
    {
        $user = $this->makeUser();
        Sanctum::actingAs($user);

        $response = $this->get("/api/images/999");

        $response->assertStatus(404);
    }


    // ───────────────────────────────────────────────
    // DESTROY – kép törlése
    // ───────────────────────────────────────────────

    #[Test]
    public function user_can_delete_own_image(): void
    {
        $user = $this->makeUser();
        Sanctum::actingAs($user);

        $car = $this->makeFavoriteCar($user);
        $image = $this->makeCarImage($car);

        $response = $this->deleteJson("/api/images/{$image->id}");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Kép sikeresen törölve');

        $this->assertDatabaseMissing('car_images', ['id' => $image->id]);
    }

    #[Test]
    public function user_cannot_delete_others_image(): void
    {
        $user1 = $this->makeUser();
        $user2 = $this->makeUser();

        Sanctum::actingAs($user1);

        $car = $this->makeFavoriteCar($user2);
        $image = $this->makeCarImage($car);

        $response = $this->deleteJson("/api/images/{$image->id}");

        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_delete_image(): void
    {
        $response = $this->deleteJson('/api/images/1');
        $response->assertStatus(401);
    }

    #[Test]
    public function delete_returns_404_if_image_not_found(): void
    {
        $user = $this->makeUser();
        Sanctum::actingAs($user);

        $response = $this->deleteJson('/api/images/999');

        $response->assertStatus(404);
    }
}
