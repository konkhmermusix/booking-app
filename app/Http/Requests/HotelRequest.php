<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HotelRequest extends FormRequest
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
        $Id = $this->route('hotel'); // យក ID សម្រាប់ករណី Update
        return [
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:hotels,email,' . $Id,
            'phone'       => 'required|string|max:20',
            'address'     => 'required|string',
            'description' => 'nullable|string',
            'logo'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:0,1',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
        ];
    }
}
