<?php

/**
 * @OA\Schema(
 *     schema="UserRequestListResponse",
 *     type="object",
 *     description="Felhasználói kérések listájának API válasza",
 *
 *     @OA\Property(property="success", type="boolean", example=true),
 *
 *     @OA\Property(property="message", type="string", example="Kérések listája"),
 *
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/UserRequest")
 *     )
 * )
 */
class UserRequestListResponseSchema {}
