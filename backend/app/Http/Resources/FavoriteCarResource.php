<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteCarResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'user_id'      => $this->user_id,
            'car_model_id' => $this->car_model_id,
            'year'         => $this->year,
            'color'        => $this->color,
            'fuel'         => $this->fuel,
            'model'        => CarModelResource::make($this->whenLoaded('carModel')),
            'images'       => CarImageResource::collection($this->whenLoaded('images')),
            'created_at'   => $this->created_at?->toISOString(),
            'updated_at'   => $this->updated_at?->toISOString(),
        ];
    }
}

