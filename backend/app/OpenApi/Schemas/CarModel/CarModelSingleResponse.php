<?php

/**
 * @OA\Schema(
 *     schema="CarModelSingleResponse",
 *     type="object",
 *     description="Egyetlen autó típus API válasza",
 *
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Autó típus adatai"),
 *     @OA\Property(property="data", ref="#/components/schemas/CarModel")
 * )
 */
class CarModelSingleResponseSchema {}
