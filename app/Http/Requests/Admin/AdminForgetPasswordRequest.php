<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminForgetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email:rfc,dns,filter', 'exists:admins,email'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.exists' => 'No admin account found with this email address.',
        ];
    }
}
