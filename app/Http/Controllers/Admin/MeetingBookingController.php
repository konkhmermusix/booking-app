<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MeetingBooking;
use App\Models\Room;
use App\Models\Hotel;
use App\Models\Payment;

class MeetingBookingController extends Controller
{
    public function index(Request $request)
    {
        $meetingCondition = function ($query) {
            $query->where('name', 'like', '%សាលប្រជុំ%')
                ->orWhere('name', 'like', '%សាលប្រជុំធំ%')
                ->orWhere('name', 'like', '%សាលប្រជុំមធ្យម%')
                ->orWhere('name', 'like', '%សាលប្រជុំតូច%')
                ->orWhere('name', 'like', '%Meeting%')
                ->orWhere('name', 'like', '%Conference%')
                ->orWhere('name', 'like', '%Hall%')
                ->orWhere('name', 'like', '%Ballroom%');
        };

        $meetingRooms = Room::with('roomType', 'hotel')
            ->where('status', '!=', 'maintenance')
            ->whereHas('roomType', $meetingCondition)
            ->get();

        $hotels = Hotel::where('status', 1)->get();

        $query = MeetingBooking::with(['user', 'room.roomType', 'payment']);

        if ($request->filled('search')) {
            $searchStr = $request->search;
            $query->where(function ($q) use ($searchStr) {
                $q->where('booking_code', 'LIKE', '%' . $searchStr . '%')
                    ->orWhere('customer_name', 'LIKE', '%' . $searchStr . '%')
                    ->orWhere('customer_phone', 'LIKE', '%' . $searchStr . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return view('admin.meeting_bookings.partials.booking_list', compact('bookings'))->render();
        }

        return view('admin.meeting_bookings.index', compact('bookings', 'meetingRooms', 'hotels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'meeting_room_id'  => 'required|exists:rooms,id',
            'customer_name'    => 'required|string|max:255',
            'customer_phone'   => 'required|string|max:50',
            'start_date'       => 'required|date|after_or_equal:today',
            'end_date'         => 'required|date|after_or_equal:start_date',
            'start_time'       => 'required',
            'end_time'         => 'required',
            'total_hours'      => 'required|numeric|min:0',
            'total_price'      => 'required|numeric|min:0',
            'payment_method'   => 'required|string',
            'setup_style'      => 'nullable|string',
            'attendees_count'  => 'nullable|integer|min:1',
            'special_requests' => 'nullable|string',
        ]);

        try {
            $booking = DB::transaction(function () use ($request) {

                $walkInInfo = 'ភ្ញៀវកក់ផ្ទាល់ ឈ្មោះ: ' . $request->customer_name
                    . ' | លេខទូរស័ព្ទ: ' . $request->customer_phone;
                $finalRequests = !empty($request->special_requests)
                    ? $walkInInfo . ' | មតិផ្សេងៗ: ' . $request->special_requests
                    : $walkInInfo;

                $booking = MeetingBooking::create([
                    'booking_code'     => 'PNT-' . strtoupper(\Illuminate\Support\Str::random(6)),
                    'booking_type'     => 'walk_in',
                    'user_id'          => auth()->id(),
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
                    'payment_method'   => $request->payment_method,
                    'attendees_count'  => $request->attendees_count,
                    'setup_style'      => $request->setup_style,
                    'special_requests' => $finalRequests,
                    'status'           => 'confirmed',
                ]);

                Room::where('id', $request->meeting_room_id)->update(['status' => 'booked']);

                $payStatus = $request->payment_status ?? 'paid';
                $payMethod = in_array($request->payment_method ?? 'cash', ['qr', 'khqr']) ? 'qr' : 'cash';

                Payment::create([
                    'meeting_booking_id' => $booking->id,
                    'method'             => $payMethod,
                    'amount'             => $request->total_price,
                    'currency'           => 'USD',
                    'transaction_id'     => $request->transaction_id ?? null,
                    'status'             => $payStatus,
                    'paid_at'            => $payStatus === 'paid' ? now() : null,
                ]);

                return $booking;
            });

            return response()->json([
                'success' => true,
                'message' => 'បានកក់សាលប្រជុំដោយជោគជ័យ',
                'data'    => $booking,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'មានបញ្ហាក្នុងការរក្សាទុក: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $booking = MeetingBooking::findOrFail($id);
            $booking->update([
                'customer_name'    => $request->customer_name ?? $booking->customer_name,
                'customer_phone'   => $request->customer_phone ?? $booking->customer_phone,
                'customer_email'   => $request->customer_email ?? $booking->customer_email,
                'meeting_room_id'  => $request->meeting_room_id ?? $booking->meeting_room_id,
                'start_date'       => $request->start_date ?? $booking->start_date,
                'end_date'         => $request->end_date ?? $booking->end_date,
                'start_time'       => $request->start_time ?? $booking->start_time,
                'end_time'         => $request->end_time ?? $booking->end_time,
                'total_hours'      => $request->total_hours ?? $booking->total_hours,
                'total_price'      => $request->total_price ?? $booking->total_price,
                'payment_method'   => $request->payment_method ?? $booking->payment_method,
                'attendees_count'  => $request->attendees_count ?? $booking->attendees_count,
                'setup_style'      => $request->setup_style ?? $booking->setup_style,
                'special_requests' => $request->special_requests ?? $booking->special_requests,
                'status'           => $request->status ?? $booking->status,
            ]);

            if (isset($request->status)) {
                if (in_array($request->status, ['completed', 'cancelled'])) {
                    Room::where('id', $booking->meeting_room_id)->update(['status' => 'available']);
                } elseif ($request->status === 'confirmed') {
                    Room::where('id', $booking->meeting_room_id)->update(['status' => 'booked']);
                }
            }

            $payStatus = $request->payment_status ?? 'paid';
            $payMethod = in_array($request->payment_method ?? $booking->payment_method, ['qr', 'khqr']) ? 'qr' : 'cash';

            Payment::updateOrCreate(
                ['meeting_booking_id' => $booking->id],
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
                'message' => 'បានធ្វើបច្ចុប្បន្នភាពកក់សាលប្រជុំដោយជោគជ័យ',
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
            $booking = MeetingBooking::findOrFail($id);
            if ($booking->meeting_room_id) {
                Room::where('id', $booking->meeting_room_id)->update(['status' => 'available']);
            }
            $booking->delete();

            return response()->json([
                'success' => true,
                'message' => 'បានលុបការកក់សាលប្រជុំដោយជោគជ័យ'
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
            $startDate = $request->get('start_date');
            $endDate   = $request->get('end_date');
            $startTime = $request->get('start_time');
            $endTime   = $request->get('end_time');
            $excludeId = $request->get('exclude_booking_id');

            $allRooms = Room::with('roomType', 'hotel')
                ->whereHas('roomType', function ($q) {
                    $q->where(function ($sub) {
                        $sub->where('name', 'like', '%សាលប្រជុំ%')
                            ->orWhere('name', 'like', '%Meeting%')
                            ->orWhere('name', 'like', '%Conference%')
                            ->orWhere('name', 'like', '%Hall%')
                            ->orWhere('name', 'like', '%Ballroom%');
                    });
                })
                ->get();

            if (!$startDate || !$endDate) {
                return response()->json([
                    'success' => true,
                    'busy_room_ids' => [],
                    'rooms' => $allRooms
                ]);
            }

            $sDateStr = date('Y-m-d', strtotime($startDate));
            $eDateStr = date('Y-m-d', strtotime($endDate));
            $sTimeStr = $startTime ? date('H:i:s', strtotime($startTime)) : '00:00:00';
            $eTimeStr = $endTime ? date('H:i:s', strtotime($endTime)) : '23:59:59';

            $allBusyIds = MeetingBooking::where('status', '!=', 'cancelled')
                ->when($excludeId, function ($q) use ($excludeId) {
                    $q->where('id', '!=', $excludeId);
                })
                ->where(function ($q) use ($sDateStr, $eDateStr, $sTimeStr, $eTimeStr) {
                    $q->whereRaw('DATE(start_date) <= ?', [$eDateStr])
                        ->whereRaw('DATE(end_date) >= ?', [$sDateStr])
                        ->whereRaw('TIME(start_time) < ?', [$eTimeStr])
                        ->whereRaw('TIME(end_time) > ?', [$sTimeStr]);
                })
                ->pluck('meeting_room_id')
                ->filter()
                ->toArray();

            return response()->json([
                'success' => true,
                'busy_room_ids' => array_values(array_map('intval', array_unique($allBusyIds))),
                'rooms' => $allRooms
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function printInvoice($id)
    {
        $booking = MeetingBooking::with(['user', 'room.roomType', 'payment'])
            ->where('id', $id)
            ->orWhere('booking_code', $id)
            ->firstOrFail();

        return view('admin.room_bookings.print_invoice', compact('booking'));
    }
}
