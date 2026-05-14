<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ChatRequest extends FormRequest
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
            // កែ conversation_id ឱ្យទៅជា nullable ដើម្បីកុំឱ្យវាស្ទះពេលឆាតលើកដំបូង
            'conversation_id' => 'nullable',

            // កែ message និង images ឱ្យស្របតាមអ្វីដែល JavaScript ផ្ញើមក
            'message' => 'required_without:images|nullable|string',

            // images.* សម្រាប់ទទួលរូបភាពច្រើន (Array)
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',

            // ប្រសិនបើអ្នកផ្ញើតាមរយៈ field ឈ្មោះ file_path (Optional)
            'file_path' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}
