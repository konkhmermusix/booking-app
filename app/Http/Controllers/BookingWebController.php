<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\RoomType;
use App\Models\Room;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\BookingDetail;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class BookingWebController extends Controller
{
    public function myBookings()
    {
        $userId = Auth::id();

        // 1. កែប្រែ៖ Join ថែមទៅកាន់ room_types ដើម្បីយកឈ្មោះប្រភេទបន្ទប់សណ្ឋាគារ
        $hotelBookings = DB::table('hotel_bookings')
            ->leftJoin('payments', 'hotel_bookings.id', '=', 'payments.hotel_booking_id')
            ->leftJoin('rooms', 'hotel_bookings.room_id', '=', 'rooms.id')
            ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id') // 🔥 Join ថែមត្រង់នេះ
            ->where('hotel_bookings.user_id', $userId)
            ->select(
                'hotel_bookings.*',
                'payments.method as payment_method',
                'payments.status as payment_status',
                'rooms.room_number as room_name',
                'room_types.name as room_type_name' // 🔥 ទាញយកឈ្មោះប្រភេទបន្ទប់ (ឧទាហរណ៍៖ Single, Deluxe)
            )
            ->orderBy('hotel_bookings.created_at', 'desc')
            ->get();

        // 2. ទាញយកប្រវត្តិកក់ សាលប្រជុំ
        $meetingBookings = DB::table('meeting_bookings')
            ->leftJoin('payments', 'meeting_bookings.id', '=', 'payments.meeting_booking_id')
            ->leftJoin('rooms', 'meeting_bookings.meeting_room_id', '=', 'rooms.id')
            ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('meeting_bookings.user_id', $userId)
            ->select(
                'meeting_bookings.*',
                'payments.method as payment_method',
                'payments.status as payment_status',
                'rooms.room_number as room_name',
                'room_types.name as room_type_name'
            )
            ->orderBy('meeting_bookings.created_at', 'desc')
            ->get();

        return view('frontend.mybookings', compact('hotelBookings', 'meetingBookings'));
    }

    public function viewReceipt($code)
    {
        $booking = DB::table('hotel_bookings')
            ->where('booking_code', $code)
            ->first();

        $type = 'hotel';
        $payment = null;
        $details = null;

        if ($booking) {
            $payment = DB::table('payments')
                ->where('hotel_booking_id', $booking->id)
                ->select('payments.*', 'payments.status as payment_status')
                ->first();

            $details = DB::table('hotel_booking_details')
                ->join('rooms', 'hotel_booking_details.room_id', '=', 'rooms.id')
                ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('hotel_booking_details.hotel_booking_id', $booking->id)
                ->select('hotel_booking_details.*', 'rooms.room_number as name', 'room_types.name as type_name')
                ->first();
        } else {
            $booking = DB::table('meeting_bookings')
                ->where('booking_code', $code)
                ->first();

            $type = 'meeting';

            if ($booking) {
                $payment = DB::table('payments')
                    ->where('meeting_booking_id', $booking->id)
                    ->select('payments.*', 'payments.status as payment_status')
                    ->first();

                $details = DB::table('rooms')
                    ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                    ->where('rooms.id', $booking->meeting_room_id)
                    ->select('rooms.room_number as name', 'room_types.name as type_name')
                    ->first();
            }
        }

        if (!$booking) {
            return redirect()->route('home')->with('error', 'រកមិនឃើញវិក្កយបត្រដែលអ្នកស្វែងរកឡើយ!');
        }

        return view('frontend.receipt', compact('booking', 'payment', 'details', 'type'));
    }
}
