<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Services\BookingService;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index(Request $request)
    {
        $bookings = $this->bookingService->getAllBookings($request->all());

        if ($request->ajax()) {
            return view('admin.bookings.partials.booking_list', compact('bookings'))->render();
        }

        $hotels = Hotel::where('status', 1)->get();

        $rooms = Room::with('roomType', 'hotel')
            ->where('status', 'available')
            ->get();

        return view('admin.bookings.index', compact('bookings', 'hotels', 'rooms'));
    }

    public function store(BookingRequest $request)
    {
        try {
            $booking = $this->bookingService->createBooking($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'ការកក់បន្ទប់ជោគជ័យ!',
                'data' => $booking
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
