<?php

/**
 * @OA\Schema(
 *     schema="CarModelUpdateRequest",
 *     type="object",
 *     required={"name"},
 *     description="Autótípus módosításához szükséges adatok",
 *
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="Civic",
 *         description="A módosított típus neve"
 *     )
 * )
 */
class CarModelUpdateRequestSchema {}
