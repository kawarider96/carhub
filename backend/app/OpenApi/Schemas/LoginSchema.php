<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="LoginRequest",
 *     type="object",
 *     required={"username","password"},
 *     @OA\Property(property="username", type="string", example="kisspeter"),
 *     @OA\Property(property="password", type="string", example="Secret123!")
 * )
 */
class LoginRequestSchema {}
