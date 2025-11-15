<?php

/**
 * @OA\Schema(
 *     schema="ValidationErrorResponse",
 *     type="object",
 *
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Validációs hiba"),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         example={"name": {"A név mező kitöltése kötelező."}}
 *     )
 * )
 */
class ValidationErrorResponseSchema {}
