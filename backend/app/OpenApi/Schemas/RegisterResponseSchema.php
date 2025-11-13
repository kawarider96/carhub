<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="RegisterResponse",
 *     type="object",
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="user", ref="#/components/schemas/User")
 * )
 */
class RegisterResponseSchema {}
