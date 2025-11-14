<?php

namespace App\Http\Requests\Admin\CarBrand;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:80', 'unique:car_brands,name'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'márkanév',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'A :attribute megadása kötelező.',
            'name.unique'   => 'Ez a :attribute már létezik.',
        ];
    }
}

