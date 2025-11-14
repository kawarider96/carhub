<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UploadCarImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'favorite_car_id' => ['required', 'integer', 'exists:favorite_cars,id'],
            'content'         => ['required', 'string'],
            'mime'            => ['nullable', 'string', 'max:64'],
        ];
    }

    public function attributes(): array
    {
        return [
            'favorite_car_id' => 'kedvenc autó azonosító',
            'content'         => 'kép tartalom',
            'mime'            => 'MIME típus',
        ];
    }

    public function messages(): array
    {
        return [
            'favorite_car_id.required' => 'A :attribute megadása kötelező.',
            'favorite_car_id.exists'   => 'A megadott :attribute nem létezik.',
            'content.required'         => 'A :attribute megadása kötelező.',
        ];
    }
}

