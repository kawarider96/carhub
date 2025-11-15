<?php

/**
 * @OA\Schema(
 *     schema="UserListResponse",
 *     type="object",
 *     description="Felhasználók listájának API válasza (admin)",
 *
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Felhasználók listája"),
 *
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/User")
 *     )
 * )
 */
class UserListResponseSchema {}
