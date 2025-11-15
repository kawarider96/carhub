<?php

/**
 * @OA\Schema(
 *     schema="FavoriteCarListResponse",
 *     type="object",
 *     description="Kedvenc autók listájának API válasza",
 *
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Kedvenc autók listája"),
 *
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/FavoriteCar")
 *     )
 * )
 */
class FavoriteCarListResponseSchema {}
