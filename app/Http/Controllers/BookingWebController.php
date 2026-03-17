<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\RoomType;
use App\Models\Room;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BookingWebController extends Controller
{

    // បង្ហាញ Booking Page (Optional)
    public function index()
    {
        $roomTypes = RoomType::withCount('rooms')->get();
        return view('frontend.booking', compact('roomTypes'));
    }

    // Save Booking (Ajax)
    public function store(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required',
            'check_in' => 'required|date',
            'check_out' => 'required|date',
            'guests' => 'required|integer'
        ]);

        // រកបន្ទប់ដែលនៅសល់
        $room = Room::where('room_type_id', $request->room_type_id)
            ->where('status', 'available')
            ->first();

        if (!$room) {
            return response()->json([
                'success' => false,
                'message' => 'អត់មានបន្ទប់ទេ'
            ]);
        }

        // បង្កើត Booking
        $booking = Booking::create([
            'booking_number' => 'BK-' . strtoupper(Str::random(8)),
            'user_id' => Auth::id(),
            'room_id' => $room->id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'guests' => $request->guests,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking Successful',
            'booking_id' => $booking->id
        ]);
    }

    // Booking History
    public function history()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('frontend.booking.history', compact('bookings'));
    }
}
