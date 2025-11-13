<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\CarbonImmutable;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'full_name'     => $this->faker->name(),
            'username'      => $this->faker->unique()->userName(),
            'password'      => 'Password123!', // mutátor hash-eli
            'role'          => $this->faker->randomElement(['user', 'admin']),
            'is_active'     => true,
            'failed_logins' => 0,
            'created_at'    => CarbonImmutable::now(),
            'updated_at'    => CarbonImmutable::now(),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => ['role' => 'admin']);
    }
}
