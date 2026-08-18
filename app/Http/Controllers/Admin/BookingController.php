<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelBooking;
use App\Models\HotelBookingDetail;
use App\Models\MeetingBooking;
use App\Models\User;
use App\Models\Room;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    // Display a listing of bookings from hotel_bookings and meeting_bookings.
    public function index(Request $request)
    {
        $category = $request->get('category', 'all');
        $search = $request->get('search');
        $status = $request->get('status');

        if ($category === 'meeting_room') {
            $query = MeetingBooking::with(['user', 'room.roomType', 'payment']);
            if ($request->filled('search')) {
                $searchStr = $request->search;
                $query->where(function ($q) use ($searchStr) {
                    $q->where('booking_code', 'like', '%' . $searchStr . '%')
                        ->orWhere('customer_name', 'like', '%' . $searchStr . '%')
                        ->orWhere('customer_phone', 'like', '%' . $searchStr . '%')
                        ->orWhereHas('user', function ($u) use ($searchStr) {
                            $u->where('name', 'like', '%' . $searchStr . '%')
                                ->orWhere('email', 'like', '%' . $searchStr . '%');
                        })
                        ->orWhereHas('room', function ($r) use ($searchStr) {
                            $r->where('room_number', 'like', '%' . $searchStr . '%');
                        });
                });
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            $bookings = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        } else if ($category === 'hotel') {
            $query = HotelBooking::with(['user', 'hotel', 'room.roomType', 'details.roomType', 'details.room', 'payment']);
            if ($request->filled('search')) {
                $searchStr = $request->search;
                $query->where(function ($q) use ($searchStr) {
                    $q->where('booking_code', 'like', '%' . $searchStr . '%')
                        ->orWhere('customer_name', 'like', '%' . $searchStr . '%')
                        ->orWhere('customer_phone', 'like', '%' . $searchStr . '%')
                        ->orWhereHas('user', function ($u) use ($searchStr) {
                            $u->where('name', 'like', '%' . $searchStr . '%')
                                ->orWhere('email', 'like', '%' . $searchStr . '%');
                        })
                        ->orWhereHas('room', function ($r) use ($searchStr) {
                            $r->where('room_number', 'like', '%' . $searchStr . '%');
                        });
                });
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            $bookings = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        } else {
            $hotelQuery = HotelBooking::with(['user', 'hotel', 'room.roomType', 'details.roomType', 'details.room', 'payment']);
            $meetingQuery = MeetingBooking::with(['user', 'room.roomType', 'payment']);

            if ($request->filled('search')) {
                $searchStr = $request->search;
                $hotelQuery->where(function ($q) use ($searchStr) {
                    $q->where('booking_code', 'like', '%' . $searchStr . '%')
                        ->orWhere('customer_name', 'like', '%' . $searchStr . '%')
                        ->orWhere('customer_phone', 'like', '%' . $searchStr . '%')
                        ->orWhereHas('user', function ($u) use ($searchStr) {
                            $u->where('name', 'like', '%' . $searchStr . '%')
                                ->orWhere('email', 'like', '%' . $searchStr . '%');
                        })
                        ->orWhereHas('room', function ($r) use ($searchStr) {
                            $r->where('room_number', 'like', '%' . $searchStr . '%');
                        });
                });

                $meetingQuery->where(function ($q) use ($searchStr) {
                    $q->where('booking_code', 'like', '%' . $searchStr . '%')
                        ->orWhere('customer_name', 'like', '%' . $searchStr . '%')
                        ->orWhere('customer_phone', 'like', '%' . $searchStr . '%')
                        ->orWhereHas('user', function ($u) use ($searchStr) {
                            $u->where('name', 'like', '%' . $searchStr . '%')
                                ->orWhere('email', 'like', '%' . $searchStr . '%');
                        })
                        ->orWhereHas('room', function ($r) use ($searchStr) {
                            $r->where('room_number', 'like', '%' . $searchStr . '%');
                        });
                });
            }

            if ($request->filled('status')) {
                $hotelQuery->where('status', $request->status);
                $meetingQuery->where('status', $request->status);
            }

            $hotelBookings = $hotelQuery->get();
            $meetingBookings = $meetingQuery->get();

            $combined = $hotelBookings->concat($meetingBookings)->sortByDesc('created_at')->values();

            $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
            $perPage = 10;
            $itemsForCurrentPage = $combined->slice(($page - 1) * $perPage, $perPage)->values();

            $bookings = new \Illuminate\Pagination\LengthAwarePaginator(
                $itemsForCurrentPage,
                $combined->count(),
                $perPage,
                $page,
                [
                    'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
                    'query' => $request->query(),
                ]
            );
        }

        if ($request->ajax()) {
            return view('admin.bookings.partials.booking_list', compact('bookings', 'category'))->render();
        }

        $customers = User::where('role', 'customer')->orderBy('name')->get();
        if ($customers->isEmpty()) {
            $customers = User::orderBy('name')->get();
        }

        $hotels = Hotel::where('status', 1)->get();
        $rooms = Room::with(['roomType.hotel'])->orderBy('room_number')->get();
        $meetingRooms = Room::with('roomType')->whereHas('roomType', function ($q) {
            $q->where('category', 'meeting');
        })->get();

        return view('admin.bookings.index', compact('bookings', 'customers', 'hotels', 'rooms', 'meetingRooms', 'category'));
    }

    // Store a newly created booking in hotel_bookings / hotel_booking_details or meeting_bookings.
    public function store(Request $request)
    {
        $bookingCategory = $request->input('booking_category', 'hotel');

        if ($bookingCategory === 'meeting_room') {
            $request->validate([
                'meeting_room_id' => 'required|exists:rooms,id',
                'start_date'      => 'required|date',
                'end_date'        => 'required|date',
                'start_time'      => 'required',
                'end_time'        => 'required',
                'total_hours'     => 'required|numeric',
                'total_price'     => 'required|numeric|min:0',
                'payment_method'  => 'required|string',
            ]);

            try {
                $booking = DB::transaction(function () use ($request) {
                    $bookingCode = 'PNT-' . strtoupper(Str::random(6));

                    $walkInInfo = !empty($request->customer_name) ? "ភ្ញៀវកក់ផ្ទាល់ ឈ្មោះ: {$request->customer_name} | លេខទូរស័ព្ទ: {$request->customer_phone}" : "";
                    $finalRequests = !empty($request->special_requests)
                        ? ($walkInInfo ? "{$walkInInfo} | {$request->special_requests}" : $request->special_requests)
                        : $walkInInfo;

                    $meetingBooking = MeetingBooking::create([
                        'booking_code'     => $bookingCode,
                        'booking_type'     => 'walk_in',
                        'user_id'          => $request->customer_id ?? auth()->id(),
                        'customer_name'    => $request->customer_name,
                        'customer_phone'   => $request->customer_phone,
                        'customer_email'   => $request->customer_email,
                        'meeting_room_id'  => $request->meeting_room_id,
                        'start_date'       => $request->start_date,
                        'end_date'         => $request->end_date,
                        'start_time'       => $request->start_time,
                        'end_time'         => $request->end_time,
                        'total_hours'      => $request->total_hours,
                        'total_price'      => $request->total_price,
                        'status'           => 'confirmed',
                        'payment_method'   => $request->payment_method ?? 'cash',
                        'attendees_count'  => $request->attendees_count,
                        'setup_style'      => $request->setup_style,
                        'special_requests' => $finalRequests,
                    ]);

                    Room::where('id', $request->meeting_room_id)->update(['status' => 'booked']);

                    return $meetingBooking;
                });

                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'ការកក់បន្ទប់ប្រជុំត្រូវបានបង្កើតជោគជ័យ',
                        'data'    => $booking->load(['user', 'room.roomType'])
                    ]);
                }

                return redirect()->route('bookings.index')->with('success', 'ការកក់បន្ទប់ប្រជុំត្រូវបានបង្កើតជោគជ័យ');
            } catch (\Exception $e) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'មានបញ្ហាក្នុងការបង្កើត៖ ' . $e->getMessage()], 422);
                }
                return back()->withInput()->with('error', 'មានបញ្ហាក្នុងការបង្កើត៖ ' . $e->getMessage());
            }
        } else {
            // Hotel Room Booking
            $request->validate([
                'room_id'        => 'required|exists:rooms,id',
                'check_in'       => 'required|date',
                'check_out'      => 'required|date',
                'total_price'    => 'required|numeric|min:0',
                'payment_method' => 'required|string',
            ]);

            try {
                $booking = DB::transaction(function () use ($request) {
                    $bookingCode = 'PNT-' . strtoupper(Str::random(6));
                    $room = Room::with('roomType')->findOrFail($request->room_id);

                    $walkInInfo = !empty($request->customer_name) ? "ភ្ញៀវកក់ផ្ទាល់ ឈ្មោះ: {$request->customer_name} | លេខទូរស័ព្ទ: {$request->customer_phone}" : "";
                    $finalRequests = !empty($request->special_requests)
                        ? ($walkInInfo ? "{$walkInInfo} | {$request->special_requests}" : $request->special_requests)
                        : $walkInInfo;

                    $hotelBooking = HotelBooking::create([
                        'booking_code'     => $bookingCode,
                        'booking_type'     => 'walk_in',
                        'user_id'          => $request->customer_id ?? auth()->id(),
                        'customer_name'    => $request->customer_name,
                        'customer_phone'   => $request->customer_phone,
                        'customer_email'   => $request->customer_email,
                        'hotel_id'         => $room->hotel_id ?? 1,
                        'room_id'          => $room->id,
                        'check_in'         => $request->check_in,
                        'check_out'        => $request->check_out,
                        'check_in_time'    => '14:00:00',
                        'check_out_time'   => '12:00:00',
                        'total_price'      => $request->total_price,
                        'status'           => 'confirmed',
                        'payment_method'   => $request->payment_method ?? 'cash',
                        'special_requests' => $finalRequests,
                    ]);

                    // Save to hotel_booking_details table
                    HotelBookingDetail::create([
                        'hotel_booking_id' => $hotelBooking->id,
                        'room_type_id'     => $room->room_type_id,
                        'room_id'          => $room->id,
                        'price_at_booking' => $request->total_price,
                    ]);

                    if ($hotelBooking->status === 'confirmed') {
                        $room->update(['status' => 'booked']);
                    }

                    return $hotelBooking;
                });

                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'ការកក់បន្ទប់សណ្ឋាគារត្រូវបានបង្កើតជោគជ័យ',
                        'data'    => $booking->load(['user', 'hotel', 'room.roomType', 'details'])
                    ]);
                }

                return redirect()->route('bookings.index')->with('success', 'ការកក់បន្ទប់សណ្ឋាគារត្រូវបានបង្កើតជោគជ័យ');
            } catch (\Exception $e) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'មានបញ្ហាក្នុងការបង្កើត៖ ' . $e->getMessage()], 422);
                }
                return back()->withInput()->with('error', 'មានបញ្ហាក្នុងការបង្កើត៖ ' . $e->getMessage());
            }
        }
    }

    /**
     * Update specified booking in hotel_bookings or meeting_bookings.
     */
    public function update(Request $request, $id)
    {
        $category = $request->input('booking_category');

        try {
            if ($category === 'meeting_room' || $request->has('meeting_room_id')) {
                $booking = MeetingBooking::findOrFail($id);
                $booking->update([
                    'meeting_room_id'  => $request->meeting_room_id ?? $booking->meeting_room_id,
                    'start_date'       => $request->start_date ?? $booking->start_date,
                    'end_date'         => $request->end_date ?? $booking->end_date,
                    'start_time'       => $request->start_time ?? $booking->start_time,
                    'end_time'         => $request->end_time ?? $booking->end_time,
                    'total_hours'      => $request->total_hours ?? $booking->total_hours,
                    'total_price'      => $request->total_price ?? $booking->total_price,
                    'payment_method'   => $request->payment_method ?? $booking->payment_method,
                    'special_requests' => $request->special_requests ?? $booking->special_requests,
                ]);
            } else {
                $booking = HotelBooking::findOrFail($id);

                $booking->update([
                    'room_id'          => $request->room_id ?? $booking->room_id,
                    'check_in'         => $request->check_in ?? $booking->check_in,
                    'check_out'        => $request->check_out ?? $booking->check_out,
                    'total_price'      => $request->total_price ?? $booking->total_price,
                    'payment_method'   => $request->payment_method ?? $booking->payment_method,
                    'special_requests' => $request->special_requests ?? $booking->special_requests,
                ]);

                // Update detail record
                if ($request->filled('room_id')) {
                    $room = Room::find($request->room_id);
                    if ($room) {
                        HotelBookingDetail::where('hotel_booking_id', $booking->id)->update([
                            'room_id'      => $room->id,
                            'room_type_id' => $room->room_type_id,
                        ]);
                    }
                }
            }

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'ការកក់ត្រូវបានធ្វើបច្ចុប្បន្នភាពជោគជ័យ',
                    'data'    => $booking
                ]);
            }

            return redirect()->route('bookings.index')->with('success', 'ការកក់ត្រូវបានធ្វើបច្ចុប្បន្នភាពជោគជ័យ');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'មានបញ្ហាក្នុងការកែសម្រួល៖ ' . $e->getMessage()], 422);
            }
            return back()->withInput()->with('error', 'មានបញ្ហាក្នុងការកែសម្រួល៖ ' . $e->getMessage());
        }
    }

    /**
     * Quick status update for hotel_bookings or meeting_bookings.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'category' => 'nullable|string'
        ]);

        $newStatus = $request->status;
        $category = $request->category;

        $booking = null;
        if ($category === 'meeting_room') {
            $booking = MeetingBooking::find($id);
        } else {
            $booking = HotelBooking::find($id);
            if (!$booking) {
                $booking = MeetingBooking::find($id);
            }
        }

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'រកមិនឃើញការកក់នេះឡើយ'], 404);
        }

        $booking->status = $newStatus;
        $booking->save();

        // Update room status accordingly
        $roomId = $booking->room_id ?? $booking->meeting_room_id;
        if ($roomId) {
            if ($newStatus === 'confirmed') {
                Room::where('id', $roomId)->update(['status' => 'booked']);
            } elseif (in_array($newStatus, ['completed', 'cancelled'])) {
                Room::where('id', $roomId)->update(['status' => 'available']);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'ស្ថានភាពការកក់ត្រូវបានធ្វើបច្ចុប្បន្នភាព',
            'data'    => $booking
        ]);
    }

    /**
     * Destroy a booking from hotel_bookings or meeting_bookings.
     */
    public function destroy($id)
    {
        try {
            $booking = HotelBooking::find($id);
            if ($booking) {
                if ($booking->room_id) {
                    Room::where('id', $booking->room_id)->update(['status' => 'available']);
                }
                HotelBookingDetail::where('hotel_booking_id', $booking->id)->delete();
                $booking->delete();
            } else {
                $meetingBooking = MeetingBooking::find($id);
                if ($meetingBooking) {
                    if ($meetingBooking->meeting_room_id) {
                        Room::where('id', $meetingBooking->meeting_room_id)->update(['status' => 'available']);
                    }
                    $meetingBooking->delete();
                }
            }

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'ការកក់ត្រូវបានលុបដោយជោគជ័យ'
                ]);
            }

            return redirect()->route('bookings.index')->with('success', 'ការកក់ត្រូវបានលុបដោយជោគជ័យ');
        } catch (\Exception $e) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'មានបញ្ហាក្នុងការលុប៖ ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'មានបញ្ហាក្នុងការលុប៖ ' . $e->getMessage());
        }
    }

    /**
     * Download or view PDF invoice.
     */
    public function downloadInvoice($id)
    {
        $booking = HotelBooking::with(['user', 'hotel', 'room.roomType', 'details'])->find($id);
        $isMeeting = false;
        if (!$booking) {
            $booking = MeetingBooking::with(['user', 'room.roomType'])->find($id);
            $isMeeting = true;
        }

        if (!$booking) {
            abort(404, 'រកមិនឃើញទិន្នន័យការកក់ឡើយ');
        }

        return view('admin.room_bookings.print_invoice', compact('booking', 'isMeeting'));
    }
}
