<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('id');

        return [
            'full_name'     => ['sometimes', 'nullable', 'string', 'max:120'],
            'username'      => ['sometimes', 'string', 'max:60', Rule::unique('users', 'username')->ignore($userId)],
            'password'      => ['sometimes', 'string', 'min:8', 'confirmed'],
            'role'          => ['sometimes', 'in:admin,user'],
            'is_active'     => ['sometimes', 'boolean'],
            'failed_logins' => ['sometimes', 'integer', 'min:0'],
        ];
    }

    public function attributes(): array
    {
        // Ne duplikáljunk – a StoreUserRequest attribútumait használjuk
        return (new StoreUserRequest())->attributes();
    }

    public function messages(): array
    {
        return [
            'username.unique'   => 'A megadott :attribute már foglalt.',
            'password.min'      => 'A :attribute legalább :min karakter legyen.',
            'password.confirmed'=> 'A :attribute megerősítése nem egyezik.',
            'role.in'           => 'A :attribute csak admin vagy user lehet.',
        ];
    }
}

