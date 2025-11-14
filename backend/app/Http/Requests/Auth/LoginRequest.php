<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:60'],
            'password' => ['required', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'username' => 'felhasználónév',
            'password' => 'jelszó',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'A :attribute megadása kötelező.',
            'password.required' => 'A :attribute megadása kötelező.',
        ];
    }
}

