<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="CarImage",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=44),
 *     @OA\Property(property="favorite_car_id", type="integer", example=12),
 *     @OA\Property(
 *         property="image_data",
 *         type="string",
 *         format="byte",
 *         example="BASE64_ENCODED_IMAGE"
 *     )
 * )
 */
class CarImageSchema {}
