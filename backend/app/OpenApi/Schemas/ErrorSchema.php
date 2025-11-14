<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     type="object",
 *     description="Egységes hiba válasz az API-ban",
 *
 *     @OA\Property(
 *         property="message",
 *         type="string",
 *         example="Validation failed."
 *     ),
 *
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         nullable=true,
 *         example={
 *             "email": {"The email field is required."},
 *             "password": {"The password must be at least 8 characters."}
 *         }
 *     )
 * )
 */
class ErrorResponseSchema {}
