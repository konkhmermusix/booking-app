<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\RoomType;
use App\Models\Room;
use DB;

class CartController extends Controller
{


    // បង្ហាញទិន្នន័យក្នុងកន្ត្រកទំនិញ
    public function index()
    {

        $cart = session()->get('cart', []);

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += isset($item['total_price']) ? $item['total_price'] : 0;
        }

        return view('frontend.cart', compact('cart', 'subtotal'));
    }

    // លុបទំនិញ/បន្ទប់ ចេញពីកន្ត្រក
    public function remove($key)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'បានលុបចេញពីកន្ត្រករួចរាល់!');
    }

    public function addToCart(Request $request)
    {
        $id = $request->id;
        $roomType = RoomType::findOrFail($id);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            return response()->json(['message' => 'បន្ទប់នេះមានក្នុងបញ្ជីរួចហើយ', 'status' => 'warning']);
        }

        $cart[$id] = [
            "id" => $roomType->id,
            "name" => $roomType->name,
            "price" => $roomType->base_price,
            "image" => $roomType->images->first()->image_path ?? ''
        ];

        session()->put('cart', $cart);

        return response()->json([
            'message' => 'បន្ថែមទៅក្នុងបញ្ជីជោគជ័យ',
            'cart_count' => count($cart),
            'status' => 'success'
        ]);
    }

    public function getCartCount()
    {
        $cart = session()->get('cart', []);
        return response()->json(['count' => count($cart)]);
    }

    // សម្រាប់ថែម "បន្ទប់ស្នាក់នៅ" ចូលកន្ត្រក (គិតជាយប់)
    public function addHotel(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'check_in'     => 'required|date|after_or_equal:today',
            'check_out'    => 'required|date|after:check_in',
        ]);

        $roomTypeId = $request->input('room_type_id');
        $promoPrice = $request->input('promo_price');
        $checkIn    = $request->input('check_in');
        $checkOut   = $request->input('check_out');

        $availableRoom = Room::where('room_type_id', $roomTypeId)
            ->where('status', 'available')
            ->whereNotExists(function ($query) use ($checkIn, $checkOut) {
                $query->select(DB::raw(1))
                    ->from('hotel_bookings')
                    ->join('hotel_booking_details', 'hotel_bookings.id', '=', 'hotel_booking_details.hotel_booking_id')
                    ->whereColumn('rooms.id', 'hotel_booking_details.room_id')
                    ->whereIn('hotel_bookings.status', ['confirmed', 'pending'])
                    ->where(function ($q) use ($checkIn, $checkOut) {
                        $q->whereBetween('hotel_bookings.check_in', [$checkIn, $checkOut])
                            ->orWhereBetween('hotel_bookings.check_out', [$checkIn, $checkOut])
                            ->orWhere(function ($q2) use ($checkIn, $checkOut) {
                                $q2->where('hotel_bookings.check_in', '<=', $checkIn)
                                    ->where('hotel_bookings.check_out', '>=', $checkOut);
                            });
                    });
            })
            ->first();

        if (!$availableRoom) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'សូមទោស! បន្ទប់ប្រភេទនេះត្រូវបានគេកក់អស់ហើយ។'
                ], 422);
            }
            return redirect()->back()->with('error', 'សូមទោស! បន្ទប់ប្រភេទនេះត្រូវបានគេកក់អស់ហើយ។');
        }

        $roomType = RoomType::find($roomTypeId);

        $pricePerNight = !empty($promoPrice) ? $promoPrice : $roomType->base_price;

        $date1 = new \DateTime($checkIn);
        $date2 = new \DateTime($checkOut);
        $totalNights = $date1->diff($date2)->days;
        if ($totalNights <= 0) $totalNights = 1;

        $totalPrice = $pricePerNight * $totalNights;

        $cart = session()->get('cart', []);
        $cartKey = 'hotel_' . $roomTypeId . '_' . $checkIn . '_' . $checkOut;

        $cart[$cartKey] = [
            'id'           => $roomType->id,
            'type'         => 'hotel',
            'room_type_id' => $roomTypeId,
            'room_id'      => $availableRoom->id,
            'room_number'  => $availableRoom->room_number,
            'name'         => $roomType->name,
            'price'        => $pricePerNight,
            'check_in'     => $checkIn,
            'check_out'    => $checkOut,
            'total_nights' => $totalNights,
            'total_price'  => $totalPrice
        ];

        session()->put('cart', $cart);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'បានថែមចូលក្នុងកន្ត្រករួចរាល់!'
            ]);
        }

        return redirect()->back()->with('success', 'បានថែមចូលក្នុងកន្ត្រករួចរាល់!');
    }

    // សម្រាប់ថែម "សាលប្រជុំ" ចូលកន្ត្រក (គិតជាម៉ោង)
    public function addMeeting(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'start_date'   => 'required|date|after_or_equal:today',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'start_time'   => 'required',
            'end_time'     => 'required|after:start_time',
        ]);

        $roomTypeId = $request->input('room_type_id');
        $promoPrice = $request->input('promo_price');
        $startDate  = $request->input('start_date');
        $endDate    = $request->input('end_date');
        $startTime  = $request->input('start_time');
        $endTime    = $request->input('end_time');

        $availableRoom = Room::where('room_type_id', $roomTypeId)
            ->where('status', 'available')
            ->whereNotExists(function ($query) use ($startDate, $endDate, $startTime, $endTime) {
                $query->select(DB::raw(1))
                    ->from('meeting_bookings')
                    ->whereColumn('rooms.id', 'meeting_bookings.meeting_room_id')
                    ->whereIn('meeting_bookings.status', ['confirmed', 'pending'])
                    ->where(function ($q) use ($startDate, $endDate, $startTime, $endTime) {
                        $q->where('meeting_bookings.start_date', '<=', $endDate)
                            ->where('meeting_bookings.end_date', '>=', $startDate)
                            ->where('meeting_bookings.start_time', '<', $endTime)
                            ->where('meeting_bookings.end_time', '>', $startTime);
                    });
            })
            ->first();

        if (!$availableRoom) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'សូមទោស! សាលប្រជុំប្រភេទនេះត្រូវបានគេកក់អស់ហើយនៅក្នុងកំឡុងថ្ងៃ និងម៉ោងនេះ។'
                ], 422);
            }
            return redirect()->back()->with('error', 'សូមទោស! សាលប្រជុំប្រភេទនេះត្រូវបានគេកក់អស់ហើយនៅក្នុងកំឡុងថ្ងៃ និងម៉ោងនេះ។');
        }

        $roomType = RoomType::find($roomTypeId);

        $pricePerHour = !empty($promoPrice) ? $promoPrice : $roomType->base_price;

        $date1 = new \DateTime($startDate);
        $date2 = new \DateTime($endDate);
        $totalDays = $date1->diff($date2)->days + 1;

        $time1 = new \DateTime($startTime);
        $time2 = new \DateTime($endTime);
        $interval = $time1->diff($time2);
        $hoursPerDay = $interval->h + ($interval->i / 60);

        $totalHoursAllDays = $hoursPerDay * $totalDays;
        $totalPrice = $pricePerHour * $totalHoursAllDays;

        $cart = session()->get('cart', []);
        $cartKey = 'meeting_' . $roomTypeId . '_' . $startDate . '_' . str_replace(':', '', $startTime);

        $cart[$cartKey] = [
            'id'              => $roomType->id,
            'type'            => 'meeting',
            'room_type_id'    => $roomTypeId,
            'room_id'         => $availableRoom->id,
            'name'            => $roomType->name,
            'start_date'      => $startDate,
            'end_date'        => $endDate,
            'start_time'      => $startTime,
            'end_time'        => $endTime,
            'total_days'      => $totalDays,
            'total_hours'     => $totalHoursAllDays,
            'price'           => $pricePerHour,
            'total_price'     => $totalPrice
        ];

        session()->put('cart', $cart);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'បានថែមសាលប្រជុំចូលក្នុងកន្ត្រករួចរាល់!'
            ]);
        }

        return redirect()->back()->with('success', 'បានថែមសាលប្រជុំចូលក្នុងកន្ត្រករួចរាល់!');
    }
}
