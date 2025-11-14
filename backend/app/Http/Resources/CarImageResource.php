<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CarImageResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $content = (string) ($this->content ?? '');
        $encoded = $this->ensureBase64($content);

        return [
            'id'               => $this->id,
            'favorite_car_id'  => $this->favorite_car_id,
            'content'          => $encoded,
            'mime'             => $this->mime,
            'created_at'       => $this->created_at?->toISOString(),
            'updated_at'       => $this->updated_at?->toISOString(),
        ];
    }

    private function ensureBase64(?string $data): ?string
    {
        if ($data === null) {
            return null;
        }
        if ($data === '') {
            return '';
        }
        $decoded = base64_decode($data, true);
        if ($decoded !== false && base64_encode($decoded) === $data) {
            return $data; // already base64
        }
        return base64_encode($data);
    }
}

