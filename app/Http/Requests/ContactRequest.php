<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            'name'        => 'required|string|max:255',
            'email'       => 'nullable|email|max:255',
            'tell'        => 'required|string|max:20',
            'description' => 'required|string|min:5',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'សូមបញ្ចូលឈ្មោះរបស់អ្នក',
            'email.email' => 'ទម្រង់អាសយដ្ឋានអ៊ីមែលមិនត្រឹមត្រូវឡើយ',
            'tell.required' => 'សូមបញ្ចូលលេខទូរស័ព្ទ',
            'description.required' => 'សូមបញ្ចូលសាររបស់អ្នក',
            'description.min' => 'សាររបស់អ្នកត្រូវមានយ៉ាងហោចណាស់ ៥ តួអក្សរ',
        ];
    }
}
