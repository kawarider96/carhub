<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="CarModel",
 *     type="object",
 *     description="Autómárkához tartozó autótípus",
 *
 *     @OA\Property(property="id", type="integer", example=5),
 *
 *     @OA\Property(property="car_brand_id", type="integer", example=1),
 *
 *     @OA\Property(property="name", type="string", example="E46"),
 *
 *     @OA\Property(
 *         property="brand",
 *         ref="#/components/schemas/CarBrand"
 *     ),
 *
 *     @OA\Property(
 *         property="favoriteCars",
 *         type="array",
 *         description="A kedvenc autók, amelyek ezt a típust használják",
 *         @OA\Items(ref="#/components/schemas/FavoriteCar")
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
class CarModelSchema {}
