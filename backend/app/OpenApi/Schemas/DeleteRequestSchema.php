<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="DeleteRequest",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=7),
 *     @OA\Property(property="user_id", type="integer", example=3),
 *     @OA\Property(property="status", type="string", enum={"pending","approved","rejected"}, example="pending"),
 *     @OA\Property(property="handled_by", type="integer", nullable=true, example=1),
 *     @OA\Property(property="user", ref="#/components/schemas/User")
 * )
 */
class DeleteRequestSchema {}
