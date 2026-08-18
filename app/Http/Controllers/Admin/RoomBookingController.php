<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomBookingRequest;
use App\Services\RoomBookingService;   
use App\Models\Hotel;
use App\Models\Room;
use App\Models\HotelBooking;
use Illuminate\Http\Request;

class RoomBookingController extends Controller
{
    protected RoomBookingService $roomBookingService;

    public function __construct(RoomBookingService $roomBookingService)
    {
        $this->roomBookingService = $roomBookingService;
    }

    public function index(Request $request)
    {
        $bookings = $this->roomBookingService->getAllBookings($request->all());

        if ($request->ajax()) {
            return view('admin.room_bookings.partials.booking_list', compact('bookings'))->render();
        }

        $hotels = Hotel::where('status', 1)->get();

        $rooms = Room::with('roomType', 'hotel')
            ->where('status', '!=', 'maintenance')
            ->whereHas('roomType', function ($q) {
                $q->where('name', 'not like', '%សាលប្រជុំ%')
                  ->where('name', 'not like', '%Meeting%')
                  ->where('name', 'not like', '%Conference%')
                  ->where('name', 'not like', '%Hall%')
                  ->where('name', 'not like', '%Ballroom%');
            })
            ->get();

        return view('admin.room_bookings.index', compact('bookings', 'hotels', 'rooms'));
    }

    public function store(RoomBookingRequest $request)
    {
        try {
            $booking = $this->roomBookingService->createBooking($request->validated());

            if ($request->wantsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => 'ការកក់បន្ទប់ជោគជ័យ',
                    'data' => $booking
                ]);
            }

            return redirect()->back()->with('success', 'ការកក់បន្ទប់ជោគជ័យ');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'មានបញ្ហាក្នុងការរក្សាទុក: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->withInput()->with('error', 'មានបញ្ហាក្នុងការរក្សាទុក: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $booking = HotelBooking::with('details')->findOrFail($id);

            $roomIds = !empty($request->room_ids) 
                ? array_filter((array)$request->room_ids) 
                : (!empty($request->room_id) ? [$request->room_id] : [$booking->room_id]);

            $primaryRoomId = reset($roomIds) ?: $booking->room_id;

            $oldRoomIds = $booking->details->pluck('room_id')->toArray();
            if (empty($oldRoomIds) && $booking->room_id) {
                $oldRoomIds = [$booking->room_id];
            }

            $booking->update([
                'customer_name'    => $request->customer_name ?? $booking->customer_name,
                'customer_phone'   => $request->customer_phone ?? $booking->customer_phone,
                'customer_email'   => $request->customer_email ?? $booking->customer_email,
                'room_id'          => $primaryRoomId,
                'check_in'         => $request->check_in ?? $booking->check_in,
                'check_out'        => $request->check_out ?? $booking->check_out,
                'total_price'      => $request->total_price ?? $booking->total_price,
                'payment_method'   => $request->payment_method ?? $booking->payment_method,
                'special_requests' => $request->special_requests ?? $booking->special_requests,
                'status'           => $request->status ?? $booking->status,
            ]);

            // Sync multi-room details
            if (!empty($request->room_ids)) {
                $booking->details()->delete();
                foreach ($roomIds as $rId) {
                    $room = Room::with('roomType')->find($rId);
                    if ($room) {
                        $booking->details()->create([
                            'room_id'          => $room->id,
                            'room_type_id'     => $room->room_type_id,
                            'price_at_booking' => $room->roomType->base_price ?? 0,
                        ]);
                    }
                }
            }

            // Sync room statuses
            $newStatus = $request->status ?? $booking->status;
            $allAffectedRooms = array_unique(array_merge($oldRoomIds, $roomIds));

            if (in_array($newStatus, ['completed', 'cancelled'])) {
                Room::whereIn('id', $allAffectedRooms)->update(['status' => 'available']);
            } else {
                $freedRooms = array_diff($oldRoomIds, $roomIds);
                if (!empty($freedRooms)) {
                    Room::whereIn('id', $freedRooms)->update(['status' => 'available']);
                }
                Room::whereIn('id', $roomIds)->update(['status' => 'booked']);
            }

            // Sync payment details in payments table
            $payStatus = $request->payment_status ?? 'paid';
            $payMethod = in_array($request->payment_method ?? $booking->payment_method, ['qr', 'bank_transfer', 'khqr']) ? 'qr' : 'cash';

            \App\Models\Payment::updateOrCreate(
                ['hotel_booking_id' => $booking->id],
                [
                    'method'         => $payMethod,
                    'amount'         => $request->total_price ?? $booking->total_price,
                    'currency'       => 'USD',
                    'transaction_id' => $request->transaction_id ?? null,
                    'status'         => $payStatus,
                    'paid_at'        => $payStatus === 'paid' ? now() : null,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'បានធ្វើបច្ចុប្បន្នភាពការកក់បន្ទប់ដោយជោគជ័យ',
                'data'    => $booking
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'មានបញ្ហាក្នុងការធ្វើបច្ចុប្បន្នភាព: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $booking = HotelBooking::findOrFail($id);
            if ($booking->room_id) {
                Room::where('id', $booking->room_id)->update(['status' => 'available']);
            }
            $booking->delete();

            return response()->json([
                'success' => true,
                'message' => 'បានលុបការកក់បន្ទប់ដោយជោគជ័យ'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'មានបញ្ហាក្នុងការលុប: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAvailableRooms(Request $request)
    {
        try {
            $checkIn = $request->get('check_in');
            $checkOut = $request->get('check_out');
            $excludeId = $request->get('exclude_booking_id');

            $allRooms = Room::with('roomType', 'hotel')
                ->whereHas('roomType', function ($q) {
                    $q->where('name', 'not like', '%សាលប្រជុំ%')
                      ->where('name', 'not like', '%Meeting%')
                      ->where('name', 'not like', '%Conference%')
                      ->where('name', 'not like', '%Hall%')
                      ->where('name', 'not like', '%Ballroom%');
                })
                ->get();

            if (!$checkIn || !$checkOut) {
                return response()->json([
                    'success' => true,
                    'busy_room_ids' => [],
                    'rooms' => $allRooms
                ]);
            }

            $cInStr = date('Y-m-d', strtotime($checkIn));
            $cOutStr = date('Y-m-d', strtotime($checkOut));

            // Find busy room IDs from primary room_id in hotel_bookings table
            $busyRoomIds = HotelBooking::where('status', '!=', 'cancelled')
                ->when($excludeId, function($q) use ($excludeId) {
                    $q->where('id', '!=', $excludeId);
                })
                ->where(function($q) use ($cInStr, $cOutStr) {
                    $q->whereRaw('DATE(check_in) < ?', [$cOutStr])
                      ->whereRaw('DATE(check_out) > ?', [$cInStr]);
                })
                ->pluck('room_id')
                ->filter()
                ->toArray();

            // Find busy room IDs from hotel_booking_details table (multi-room bookings)
            $busyDetailRoomIds = DB::table('hotel_booking_details')
                ->join('hotel_bookings', 'hotel_booking_details.hotel_booking_id', '=', 'hotel_bookings.id')
                ->where('hotel_bookings.status', '!=', 'cancelled')
                ->when($excludeId, function($q) use ($excludeId) {
                    $q->where('hotel_bookings.id', '!=', $excludeId);
                })
                ->whereRaw('DATE(hotel_bookings.check_in) < ?', [$cOutStr])
                ->whereRaw('DATE(hotel_bookings.check_out) > ?', [$cInStr])
                ->pluck('hotel_booking_details.room_id')
                ->filter()
                ->toArray();

            $allBusyIds = array_values(array_map('intval', array_unique(array_merge($busyRoomIds, $busyDetailRoomIds))));

            return response()->json([
                'success' => true,
                'busy_room_ids' => $allBusyIds,
                'rooms' => $allRooms
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function printInvoice($id)
    {
        $booking = HotelBooking::with(['user', 'hotel', 'room.roomType', 'details.room.roomType', 'details.roomType', 'payment'])
            ->where('id', $id)
            ->orWhere('booking_code', $id)
            ->firstOrFail();

        return view('admin.room_bookings.print_invoice', compact('booking'));
    }
}
