<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarBrand;
use App\Models\CarModel;
use Database\Seeders\ExampleData;

class CarModelSeeder extends Seeder
{
    public function run(): void
    {
        CarBrand::all()->each(function (CarBrand $brand) {

            // Megkeressük az adott brand-hez tartozó modelleket
            $entry = collect(ExampleData::$carData)
                ->firstWhere('brand', $brand->name);

            if (!$entry) {
                return;
            }

            foreach ($entry['models'] as $modelName) {

                CarModel::firstOrCreate([
                    'car_brand_id' => $brand->id,
                    'name'         => $modelName,
                ]);
            }
        });
    }
}
