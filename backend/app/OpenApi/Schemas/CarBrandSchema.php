<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="CarBrand",
 *     type="object",
 *     description="Autómárka szótár bejegyzés",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *
 *     @OA\Property(property="name", type="string", example="BMW"),
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
 *     ),
 *
 *     @OA\Property(
 *         property="models",
 *         type="array",
 *         description="A márkához tartozó autótípusok",
 *         @OA\Items(ref="#/components/schemas/CarModel")
 *     )
 * )
 */
class CarBrandSchema {}
