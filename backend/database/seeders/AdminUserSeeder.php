<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'full_name' => 'System Administrator',
                'password'  => Hash::make('Admin123!'),
                'role'      => 'admin',
                'is_active' => true,
            ]
        );
    }
}
