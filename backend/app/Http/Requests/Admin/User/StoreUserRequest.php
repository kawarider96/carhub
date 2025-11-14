<?php

namespace App\Http\Requests\Admin\User;

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
            'full_name'     => ['nullable', 'string', 'max:120'],
            'username'      => ['required', 'string', 'max:60', 'unique:users,username'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'role'          => ['required', 'in:admin,user'],
            'is_active'     => ['sometimes', 'boolean'],
            'failed_logins' => ['sometimes', 'integer', 'min:0'],
        ];
    }

    public function attributes(): array
    {
        return [
            'full_name'     => 'teljes név',
            'username'      => 'felhasználónév',
            'password'      => 'jelszó',
            'password_confirmation' => 'jelszó megerősítése',
            'role'          => 'szerepkör',
            'is_active'     => 'aktív státusz',
            'failed_logins' => 'sikertelen belépések',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'A :attribute megadása kötelező.',
            'username.unique'   => 'A megadott :attribute már foglalt.',
            'password.min'      => 'A :attribute legalább :min karakter legyen.',
            'password.confirmed'=> 'A :attribute megerősítése nem egyezik.',
            'role.in'           => 'A :attribute csak admin vagy user lehet.',
        ];
    }
}

