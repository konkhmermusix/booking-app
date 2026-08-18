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
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'customer_id'      => 'required|exists:users,id',
            'room_id'          => 'required|exists:rooms,id',
            'check_in_date'    => $isUpdate ? 'required|date' : 'required|date|after_or_equal:today',
            'check_out_date'   => 'required|date|after:check_in_date',
            'number_of_guests' => 'required|integer|min:1',
            'total_price'      => 'required|numeric|min:0',
            'payment_status'   => 'required|in:pending,paid,failed',
            'booking_status'   => 'required|in:pending,confirmed,completed,cancelled',
            'notes'            => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'customer_id.required'      => 'សូមជ្រើសរើសអតិថិជន!',
            'customer_id.exists'        => 'អតិថិជនដែលបានជ្រើសរើសមិនត្រឹមត្រូវឡើយ!',
            'room_id.required'          => 'សូមជ្រើសរើសបន្ទប់!',
            'room_id.exists'            => 'បន្ទប់ដែលបានជ្រើសរើសមិនត្រឹមត្រូវឡើយ!',
            'check_in_date.required'    => 'សូមជ្រើសរើសថ្ងៃចូលស្នាក់នៅ!',
            'check_in_date.date'        => 'ថ្ងៃចូលស្នាក់នៅត្រូវតែជាកាលបរិច្ឆេទត្រឹមត្រូវ!',
            'check_in_date.after_or_equal' => 'ថ្ងៃចូលស្នាក់នៅមិនអាចមុនថ្ងៃនេះបានទេ!',
            'check_out_date.required'   => 'សូមជ្រើសរើសថ្ងៃចាកចេញ!',
            'check_out_date.date'       => 'ថ្ងៃចាកចេញត្រូវតែជាកាលបរិច្ឆេទត្រឹមត្រូវ!',
            'check_out_date.after'      => 'ថ្ងៃចាកចេញត្រូវតែក្រោយថ្ងៃចូលស្នាក់នៅ!',
            'number_of_guests.required' => 'សូមបញ្ជាក់ចំនួនភ្ញៀវ!',
            'number_of_guests.integer'  => 'ចំនួនភ្ញៀវត្រូវតែជាចំនួនគត់!',
            'number_of_guests.min'      => 'Double Check ចំនួនភ្ញៀវត្រូវតែធំជាង ឬស្មើ ១!',
            'total_price.required'      => 'សូមបញ្ចូលតម្លៃសរុប!',
            'total_price.numeric'       => 'តម្លៃសរុបត្រូវតែជាលេខ!',
            'total_price.min'           => 'តម្លៃសរុបមិនអាចអវិជ្ជមានឡើយ!',
            'payment_status.required'   => 'សូមជ្រើសរើសស្ថានភាពការបង់ប្រាក់!',
            'payment_status.in'         => 'ស្ថានភាពការបង់ប្រាក់មិនត្រឹមត្រូវ!',
            'booking_status.required'   => 'សូមជ្រើសរើសស្ថានភាពការកក់!',
            'booking_status.in'         => 'ស្ថានភាពការកក់មិនត្រឹមត្រូវ!',
        ];
    }
}
