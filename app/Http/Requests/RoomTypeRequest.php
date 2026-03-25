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
            'max_guests'  => 'required|integer|min:1',
            'base_price'  => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'facilities'  => 'nullable|array',
            'facilities.*' => 'exists:facilities,id', // ពិនិត្យថា ID គ្រឿងបរិក្ខារមានពិតមែន
            'images'      => 'nullable|array',
            'images.*'    => 'image|mimes:jpeg,png,jpg,webp|max:2048' // បន្ថែម webp និងបន្ថយទំហំត្រឹម 2MB (សមរម្យសម្រាប់ Web)
        ];
    }
}
