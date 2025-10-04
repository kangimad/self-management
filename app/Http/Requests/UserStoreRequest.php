<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_name' => ['required', 'string', 'max:255'],
            'user_email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'user_password' => ['required', 'string', 'min:8', 'confirmed:user_password_confirmation'],
            'user_password_confirmation' => ['required', 'string', 'min:8'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'user_role' => ['required', 'array', 'min:1'],
            'user_role.*' => ['exists:roles,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_name.required' => 'Nama wajib diisi.',
            'user_name.string' => 'Nama harus berupa teks.',
            'user_name.max' => 'Nama maksimal 255 karakter.',
            'user_email.required' => 'Email wajib diisi.',
            'user_email.email' => 'Format email tidak valid.',
            'user_email.unique' => 'Email sudah digunakan.',
            'user_password.required' => 'Password wajib diisi.',
            'user_password.min' => 'Password minimal 8 karakter.',
            'user_password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'user_password_confirmation.required' => 'Konfirmasi password wajib diisi.',
            'user_password_confirmation.min' => 'Konfirmasi password minimal 8 karakter.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Gambar harus berformat jpeg, png, jpg, atau gif.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
            'user_role.required' => 'Role wajib dipilih.',
            'user_role.min' => 'Minimal pilih 1 role.',
            'user_role.*.exists' => 'Role tidak valid.',
        ];
    }
}
