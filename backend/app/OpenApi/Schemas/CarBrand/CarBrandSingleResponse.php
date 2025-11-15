<?php

/**
 * @OA\Schema(
 *     schema="CarBrandSingleResponse",
 *     type="object",
 *
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Márka adatai"),
 *     @OA\Property(property="data", ref="#/components/schemas/CarBrand")
 * )
 */
class CarBrandSingleResponseSchema {}
