<?php

/**
 * @OA\Schema(
 *     schema="CarModel",
 *     type="object",
 *     description="Autó típus objektum",
 *
 *     @OA\Property(property="id", type="integer", example=42),
 *     @OA\Property(property="car_brand_id", type="integer", example=3),
 *     @OA\Property(property="name", type="string", example="A4"),
 *
 *     @OA\Property(property="brand", ref="#/components/schemas/CarBrand"),
 *
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class CarModelSchema {}
