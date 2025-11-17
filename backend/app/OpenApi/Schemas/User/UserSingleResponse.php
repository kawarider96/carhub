<?php

/**
 * @OA\Schema(
 *     schema="UserSingleResponse",
 *     type="object",
 *     description="Egy felhasználó API válasza",
 *
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Felhasználó adatai"),
 *     @OA\Property(property="data", ref="#/components/schemas/User")
 * )
 */
class UserSingleResponseSchema {}
