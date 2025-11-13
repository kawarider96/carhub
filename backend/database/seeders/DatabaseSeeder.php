<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,     // kötelező admin
            UserSeeder::class,          // random userek
            CarBrandSeeder::class,      // márkák
            CarModelSeeder::class,      // típusok
            FavoriteCarSeeder::class,   // kedvenc autók
            CarImageSeeder::class,      // autó képek
            UserRequestSeeder::class,   // felhasználói kérelmek
        ]);
    }
}
