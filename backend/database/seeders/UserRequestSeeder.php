<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserRequest;

class UserRequestSeeder extends Seeder
{
    public function run(): void
    {
        User::all()->each(function (User $user) {
            UserRequest::factory()
                ->count(rand(0, 2))
                ->create([
                    'user_id' => $user->id,
                ]);
        });
    }
}
