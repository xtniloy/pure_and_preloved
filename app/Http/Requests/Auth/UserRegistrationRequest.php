<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => [
                'required',
                'email:rfc,dns,filter',
                'regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/',
//                'unique:users,email'
                Rule::unique('users', 'email')->where(function ($query) {
                    return $query->whereNotNull('email_verified_at');
                }),

            ],
            'phone' => ['required', 'regex:/^(\+8801[3-9][0-9]{8}|01[3-9][0-9]{8})$/'],
            'password' => [
                'required',
                'string',
                'min:6',              // must be at least 6 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                // 'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            'confirm_password' => 'required|string|same:password',
        ];
    }

    public function messages(): array
    {
        return [
            'password.regex' => 'Password length should at lest 6 character. It should contain at least one uppercase, one lowercase and one number',
            'confirm_password' => "The passwords provided do not match each other",
            'phone.regex' => 'The phone number must be a valid Bangladeshi number (e.g. +8801XXXXXXXXX or 01XXXXXXXXX)',
        ];
    }

    public function passedValidation()
    {
        $this->request->remove('confirm_password');
    }
}
