<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomBookingRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name'    => 'required|string|max:255',
            'customer_phone'   => 'required|string|max:20',
            'room_id'          => 'nullable|exists:rooms,id',
            'room_ids'         => 'nullable|array',
            'room_ids.*'       => 'exists:rooms,id',
            'check_in'         => 'required|date|after_or_equal:today',
            'check_out'        => 'required|date|after:check_in',
            'payment_method'   => 'required|string|in:cash,qr,bank_transfer,khqr',
            'payment_status'   => 'nullable|string|in:paid,pending,failed,refunded',
            'transaction_id'   => 'nullable|string|max:100',
            'total_price'      => 'required|numeric|min:0',
            'special_requests' => 'nullable|string',
            'hotel_id'         => 'nullable|exists:hotels,id',
        ];
    }
}
