<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    private function validRegistrationData(): array
    {
        return [
            'full_name'             => 'Teszt Elek',
            'username'              => 'tesztuser',
            'password'              => 'Secret123!',
            'password_confirmation' => 'Secret123!',
        ];
    }

    private function makeUser(): User
    {
        return User::factory()->create([
            'username'     => 'john',
            'password'     => Hash::make('Secret123!'),
            'is_active'    => true,
            'failed_logins'=> 0,
        ]);
    }

    // ───────────────────────────────────────────────
    // REGISTER
    // ───────────────────────────────────────────────

    #[Test]
    public function user_can_register(): void
    {
        $response = $this->postJson('/api/register', $this->validRegistrationData());

        $response->assertStatus(201)
            ->assertJsonPath('message', 'Sikeres regisztráció');

        $this->assertDatabaseHas('users', [
            'username' => 'tesztuser',
            'is_active' => true,
            'failed_logins' => 0,
        ]);
    }

    #[Test]
    public function register_fails_with_missing_fields(): void
    {
        $response = $this->postJson('/api/register', []);

        $response->assertStatus(422);
    }

    #[Test]
    public function register_fails_if_username_is_taken(): void
    {
        User::factory()->create(['username' => 'tesztuser']);

        $response = $this->postJson('/api/register', $this->validRegistrationData());

        $response->assertStatus(422);
    }

    #[Test]
    public function register_fails_if_password_too_weak(): void
    {
        $data = $this->validRegistrationData();
        $data['password'] = 'weak';
        $data['password_confirmation'] = 'weak';

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(422);
    }

    // ───────────────────────────────────────────────
    // LOGIN
    // ───────────────────────────────────────────────

    #[Test]
    public function user_can_login_with_correct_credentials(): void
    {
        $user = $this->makeUser();

        $response = $this->postJson('/api/login', [
            'username' => 'john',
            'password' => 'Secret123!',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Sikeres bejelentkezés');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'failed_logins' => 0,
        ]);
    }

    #[Test]
    public function login_fails_with_wrong_password(): void
    {
        $user = $this->makeUser();

        $response = $this->postJson('/api/login', [
            'username' => 'john',
            'password' => 'WrongPass123!',
        ]);

        $response->assertStatus(401)
            ->assertJsonPath('message', 'Hibás bejelentkezési adatok');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'failed_logins' => 1,
        ]);
    }

    #[Test]
    public function user_gets_locked_after_5_failed_attempts(): void
    {
        $user = $this->makeUser();

        for ($i = 1; $i <= 5; $i++) {
            $this->postJson('/api/login', [
                'username' => 'john',
                'password' => 'Invalid999!',
            ]);
        }

        $response = $this->postJson('/api/login', [
            'username' => 'john',
            'password' => 'Secret123!',
        ]);

        $response->assertStatus(423)
            ->assertJsonPath('message', 'A felhasználó zárolva');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => false,
        ]);
    }

    #[Test]
    public function login_fails_if_user_is_locked(): void
    {
        $user = $this->makeUser();
        $user->update(['is_active' => false]);

        $response = $this->postJson('/api/login', [
            'username' => 'john',
            'password' => 'Secret123!',
        ]);

        $response->assertStatus(423);
    }

    #[Test]
    public function login_fails_if_user_not_found(): void
    {
        $response = $this->postJson('/api/login', [
            'username' => 'nope',
            'password' => 'Whatever123!',
        ]);

        $response->assertStatus(401)
            ->assertJsonPath('message', 'Hibás bejelentkezési adatok');
    }

    // ───────────────────────────────────────────────
    // LOGOUT
    // ───────────────────────────────────────────────

    #[Test]
    public function user_can_logout(): void
    {
        $user = $this->makeUser();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Sikeresen kijelentkeztél');
    }

    #[Test]
    public function guest_cannot_logout(): void
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401);
    }
}
