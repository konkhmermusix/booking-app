<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'hotel_id'        => 'required|exists:hotels,id',
            'room_id'         => 'required|exists:rooms,id',
            'check_in'        => 'required|date|after_or_equal:today',
            'check_out'       => 'required|date|after:check_in',
            'total_price'     => 'required|numeric|min:0',
            'payment_method'  => 'required|string|in:cash,qr,card,transfer',
            'special_requests' => 'nullable|string',
        ];
    }
}
