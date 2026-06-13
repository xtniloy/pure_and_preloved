<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->guard('admin')->check();
    }

    public function rules(): array
    {
        return [
            'name'   => ['required', 'string', 'max:255'],
            'email'  => ['required', 'email:rfc,dns,filter', 'unique:admins,email,' . $this->route('admin')->id],
            'status' => ['required', 'numeric', 'in:0,1'],
        ];
    }
}
