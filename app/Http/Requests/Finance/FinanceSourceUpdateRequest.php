<?php

namespace App\Http\Requests\Finance;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class FinanceSourceUpdateRequest extends FormRequest
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
                Rule::unique('finance_sources', 'name')->ignore($this->route('result')),
            ],
            'description' => 'nullable|string',
            'source_type_id' => 'required|exists:finance_source_types,id',
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
            'source_type_id.required' => 'Tipe sumber harus dipilih.',
            'source_type_id.exists' => 'Tipe sumber tidak valid.',
            'user_id.required' => 'User harus dipilih.',
            'user_id.exists' => 'User tidak valid.',
        ];
    }
}
