<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     description="Felhasználói adatokat tartalmazó séma.",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Kiss Péter"),
 *     @OA\Property(property="username", type="string", example="kisspeter"),
 *     @OA\Property(property="role", type="string", enum={"admin","user"}, example="user"),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="failed_logins", type="integer", example=0),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class UserSchema {}
