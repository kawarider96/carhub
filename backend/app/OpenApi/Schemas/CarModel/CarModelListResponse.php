<?php

/**
 * @OA\Schema(
 *     schema="CarModelListResponse",
 *     type="object",
 *     description="Autó típusok listájának API válasza",
 *
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Autó típusok listája"),
 *
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/CarModel")
 *     )
 * )
 */
class CarModelListResponseSchema {}
