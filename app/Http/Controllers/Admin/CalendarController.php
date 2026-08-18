<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\HotelBooking;
use App\Models\MeetingBooking;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year  = $request->input('year', now()->year);

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate   = $startDate->copy()->endOfMonth();
        $todayStr  = now()->toDateString();

        \DB::statement("
            INSERT INTO hotel_booking_details (hotel_booking_id, room_id, room_type_id, price_at_booking, created_at, updated_at)
            SELECT hb.id, hb.room_id, r.room_type_id, hb.total_price, hb.created_at, hb.updated_at
            FROM hotel_bookings hb
            JOIN rooms r ON hb.room_id = r.id
            LEFT JOIN hotel_booking_details hbd ON hb.id = hbd.hotel_booking_id AND hb.room_id = hbd.room_id
            WHERE hb.room_id IS NOT NULL AND hbd.id IS NULL
        ");

        $stayRooms = Room::with([
            'roomType',
            'hotelBookings' => function ($query) use ($startDate, $endDate) {
                $query->where('check_in', '<=', $endDate->toDateString())
                      ->where('check_out', '>=', $startDate->toDateString())
                      ->with(['details.room', 'payment']);
            },
        ])->whereHas('roomType', fn($q) => $q->where('category', 'stay'))->get();

        $meetingRooms = Room::with([
            'roomType',
            'meetingBookings' => function ($query) use ($startDate, $endDate) {
                $query->where('start_date', '<=', $endDate->toDateString())
                      ->where('end_date', '>=', $startDate->toDateString())
                      ->with('payment');
            },
        ])->whereHas('roomType', fn($q) => $q->where('category', 'meeting'))->get();

        $daysInMonth = $startDate->daysInMonth;

        $totalStayRooms = $stayRooms->count();
        $occupiedTodayCount = 0;

        foreach ($stayRooms as $room) {
            $hasTodayBooking = $room->hotelBookings->contains(function ($b) use ($todayStr) {
                return $todayStr >= Carbon::parse($b->check_in)->format('Y-m-d') 
                    && $todayStr < Carbon::parse($b->check_out)->format('Y-m-d')
                    && in_array($b->status, ['confirmed', 'checked_in', 'pending']);
            });
            if ($hasTodayBooking) {
                $occupiedTodayCount++;
            }
        }

        $availableTodayCount = max(0, $totalStayRooms - $occupiedTodayCount);
        $pendingBookingsCount = HotelBooking::where('status', 'pending')->count() + MeetingBooking::where('status', 'pending')->count();

        $stats = [
            'total_rooms' => $totalStayRooms,
            'occupied_today' => $occupiedTodayCount,
            'available_today' => $availableTodayCount,
            'pending_count' => $pendingBookingsCount,
        ];

        // AJAX Request — Render table partial only
        if ($request->ajax()) {
            return view('admin.calendar.partials.calendar_table', compact(
                'stayRooms',
                'meetingRooms',
                'daysInMonth',
                'month',
                'year',
                'stats'
            ))->render();
        }

        return view('admin.calendar.index', compact(
            'stayRooms',
            'meetingRooms',
            'daysInMonth',
            'month',
            'year',
            'stats'
        ));
    }

    // 1-Click Status Update directly from Calendar Modal
    public function updateStatus(Request $request, $id)
    {
        try {
            $type = $request->input('type', 'stay');
            $inputStatus = $request->input('status');

            $dbStatusMap = [
                'pending'     => 'pending',
                'confirmed'   => 'confirmed',
                'checked_in'  => 'confirmed',
                'checked_out' => 'completed',
                'completed'   => 'completed',
                'cancelled'   => 'cancelled',
            ];

            if (!isset($dbStatusMap[$inputStatus])) {
                return response()->json(['success' => false, 'message' => 'ស្ថានភាពមិនត្រឹមត្រូវ!'], 422);
            }

            $status = $dbStatusMap[$inputStatus];

            if ($type === 'meeting') {
                $booking = MeetingBooking::with('payment')->findOrFail($id);
                $booking->update(['status' => $status]);

                if ($booking->meeting_room_id) {
                    if (in_array($status, ['completed', 'cancelled'])) {
                        Room::where('id', $booking->meeting_room_id)->update(['status' => 'available']);
                    } elseif ($status === 'confirmed') {
                        Room::where('id', $booking->meeting_room_id)->update(['status' => 'booked']);
                    }
                }

                // Sync Payment table for Meeting Booking
                $rawMethod = strtolower($booking->payment_method ?? 'cash');
                $method = match (true) {
                    str_contains($rawMethod, 'qr') || str_contains($rawMethod, 'khqr') => 'qr',
                    str_contains($rawMethod, 'transfer') || str_contains($rawMethod, 'bank') => 'transfer',
                    str_contains($rawMethod, 'card') || str_contains($rawMethod, 'visa') => 'card',
                    default => 'cash',
                };
                $paymentStatus = in_array($status, ['confirmed', 'completed']) ? 'paid' : ($status === 'cancelled' ? 'failed' : 'pending');

                \App\Models\Payment::updateOrCreate(
                    ['meeting_booking_id' => $booking->id],
                    [
                        'amount'   => $booking->total_price ?? 0,
                        'method'   => $method,
                        'currency' => 'USD',
                        'status'   => $paymentStatus,
                        'paid_at'  => $paymentStatus === 'paid' ? now() : null,
                    ]
                );
            } else {
                $booking = HotelBooking::with('payment')->findOrFail($id);
                $booking->update(['status' => $status]);

                // Sync room availability status
                if (in_array($status, ['completed', 'cancelled'])) {
                    if ($booking->room_id) {
                        Room::where('id', $booking->room_id)->update(['status' => 'available']);
                    }
                } elseif ($status === 'confirmed') {
                    if ($booking->room_id) {
                        Room::where('id', $booking->room_id)->update(['status' => 'booked']);
                    }
                }

                // Sync Payment table for Hotel Booking
                $rawMethod = strtolower($booking->payment_method ?? 'cash');
                $method = match (true) {
                    str_contains($rawMethod, 'qr') || str_contains($rawMethod, 'khqr') => 'qr',
                    str_contains($rawMethod, 'transfer') || str_contains($rawMethod, 'bank') => 'transfer',
                    str_contains($rawMethod, 'card') || str_contains($rawMethod, 'visa') => 'card',
                    default => 'cash',
                };
                $paymentStatus = in_array($status, ['confirmed', 'completed']) ? 'paid' : ($status === 'cancelled' ? 'failed' : 'pending');

                \App\Models\Payment::updateOrCreate(
                    ['hotel_booking_id' => $booking->id],
                    [
                        'amount'   => $booking->total_price ?? 0,
                        'method'   => $method,
                        'currency' => 'USD',
                        'status'   => $paymentStatus,
                        'paid_at'  => $paymentStatus === 'paid' ? now() : null,
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'បានធ្វើបច្ចុប្បន្នភាពស្ថានភាពការកក់ និងទិន្នន័យបង់ប្រាក់ជោគជ័យ!',
                'booking' => $booking
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'មានបញ្ហាក្នុងការធ្វើបច្ចុប្បន្នភាព: ' . $e->getMessage()
            ], 500);
        }
    }

    // API សម្រាប់ FullCalendar JS
    public function getEvents(Request $request)
    {
        $start = $request->input('start', now()->startOfMonth()->toDateString());
        $end   = $request->input('end', now()->endOfMonth()->toDateString());

        $bookings = HotelBooking::with(['details.room', 'room'])
            ->where('check_in', '<=', $end)
            ->where('check_out', '>=', $start)
            ->get();

        $events = collect();
        $colors = [
            'available'   => '#10b981',
            'booked'      => '#ef4444',
            'checked_in'  => '#3b82f6',
            'maintenance' => '#f59e0b',
            'cleaning'    => '#6b7280',
        ];

        foreach ($bookings as $booking) {
            $rooms = $booking->details->pluck('room')->filter();
            if ($rooms->isEmpty() && $booking->room) {
                $rooms = collect([$booking->room]);
            }

            foreach ($rooms as $roomItem) {
                $events->push([
                    'id'    => $booking->id . '_' . $roomItem->id,
                    'title' => 'Room ' . ($roomItem->room_number ?? '-') . ' - ' . ($booking->customer_name ?? 'Guest'),
                    'start' => $booking->check_in,
                    'end'   => $booking->check_out,
                    'backgroundColor' => $colors[$booking->status] ?? '#6366f1',
                    'borderColor'     => $colors[$booking->status] ?? '#6366f1',
                    'textColor'       => '#ffffff',
                    'extendedProps' => [
                        'guest_name' => $booking->customer_name,
                        'phone'      => $booking->customer_phone,
                        'status'     => $booking->status,
                        'room'       => $roomItem->room_number ?? null,
                        'total'      => $booking->total_price ?? 0,
                    ],
                ]);
            }
        }

        return response()->json($events);
    }
}
