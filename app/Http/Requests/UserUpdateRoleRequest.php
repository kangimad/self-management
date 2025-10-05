<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRoleRequest extends FormRequest
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
            'user_role' => ['required', 'array', 'min:1'],
            'user_role.*' => ['integer', Rule::exists('roles', 'id')],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_role.required' => 'Pilih minimal satu role untuk pengguna.',
            'user_role.array' => 'Format role tidak valid.',
            'user_role.min' => 'Pilih minimal satu role untuk pengguna.',
            'user_role.*.integer' => 'ID role harus berupa angka.',
            'user_role.*.exists' => 'Role yang dipilih tidak valid.',
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
            'user_role' => 'role pengguna',
            'user_role.*' => 'role',
        ];
    }
}