<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\RoomType;
use App\Models\Room;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class BookingWebController extends Controller
{

    // បង្ហាញ Booking Page (Optional)
    public function index()
    {
        $roomTypes = RoomType::withCount('rooms')->get();
        return view('frontend.booking', compact('roomTypes'));
    }

    public function storecart(Request $request)
    {
        // 1️⃣ Check if user is logged in
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'សូមចូលប្រើប្រាស់ (Login) ជាមុនសិន!'
            ]);
        }

        // 2️⃣ Validate input
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'check_in'     => 'required|date|after_or_equal:today',
            'check_out'    => 'required|date|after:check_in',
            'guests'       => 'required|integer|min:1'
        ]);

        // 3️⃣ Find RoomType and check base_price
        $roomType = RoomType::with('hotel')->findOrFail($request->room_type_id);

        if (!$roomType->base_price) {
            return response()->json([
                'success' => false,
                'message' => 'ប្រភេទបន្ទប់នេះមិនមានតម្លៃកំណត់នៅទេ!'
            ]);
        }

        // 4️⃣ Find available Room
        $room = Room::where('room_type_id', $request->room_type_id)
            ->where('status', 'available')
            ->first();

        if (!$room) {
            return response()->json([
                'success' => false,
                'message' => 'សុំទោស! ប្រភេទបន្ទប់នេះត្រូវបានគេកក់អស់ហើយ។'
            ]);
        }

        // 5️⃣ Calculate total price
        $checkIn  = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $nights   = max(1, $checkIn->diffInDays($checkOut));
        $totalPrice = $nights * $roomType->base_price;

        // 6️⃣ Create Booking
        try {
            $booking = Booking::create([
                'booking_code' => 'PNT-' . strtoupper(Str::random(8)),
                'user_id'      => Auth::id(),
                'hotel_id'     => $roomType->hotel_id,
                'room_id'      => $room->id,
                'check_in'     => $request->check_in,
                'check_out'    => $request->check_out,
                'total_price'  => $totalPrice,
                'status'       => 'pending',
            ]);

            return response()->json([
                'success'    => true,
                'booking_id' => $booking->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'មានបញ្ហាក្នុងការកក់: ' . $e->getMessage()
            ]);
        }
    }

    // បន្ថែមក្នុង Class BookingWebController
    public function checkout($id)
    {
        $booking = Booking::with('room.roomType')->findOrFail($id);
        return view('frontend.checkout', compact('booking'));
    }

    public function processPayment(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        // បើគាត់រើសបង់តាម QR យើងទុក status ជា pending សិន (រង់ចាំ Admin ឆែក Screenshot)
        // បើគាត់រើសបង់នៅសណ្ឋាគារ យើងដូរទៅ confirmed_at_hotel
        if ($request->payment_method == 'pay_at_hotel') {
            $booking->update(['status' => 'confirmed']);
        } else {
            $booking->update(['status' => 'pending']); // ឬ 'awaiting_payment'
        }

        return redirect()->route('booking.success', $booking->id);
    }

    public function success($id)
    {
        $booking = Booking::findOrFail($id);
        return view('frontend.booking_success', compact('booking'));
    }

    // Booking History
    public function history()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('frontend.history', compact('bookings'));
    }

    public function show($id)
    {
        $roomType = RoomType::with([
            'images',
            'facilities',
            'rooms',
            'hotel'
        ])->findOrFail($id);

        $similarRooms = RoomType::where('hotel_id', $roomType->hotel_id)
            ->where('id', '!=', $roomType->id)
            ->take(3)
            ->get();

        return view('frontend.details', compact(
            'roomType',
            'similarRooms'
        ));
    }

}
