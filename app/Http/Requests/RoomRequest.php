<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // ត្រូវប្រើ 'id' ឱ្យត្រូវតាមឈ្មោះ Parameter ក្នុង Route (Route::put('rooms/{id}', ...))
        $roomId = $this->route('id') ?? $this->route('room');

        return [
            'hotel_id' => 'required|exists:hotels,id',
            'room_type_id' => 'required|exists:room_types,id',
            'room_number' => [
                'required',
                'string',
                'max:50',
                // ឆែក Unique លេខបន្ទប់ក្នុងសណ្ឋាគារតែមួយ ប៉ុន្តែ Ignore ID ខ្លួនឯងពេល Update
                \Illuminate\Validation\Rule::unique('rooms')
                    ->where('hotel_id', $this->hotel_id)
                    ->ignore($roomId),
            ],
            'floor' => 'nullable|string|max:20', // ប្រាកដថាមានបន្ទាត់នេះទើប $request->validated() ចាប់យកទៅប្រើ
            'status' => 'required|in:available,booked,maintenance',
        ];
    }

    public function messages(): array
    {
        return [
            'room_number.unique' => 'លេខបន្ទប់នេះមានរួចហើយនៅក្នុងសណ្ឋាគារនេះ។',
        ];
    }
}