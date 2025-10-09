<?php

namespace App\Http\Requests\Finance;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class FinanceSourceTypeUpdateRequest extends FormRequest
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
                Rule::unique('finance_category_types', 'name')->ignore($this->route('result')),
            ],
            'description' => 'nullable|string',
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
            'name.unique' => 'Nama sudah digunakan.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'name.string' => 'Nama harus berupa teks.',
            'description.string' => 'Deskripsi harus berupa teks.',
        ];
    }
}
