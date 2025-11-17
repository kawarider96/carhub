<?php

namespace App\Http\Requests\Admin\CarBrand;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCarBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'name' => ['sometimes', 'string', 'max:80', Rule::unique('car_brands', 'name')->ignore($id)],
        ];
    }

    public function attributes(): array
    {
        return (new StoreCarBrandRequest())->attributes();
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Ez a :attribute már létezik.',
        ];
    }
}

