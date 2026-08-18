<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomTypeRequest extends FormRequest
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
        // កំណត់ថាជាការ Update ឬ Store
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');

        return [
            'hotel_id'    => 'required|exists:hotels,id',
            'name'        => 'required|string|max:255',
            'category'    => 'nullable|string|in:stay,meeting',
            'max_guests'  => 'required|integer|min:1',
            'base_price'  => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'facilities'  => 'nullable|array',
            'facilities.*' => 'nullable|exists:facilities,id',
            'images'      => 'nullable|array',
            'images.*'    => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:10240'
        ];
    }
}
