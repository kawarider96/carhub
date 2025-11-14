<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="UserRequest",
 *     type="object",
 *     description="Felhasználói kérés (fióktörlés vagy hiányzó márka kérés)",
 *
 *     @OA\Property(property="id", type="integer", example=7),
 *     @OA\Property(property="user_id", type="integer", example=3),
 *
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         enum={"delete_account", "missing_brand"},
 *         example="delete_account"
 *     ),
 *
 *     @OA\Property(
 *         property="payload",
 *         type="object",
 *         nullable=true,
 *         example={
 *             "brand_name": "Bugatti"
 *         }
 *     ),
 *
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"open", "approved", "rejected"},
 *         example="open"
 *     ),
 *
 *     @OA\Property(property="handled_by", type="integer", nullable=true, example=1),
 *
 *     @OA\Property(
 *         property="handled_at",
 *         type="string",
 *         format="date-time",
 *         nullable=true,
 *         example="2025-11-14T12:45:00Z"
 *     ),
 *
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2025-11-14T09:21:00Z"
 *     ),
 *
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2025-11-14T10:15:00Z"
 *     ),
 *
 *     @OA\Property(
 *         property="user",
 *         ref="#/components/schemas/User"
 *     ),
 *
 *     @OA\Property(
 *         property="handler",
 *         ref="#/components/schemas/User",
 *         nullable=true
 *     )
 * )
 */
class UserRequestSchema {}
