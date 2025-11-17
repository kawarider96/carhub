<?php

/**
 * @OA\Schema(
 *     schema="FavoriteCarSingleResponse",
 *     type="object",
 *     description="Egy kedvenc autó API válasza",
 *
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Kedvenc autó adatai"),
 *     @OA\Property(property="data", ref="#/components/schemas/FavoriteCar")
 * )
 */
class FavoriteCarSingleResponseSchema {}
