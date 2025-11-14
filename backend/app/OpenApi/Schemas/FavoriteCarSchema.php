<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="FavoriteCar",
 *     type="object",
 *     description="Felhasználó kedvenc autója",
 *
 *     @OA\Property(property="id", type="integer", example=12),
 *
 *     @OA\Property(property="user_id", type="integer", example=3),
 *
 *     @OA\Property(property="car_model_id", type="integer", example=7),
 *
 *     @OA\Property(
 *         property="year",
 *         type="integer",
 *         nullable=true,
 *         example=2005
 *     ),
 *
 *     @OA\Property(
 *         property="color",
 *         type="string",
 *         nullable=true,
 *         example="Black"
 *     ),
 *
 *     @OA\Property(
 *         property="fuel",
 *         type="string",
 *         nullable=true,
 *         example="Petrol"
 *     ),
 *
 *     @OA\Property(
 *         property="user",
 *         ref="#/components/schemas/User"
 *     ),
 *
 *     @OA\Property(
 *         property="carModel",
 *         ref="#/components/schemas/CarModel"
 *     ),
 *
 *     @OA\Property(
 *         property="images",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/CarImage")
 *     ),
 *
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2025-11-14T10:23:00Z"
 *     ),
 *
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2025-11-14T10:23:00Z"
 *     )
 * )
 */
class FavoriteCarSchema {}
