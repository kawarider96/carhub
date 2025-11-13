<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="CarModel",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=5),
 *     @OA\Property(property="car_brand_id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="E46"),
 *     @OA\Property(property="brand", ref="#/components/schemas/CarBrand")
 * )
 */
class CarModelSchema {}
