<?php

/**
 * @OA\Schema(
 *     schema="UserRequestStoreRequest",
 *     type="object",
 *     required={"type"},
 *     description="Felhasználói kérés létrehozásához szükséges adatok",
 *
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         enum={"delete_account", "missing_brand"},
 *         example="delete_account",
 *         description="A kérés típusa"
 *     ),
 *
 *     @OA\Property(
 *         property="payload",
 *         type="object",
 *         nullable=true,
 *         description="Kiegészítő adatok (csak missing_brand esetén használatos)",
 *
 *         @OA\Property(
 *             property="brand",
 *             type="string",
 *             example="Lamborghini",
 *             description="A hiányzó autómárka neve (missing_brand esetén)"
 *         )
 *     )
 * )
 */
class UserRequestStoreRequestSchema {}
