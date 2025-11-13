<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarBrand;
use Database\Seeders\ExampleData;

class CarBrandSeeder extends Seeder
{
    public function run(): void
    {
        foreach (ExampleData::$carData as $entry) {

            CarBrand::firstOrCreate([
                'name' => $entry['brand'],
            ]);
        }
    }
}
