<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserRequestResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'user_id'     => $this->user_id,
            'type'        => $this->type,
            'payload'     => $this->payload,
            'status'      => $this->status,
            'handled_by'  => $this->handled_by,
            'handled_at'  => $this->handled_at?->toISOString(),
            'user'        => UserResource::make($this->whenLoaded('user')),
            'handler'     => UserResource::make($this->whenLoaded('handler')),
            'created_at'  => $this->created_at?->toISOString(),
            'updated_at'  => $this->updated_at?->toISOString(),
        ];
    }
}

