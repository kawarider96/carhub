<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="FavoriteCar",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=12),
 *     @OA\Property(property="user_id", type="integer", example=3),
 *     @OA\Property(property="car_model_id", type="integer", example=7),
 *     @OA\Property(property="year", type="integer", example=2005),
 *     @OA\Property(property="color", type="string", example="Black"),
 *     @OA\Property(property="fuel", type="string", example="Petrol"),
 *     @OA\Property(property="model", ref="#/components/schemas/CarModel")
 * )
 */
class FavoriteCarSchema {}
