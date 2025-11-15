<?php

/**
 * @OA\Schema(
 *     schema="UserRequestSingleResponse",
 *     type="object",
 *     description="Egyetlen felhasználói kérés API válasza",
 *
 *     @OA\Property(property="success", type="boolean", example=true),
 *
 *     @OA\Property(property="message", type="string", example="Kérés jóváhagyva"),
 *
 *     @OA\Property(property="data", ref="#/components/schemas/UserRequest")
 * )
 */
class UserRequestSingleResponseSchema {}
