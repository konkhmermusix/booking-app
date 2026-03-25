<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromotionRequest extends FormRequest
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
    public function rules()
    {
        return [
            'room_type_id'     => 'required|exists:room_types,id',
            'title'            => 'required|string|max:255',
            'tag'              => 'nullable|string|max:50',
            'description'      => 'nullable|string',
            'original_price'   => 'required|numeric|min:0',
            'discounted_price' => 'required|numeric|min:0|lt:original_price',
            'expiry_date'      => 'required|date|after:today',
            'image_path'       => 'nullable|image|mimes:jpeg,png,jpg|max:20480',
            'status'           => 'boolean'
        ];
    }

    public function messages()
    {
        return [
            'discounted_price.lt' => 'តម្លៃបញ្ចុះត្រូវតែតូចជាងតម្លៃដើម។',
            'expiry_date.after' => 'ថ្ងៃផុតកំណត់ត្រូវតែជាថ្ងៃនៅពេលអនាគត (ក្រោយថ្ងៃនេះ)។',
            'room_type_id.required' => 'សូមជ្រើសរើសប្រភេទបន្ទប់។',
            'image_path.max' => 'រូបភាពមិនត្រូវលើសពី 20MB ឡើយ។',
        ];
    }
}
