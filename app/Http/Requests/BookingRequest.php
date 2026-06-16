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
            // សម្គាល់ប្រភេទនៃការកក់: 'hotel' ឬ 'meeting_room'
            'booking_category' => 'required|in:hotel,meeting_room',

            // ព័ត៌មានភ្ញៀវ Walk-in
            'customer_name'    => 'required|string|max:255',
            'customer_phone'   => 'required|string|max:50',
            'special_requests' => 'nullable|string',

            // លក្ខខណ្ឌរួម
            'total_price'      => 'required|numeric|min:0',
            'payment_method'   => 'required|string',

            // លក្ខខណ្ឌសម្រាប់ Hotel
            'hotel_id'         => 'required_if:booking_category,hotel|nullable|exists:hotels,id',
            'room_id'          => 'required_if:booking_category,hotel|nullable|exists:rooms,id',
            'check_in'         => 'required_if:booking_category,hotel|nullable|date|after_or_equal:today',
            'check_out'        => 'required_if:booking_category,hotel|nullable|date|after:check_in',

            // លក្ខខណ្ឌសម្រាប់ Meeting Room
            'meeting_room_id'  => 'required_if:booking_category,meeting_room|nullable|exists:meeting_rooms,id',
            'start_date'       => 'required_if:booking_category,meeting_room|nullable|date|after_or_equal:today',
            'end_date'         => 'required_if:booking_category,meeting_room|nullable|date|after_or_equal:start_date',
            'start_time'       => 'required_if:booking_category,meeting_room|nullable|date_format:H:i',
            'end_time'         => 'required_if:booking_category,meeting_room|nullable|date_format:H:i',
            'total_hours'      => 'required_if:booking_category,meeting_room|nullable|numeric|min:0',
            'attendees_count'  => 'nullable|integer|min:1',
            'setup_style'      => 'nullable|string|max:255',
        ];
    }
}
