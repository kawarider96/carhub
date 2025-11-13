<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="RegisterRequest",
 *     type="object",
 *     required={"name","username","password","password_confirmation"},
 *     @OA\Property(property="name", type="string", example="Kiss Péter"),
 *     @OA\Property(property="username", type="string", example="kissp"),
 *     @OA\Property(property="password", type="string", example="Secret123!"),
 *     @OA\Property(property="password_confirmation", type="string", example="Secret123!")
 * )
 */
class RegisterRequestSchema {}
