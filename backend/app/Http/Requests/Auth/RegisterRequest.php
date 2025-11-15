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

                'username'  => [
                    'required',
                    'string',
                    'max:60',
                    'unique:users,username',
                ],

                'password'  => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/[a-z]/',                     // kisbetű
                    'regex:/[A-Z]/',                     // nagybetű
                    'regex:/[0-9]/',                     // szám
                    'regex:/[!@#$%^&*(),.?":{}|<>]/',    // speciális karakter
                    'confirmed',
                ],
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
                'username.max'      => 'A :attribute legfeljebb :max karakter lehet.',
                
                'password.required' => 'A :attribute megadása kötelező.',
                'password.min'      => 'A :attribute legalább :min karakter hosszú legyen.',
                'password.confirmed'=> 'A :attribute megerősítése nem egyezik.',

                'password.regex' => 'A jelszónak tartalmaznia kell kisbetűt, nagybetűt, számot és speciális karaktert.',

                'password.regex.lower' => 'A jelszónak tartalmaznia kell legalább egy kisbetűt.',
                'password.regex.upper' => 'A jelszónak tartalmaznia kell legalább egy nagybetűt.',
                'password.regex.number'=> 'A jelszónak tartalmaznia kell legalább egy számot.',
                'password.regex.special' => 'A jelszónak tartalmaznia kell legalább egy speciális karaktert.',
            ];
        }
    }

