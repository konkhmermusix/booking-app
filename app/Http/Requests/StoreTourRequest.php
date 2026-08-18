<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTourRequest extends FormRequest
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
            'name'            => 'required|string|max:255',
            'distance'        => 'nullable|string|max:100',
            'google_map_link' => 'nullable|string',
            'images'          => 'nullable|array',
            'images.*'        => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,heic|max:10240',
            'description'     => 'nullable|string',
            'status'          => 'required'
        ];
    }
}
