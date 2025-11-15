<?php

/**
 * @OA\Schema(
 *     schema="FavoriteCarUpdateRequest",
 *     type="object",
 *     description="Kedvenc autó módosításának bemeneti adatai",
 *
 *     @OA\Property(property="year", type="integer", example=2018),
 *     @OA\Property(property="color", type="string", example="Kék"),
 *     @OA\Property(property="fuel", type="string", example="Hybrid")
 * )
 */
class FavoriteCarUpdateRequestSchema {}
