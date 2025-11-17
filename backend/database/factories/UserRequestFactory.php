<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserRequest;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\CarbonImmutable;

class UserRequestFactory extends Factory
{
    protected $model = UserRequest::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['delete_account', 'missing_brand']);

        return [
            'user_id'     => User::factory(),
            'type'        => $type,
            'payload'     => $type === 'missing_brand'
                                ? ['brand' => $this->faker->word()]
                                : null,
            'status'      => 'open',
            'handled_by'  => null,
            'handled_at'  => null,
            'created_at'  => CarbonImmutable::now(),
            'updated_at'  => CarbonImmutable::now(),
        ];
    }
}
