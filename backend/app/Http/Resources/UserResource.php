<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'full_name'     => $this->full_name,
            'username'      => $this->username,
            'role'          => $this->role,
            'is_active'     => (bool) $this->is_active,
            'failed_logins' => (int) ($this->failed_logins ?? 0),
            'created_at'    => $this->created_at?->toISOString(),
            'updated_at'    => $this->updated_at?->toISOString(),
        ];
    }
}

