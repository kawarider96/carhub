<?php

/**
 * @OA\Schema(
 *     schema="UserStoreRequest",
 *     type="object",
 *     required={"full_name", "username", "password", "role"},
 *     description="Admin által létrehozott felhasználó",
 *
 *     @OA\Property(property="full_name", type="string", example="Teszt Elek"),
 *     @OA\Property(property="username", type="string", example="tesztuser123"),
 *
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         example="Teszt1234!",
 *         description="Minimum 8 karakter, kisbetű, nagybetű, szám, speciális"
 *     ),
 *
 *     @OA\Property(property="role", type="string", example="user"),
 *     @OA\Property(property="is_active", type="boolean", example=true)
 * )
 */
class UserStoreRequestSchema {}
