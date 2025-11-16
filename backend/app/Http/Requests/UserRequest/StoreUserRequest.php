<?php

namespace App\Http\Requests\UserRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'    => ['required', 'in:delete_account,missing_brand'],
            'payload' => ['nullable', 'array'],
        ];
    }

    public function attributes(): array
    {
        return [
            'type'    => 'kérelem típusa',
            'payload' => 'kérelem adatai',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required'    => 'A :attribute megadása kötelező.',
            'type.in'          => 'A :attribute csak a megengedett értékek egyike lehet. (delete_account, missing_brand)',
        ];
    }
}

