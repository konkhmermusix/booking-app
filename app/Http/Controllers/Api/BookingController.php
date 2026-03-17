<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $fields = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'total_price' => 'required|numeric',
        ]);

        // បង្កើតការកក់ថ្មី
        $booking = Booking::create([
            'user_id' => $request->user()->id, // យក ID របស់ User ដែលកំពុង Login
            'hotel_id' => $fields['hotel_id'],
            'check_in' => $fields['check_in'],
            'check_out' => $fields['check_out'],
            'total_price' => $fields['total_price'],
            'status' => 'pending',
            'booking_code' => 'BK-' . strtoupper(Str::random(8)), // បង្កើតកូដសម្គាល់ការកក់
        ]);

        return response()->json([
            'success' => true,
            'message' => 'ការកក់របស់អ្នកត្រូវបានបញ្ជូនដោយជោគជ័យ',
            'data' => $booking
        ], 201);
    }

    // ទាញយកប្រវត្តិការកក់របស់ User ម្នាក់ៗ
    public function myBookings(Request $request)
    {
        $bookings = Booking::where('user_id', $request->user()->id)
            ->with('hotel')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($bookings);
    }
}