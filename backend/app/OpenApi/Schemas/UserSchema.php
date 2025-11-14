<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     description="A rendszer felhasználója (admin vagy user).",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *
 *     @OA\Property(property="full_name", type="string", nullable=true, example="Kiss Péter"),
 *
 *     @OA\Property(property="username", type="string", example="kisspeter"),
 *
 *     @OA\Property(property="role", type="string", enum={"admin", "user"}, example="user"),
 *
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *
 *     @OA\Property(property="failed_logins", type="integer", example=0),
 *
 *     @OA\Property(
 *         property="is_admin",
 *         type="boolean",
 *         description="Származtatott érték: igaz, ha a role = admin",
 *         example=false
 *     ),
 *
 *     @OA\Property(
 *         property="is_locked",
 *         type="boolean",
 *         description="Származtatott érték: a felhasználói fiók zárolt-e",
 *         example=false
 *     ),
 *
 *     @OA\Property(
 *         property="favoriteCars",
 *         type="array",
 *         description="A felhasználó által rögzített kedvenc autók",
 *         @OA\Items(ref="#/components/schemas/FavoriteCar")
 *     ),
 *
 *     @OA\Property(
 *         property="requests",
 *         type="array",
 *         description="A felhasználó által indított kérelmek",
 *         @OA\Items(ref="#/components/schemas/UserRequest")
 *     ),
 *
 *     @OA\Property(
 *         property="handledRequests",
 *         type="array",
 *         description="A kérelmek, amelyeket ez az admin kezelt",
 *         @OA\Items(ref="#/components/schemas/UserRequest")
 *     ),
 *
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2025-11-14T10:23:00Z"
 *     ),
 *
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2025-11-14T10:23:00Z"
 *     )
 * )
 */
class UserSchema {}
