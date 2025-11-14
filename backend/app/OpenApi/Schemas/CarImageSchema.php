<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="CarImage",
 *     type="object",
 *     description="Kedvenc autóhoz feltöltött kép",
 *
 *     @OA\Property(property="id", type="integer", example=44),
 *
 *     @OA\Property(property="favorite_car_id", type="integer", example=12),
 *
 *     @OA\Property(
 *         property="content",
 *         type="string",
 *         description="A kép BASE64-ben",
 *         example="iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAA..."
 *     ),
 *
 *     @OA\Property(
 *         property="mime",
 *         type="string",
 *         description="MIME típus (pl. image/jpeg, image/png)",
 *         example="image/jpeg"
 *     ),
 *
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2025-11-14T09:12:00Z"
 *     ),
 *
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2025-11-14T09:12:00Z"
 *     )
 * )
 */
class CarImageSchema {}

