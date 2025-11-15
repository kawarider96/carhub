<?php

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     description="Felhasználói objektum",
 *
 *     @OA\Property(property="id", type="integer", example=17),
 *     @OA\Property(property="full_name", type="string", example="Teszt Elek"),
 *     @OA\Property(property="username", type="string", example="tesztuser"),
 *     @OA\Property(property="role", type="string", example="admin"),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="failed_logins", type="integer", example=0),
 *
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class UserSchema {}
