<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     type="object",
 *     @OA\Property(property="message", type="string", example="Validation failed."),
 *     @OA\Property(property="errors", type="object")
 * )
 */
class ErrorSchema {}
