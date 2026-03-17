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
    // public function rules(): array
    // {
    //     // ត្រូវប្រើ 'id' ឱ្យត្រូវតាមឈ្មោះ Parameter ក្នុង Route (Route::put('rooms/{id}', ...))
    //     $roomTypeId = $this->route('id') ?? $this->route('roomtype');

    //     return [
    //         'hotel_id' => 'required|exists:hotels,id',
    //         'name' => 'required|string|max:200',
    //         'description' => 'required|text',
    //         'base_price' => 'required|numeric',
    //         'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:20480'
    //     ];
    // }

    public function rules(): array
    {
        // ត្រូវប្រើ 'id' ឱ្យត្រូវតាមឈ្មោះ Parameter ក្នុង Route (Route::put('rooms/{id}', ...))
        $roomTypeId = $this->route('id') ?? $this->route('roomtype');

        return [
            'hotel_id'   => 'required|exists:hotels,id',
            'name'       => 'required|string|max:255',
            'max_guests' => 'required|integer|min:1',
            'base_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'facilities' => 'nullable|array', // ទទួលយក Array នៃ Facility IDs
            'images.*'   => 'nullable|image|mimes:jpeg,png,jpg|max:20480' // បើមានរូបភាពច្រើន
        ];
    }
}
