<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'images'   => ['required'],
            'images.*' => [
                'required',
                'file',
                'max:5120',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'images.required'   => 'Legalább egy képet fel kell tölteni.',
            'images.array'      => 'A képek mezőnek tömbnek kell lennie.',
            'images.min'        => 'Legalább egy képet fel kell tölteni.',

            'images.*.required' => 'Minden kép megadása kötelező.',
            'images.*.file'     => 'Minden feltöltött elemnek fájlnak kell lennie.',
            'images.*.image'    => 'Csak képfájlok tölthetők fel.',
            'images.*.mimes'    => 'A képek csak JPEG, JPG, PNG vagy WEBP formátumúak lehetnek.',
            'images.*.max'      => 'Egy kép mérete legfeljebb 5 MB lehet.',
        ];
    }

    public function attributes(): array
    {
        return [
            'images'   => 'képek',
            'images.*' => 'kép',
        ];
    }
}

