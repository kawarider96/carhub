<?php

/**
 * @OA\Schema(
 *     schema="SuccessResponse",
 *     type="object",
 *
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Sikeres művelet"),
 *     @OA\Property(property="data", nullable=true)
 * )
 */
class SuccessResponseSchema {}
