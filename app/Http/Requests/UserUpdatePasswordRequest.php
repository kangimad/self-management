<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserUpdatePasswordRequest extends FormRequest
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
            'current_password' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $user = $this->route('user');
                    if (!$user || !Hash::check($value, $user->password)) {
                        $fail('Password saat ini tidak sesuai.');
                    }
                },
            ],
            'new_password' => [
                'required',
                'string',
                'min:8',
                'different:current_password',
            ],
            'confirm_password' => [
                'required',
                'string',
                'same:new_password',
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'current_password' => 'password saat ini',
            'new_password' => 'password baru',
            'confirm_password' => 'konfirmasi password',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'current_password.required' => 'Password saat ini harus diisi.',
            'new_password.required' => 'Password baru harus diisi.',
            'new_password.min' => 'Password baru minimal harus :min karakter.',
            'new_password.different' => 'Password baru harus berbeda dengan password saat ini.',
            'confirm_password.required' => 'Konfirmasi password harus diisi.',
            'confirm_password.same' => 'Konfirmasi password tidak cocok dengan password baru.',
        ];
    }
}
