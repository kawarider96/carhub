<?php

namespace App\Http\Requests\Admin\CarModel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCarModelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'car_brand_id' => ['required', 'integer', 'exists:car_brands,id'],
            'name'         => [
                'required',
                'string',
                'max:80',
                Rule::unique('car_models')->where(fn ($q) => $q->where('car_brand_id', $this->input('car_brand_id'))),
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'car_brand_id' => 'márka azonosító',
            'name'         => 'típus név',
        ];
    }

    public function messages(): array
    {
        return [
            'car_brand_id.required' => 'A :attribute megadása kötelező.',
            'car_brand_id.exists'   => 'A megadott :attribute nem létezik.',
            'name.required'         => 'A :attribute megadása kötelező.',
            'name.unique'           => 'Ez a :attribute már létezik a megadott márkánál.',
        ];
    }
}

