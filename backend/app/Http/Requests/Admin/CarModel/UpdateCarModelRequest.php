<?php

namespace App\Http\Requests\Admin\CarModel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCarModelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'car_brand_id' => ['sometimes', 'integer', 'exists:car_brands,id'],
            'name'         => [
                'sometimes',
                'string',
                'max:80',
                Rule::unique('car_models')->ignore($id)->where(function ($q) {
                    $brandId = $this->input('car_brand_id');
                    if ($brandId) {
                        $q->where('car_brand_id', $brandId);
                    }
                }),
            ],
        ];
    }

    public function attributes(): array
    {
        return (new StoreCarModelRequest())->attributes();
    }

    public function messages(): array
    {
        return [
            'car_brand_id.exists' => 'A megadott :attribute nem létezik.',
            'name.unique'         => 'Ez a :attribute már létezik a megadott márkánál.',
        ];
    }
}

