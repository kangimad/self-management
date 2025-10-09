<?php

namespace App\Http\Requests\FInance;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class FinanceCategoryUpdateRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('finance_categories', 'name')->ignore($this->route('result')),
            ],
            'description' => 'nullable|string',
            'category_type_id' => 'required|exists:finance_category_types,id',
            'user_id' => 'required|exists:users,id',
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
            'name.required' => 'Nama harus diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'name.unique' => 'Nama sudah digunakan.',
            'description.string' => 'Deskripsi harus berupa teks.',
            'category_type_id.required' => 'Tipe kategori harus dipilih.',
            'category_type_id.exists' => 'Tipe kategori tidak valid.',
            'user_id.required' => 'User harus dipilih.',
            'user_id.exists' => 'User tidak valid.',
        ];
    }
}
