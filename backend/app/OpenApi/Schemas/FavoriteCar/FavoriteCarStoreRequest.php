<?php

/**
 * @OA\Schema(
 *     schema="FavoriteCarStoreRequest",
 *     type="object",
 *     required={"car_model_id"},
 *     description="Kedvenc autó hozzáadásának bemeneti adatai",
 *
 *     @OA\Property(property="car_model_id", type="integer", example=3),
 *     @OA\Property(property="year", type="integer", example=2015),
 *     @OA\Property(property="color", type="string", example="Piros"),
 *     @OA\Property(property="fuel", type="string", example="Dízel")
 * )
 */
class FavoriteCarStoreRequestSchema {}
