<?php

/**
 * @OA\Schema(
 *     schema="FavoriteCar",
 *     type="object",
 *     description="Kedvenc autó objektum",
 *
 *     @OA\Property(property="id", type="integer", example=12),
 *     @OA\Property(property="user_id", type="integer", example=5),
 *     @OA\Property(property="car_model_id", type="integer", example=3),
 *     @OA\Property(property="year", type="integer", example=2016),
 *     @OA\Property(property="color", type="string", example="Fekete"),
 *     @OA\Property(property="fuel", type="string", example="Benzin"),
 *
 *     @OA\Property(property="model", ref="#/components/schemas/CarModel"),
 *
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class FavoriteCarSchema {}
