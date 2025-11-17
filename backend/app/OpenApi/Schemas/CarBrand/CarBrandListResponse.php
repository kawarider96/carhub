<?php

/**
 * @OA\Schema(
 *     schema="CarBrandListResponse",
 *     type="object",
 *
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Márkák listája"),
 *
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/CarBrand")
 *     )
 * )
 */
class CarBrandListResponseSchema {}
