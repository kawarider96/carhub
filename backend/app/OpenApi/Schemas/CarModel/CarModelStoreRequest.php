<?php

/**
 * @OA\Schema(
 *     schema="CarModelStoreRequest",
 *     type="object",
 *     required={"car_brand_id", "name"},
 *     description="Új autótípus létrehozásához szükséges adatok",
 *
 *     @OA\Property(
 *         property="car_brand_id",
 *         type="integer",
 *         example=2,
 *         description="A márka azonosítója"
 *     ),
 *
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="Corolla",
 *         description="A típus neve"
 *     )
 * )
 */
class CarModelStoreRequestSchema {}
