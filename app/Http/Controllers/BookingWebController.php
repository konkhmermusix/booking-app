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

    // បង្ហាញ Booking Page (Optional)
    public function index()
    {
        $roomTypes = RoomType::withCount('rooms')->get();
        return view('frontend.booking', compact('roomTypes'));
    }

    public function store(Request $request)
    {
        // 1. Validation: បន្ថែម special_requests ក្នុង list
        $request->validate([
            'hotel_id' => 'required',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'room_type_id' => 'required|exists:room_types,id',
            'room_id' => 'nullable|exists:rooms,id',
            'special_requests' => 'nullable|string|max:500', // បន្ថែម validation សម្រាប់សំណូមពរពិសេស
        ]);

        try {
            return DB::transaction(function () use ($request) {

                // 2. ទាញទិន្នន័យ RoomType
                $roomType = RoomType::findOrFail($request->room_type_id);

                // 3. ស្វែងរកបន្ទប់មួយដែលទំនេរ (Available) តាមប្រភេទបន្ទប់ដែលរើស
                // នេះនឹងជួយកុំឱ្យ room_id ចេញ NULL ក្នុងតារាង bookings
                $availableRoom = Room::where('room_type_id', $request->room_type_id)
                    ->where('status', 'available')
                    ->first();

                // 4. គណនាចំនួនថ្ងៃស្នាក់នៅ
                $checkIn = Carbon::parse($request->check_in);
                $checkOut = Carbon::parse($request->check_out);
                $days = $checkIn->diffInDays($checkOut);
                if ($days <= 0) $days = 1;

                // 5. គណនាតម្លៃសរុប
                $calculatedTotalPrice = $roomType->base_price * $days;

                // 6. បង្កើតលេខកូដកក់
                $bookingCode = 'PNT-' . strtoupper(Str::random(6));

                // 7. រក្សាទុកក្នុងតារាង Bookings
                // បន្ថែម special_requests និងការពារ user_id កុំឱ្យ NULL
                $booking = Booking::create([
                    'user_id'          => auth()->id(), // ត្រូវប្រាកដថា User បាន Login
                    'hotel_id'         => $request->hotel_id,
                    'room_id'          => $availableRoom ? $availableRoom->id : null, // បញ្ចូល room_id ដែលរកឃើញ
                    'booking_code'     => $bookingCode,
                    'check_in'         => $request->check_in,
                    'check_out'        => $request->check_out,
                    'total_price'      => $calculatedTotalPrice,
                    'status'           => 'pending',
                    'special_requests' => $request->special_requests, // រក្សាទុកសំណូមពរពិសេស
                ]);

                // 8. រក្សាទុកក្នុងតារាង Booking Details
                BookingDetail::create([
                    'booking_id'       => $booking->id,
                    'room_id'          => $availableRoom ? $availableRoom->id : null,
                    'room_type_id'     => $request->room_type_id,
                    'price_at_booking' => $calculatedTotalPrice,
                ]);

                // 9. បង្កើតតារាង Payment
                Payment::create([
                    'booking_id' => $booking->id,
                    'method'     => $request->payment_method ?? 'cash',
                    'amount'     => $calculatedTotalPrice,
                    'status'     => 'pending',
                ]);

                // ប្រសិនបើកក់ជោគជ័យ អាចដូរបន្ទប់នោះទៅជា 'occupied' ឬ 'booked' (តាមតម្រូវការប្រព័ន្ធ)
                // if($availableRoom) { $availableRoom->update(['status' => 'booked']); }

                return redirect()->route('success', $bookingCode)
                    ->with('success', 'ការកក់របស់អ្នកត្រូវបានទទួលជោគជ័យ!');
            });
        } catch (\Exception $e) {
            return back()->with('error', 'មានបញ្ហាបច្ចេកទេស៖ ' . $e->getMessage());
        }
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
