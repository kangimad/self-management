<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
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
        $roleId = $this->route('role') ? $this->route('role') : null;

        return [
            'role_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($roleId),
            ],
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'role_name.required' => 'Nama role wajib diisi.',
            'role_name.string' => 'Nama role harus berupa teks.',
            'role_name.max' => 'Nama role maksimal 255 karakter.',
            'role_name.unique' => 'Nama role sudah digunakan.',
            'permissions.array' => 'Permission harus berupa array.',
            'permissions.*.string' => 'Permission harus berupa teks.',
            'permissions.*.exists' => 'Permission tidak valid.',
        ];
    }
}
