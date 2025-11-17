<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCarImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:5120',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'image' => 'kép',
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => 'A kép megadása kötelező.',
            'image.file'     => 'A feltöltött elemnek fájlnak kell lennie.',
            'image.image'    => 'Csak képfájl tölthető fel.',
            'image.mimes'    => 'A kép csak JPEG, JPG, PNG vagy WEBP formátumú lehet.',
            'image.max'      => 'A kép mérete legfeljebb 5 MB lehet.',
        ];
    }
}

