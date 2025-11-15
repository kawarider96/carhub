<?php

/**
 * @OA\Schema(
 *     schema="UserRequest",
 *     type="object",
 *     description="Felhasználói kérés objektum",
 *
 *     @OA\Property(property="id", type="integer", example=10),
 *
 *     @OA\Property(property="user_id", type="integer", example=5),
 *
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         example="delete_account",
 *         description="A kérés típusa (pl. fióktörlés)"
 *     ),
 *
 *     @OA\Property(
 *         property="payload",
 *         type="object",
 *         nullable=true,
 *         example={"brand": "Lamborghini", "reason": "Nincs a listában"}
 *     ),
 *
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         example="pending",
 *         description="pending, approved vagy rejected"
 *     ),
 *
 *     @OA\Property(property="handled_by", type="integer", nullable=true, example=1),
 *
 *     @OA\Property(
 *         property="handled_at",
 *         type="string",
 *         format="date-time",
 *         nullable=true,
 *         example="2025-01-12T14:22:15Z"
 *     ),
 *
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class UserRequestSchema {}
