<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="LoginResponse",
 *     type="object",
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="user", ref="#/components/schemas/User")
 * )
 */
class LoginResponseSchema {}
