<?php

/**
 * @OA\Schema(
 *     schema="UserUpdateRequest",
 *     type="object",
 *     description="Felhasználó módosítása (saját vagy admin)",
 *
 *     @OA\Property(property="full_name", type="string", example="Új Elek"),
 *     @OA\Property(property="username", type="string", example="ujelek"),
 *
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         nullable=true,
 *         example="UjJelszo123!",
 *         description="Csak akkor kell megadni, ha változik"
 *     ),
 *
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="role", type="string", example="admin")
 * )
 */
class UserUpdateRequestSchema {}
