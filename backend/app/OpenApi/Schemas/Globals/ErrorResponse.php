<?php

/**
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     type="object",
 *
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Hibás kérés"),
 *     @OA\Property(property="errors", type="object", nullable=true)
 * )
 */
class ErrorResponseSchema {}
