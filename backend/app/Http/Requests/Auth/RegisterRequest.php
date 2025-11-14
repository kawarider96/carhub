<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['nullable', 'string', 'max:120'],
            'username'  => ['required', 'string', 'max:60', 'unique:users,username'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function attributes(): array
    {
        return [
            'full_name' => 'teljes név',
            'username'  => 'felhasználónév',
            'password'  => 'jelszó',
            'password_confirmation' => 'jelszó megerősítése',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'A :attribute megadása kötelező.',
            'username.unique'   => 'A megadott :attribute már foglalt.',
            'password.min'      => 'A :attribute legalább :min karakter legyen.',
            'password.confirmed'=> 'A :attribute megerősítése nem egyezik.',
        ];
    }
}

