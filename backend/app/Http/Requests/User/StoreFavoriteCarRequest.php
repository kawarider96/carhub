<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreFavoriteCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $maxYear = (int) date('Y') + 1;

        return [
            'car_model_id' => ['required', 'integer', 'exists:car_models,id'],
            'year'         => ['nullable', 'integer', "between:1886,{$maxYear}"],
            'color'        => ['nullable', 'string', 'max:40'],
            'fuel'         => ['nullable', 'string', 'max:40'],
        ];
    }

    public function attributes(): array
    {
        return [
            'car_model_id' => 'autótípus',
            'year'         => 'évjárat',
            'color'        => 'szín',
            'fuel'         => 'üzemanyag',
        ];
    }

    public function messages(): array
    {
        return [
            'car_model_id.required' => 'A :attribute megadása kötelező.',
            'car_model_id.exists'   => 'A megadott :attribute nem létezik.',
            'year.integer'          => 'Az :attribute csak szám lehet.',
            'year.between'          => 'Az :attribute értéke 1886 és :max között legyen.',
        ];
    }
}

