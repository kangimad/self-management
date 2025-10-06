<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RemoveUserFromRoleRequest extends FormRequest
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
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')],
            'role_id' => ['required', 'integer', Rule::exists('roles', 'id')],
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
            'user_id.required' => 'User ID diperlukan.',
            'user_id.integer' => 'User ID harus berupa angka.',
            'user_id.exists' => 'User tidak ditemukan.',
            'role_id.required' => 'Role ID diperlukan.',
            'role_id.integer' => 'Role ID harus berupa angka.',
            'role_id.exists' => 'Role tidak ditemukan.',
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
            'user_id' => 'User',
            'role_id' => 'Role',
        ];
    }
}
