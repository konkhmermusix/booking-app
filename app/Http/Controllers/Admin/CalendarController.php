<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Booking;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        $currentMonth = now()->month;
        $currentYear  = now()->year;

        // Load Rooms + Bookings
        $rooms = Room::with(['bookings' => function ($query) use ($currentMonth, $currentYear) {

            $query->where(function ($q) use ($currentMonth, $currentYear) {

                $q->whereMonth('check_in', $currentMonth)
                    ->whereYear('check_in', $currentYear)

                    ->orWhere(function ($qq) use ($currentMonth, $currentYear) {

                        $qq->whereMonth('check_out', $currentMonth)
                            ->whereYear('check_out', $currentYear);
                    });
            });
        }])->get();

        // Generate booked days array
        $bookedDays = [];

        foreach ($rooms as $room) {

            foreach ($room->bookings as $booking) {

                $start = Carbon::parse($booking->check_in);
                $end   = Carbon::parse($booking->check_out);

                while ($start->lt($end)) {

                    if (
                        $start->month == $currentMonth &&
                        $start->year == $currentYear
                    ) {

                        $bookedDays[$room->id][$start->day] = [
                            'booking_id' => $booking->id,
                            'guest_name' => $booking->guest_name,
                            'status'     => $booking->status ?? 'booked',
                        ];
                    }

                    $start->addDay();
                }
            }
        }

        $daysInMonth = now()->daysInMonth;

        return view('admin.calendar.index', compact(
            'rooms',
            'bookedDays',
            'daysInMonth'
        ));
    }

    /**
     * FullCalendar Events API
     */
    public function getEvents()
    {
        $bookings = Booking::with('room')->get();

        $events = $bookings->map(function ($booking) {

            // Status Colors
            $colors = [
                'available'   => '#10b981',
                'booked'      => '#ef4444',
                'checked_in'  => '#3b82f6',
                'maintenance' => '#f59e0b',
            ];

            return [
                'id'    => $booking->id,

                'title' =>
                'Room ' .
                    ($booking->room->room_number ?? '-') .
                    ' - ' .
                    ($booking->guest_name ?? 'Guest'),

                'start' => $booking->check_in,

                'end'   => $booking->check_out,

                'backgroundColor' =>
                $colors[$booking->status] ?? '#6366f1',

                'borderColor' =>
                $colors[$booking->status] ?? '#6366f1',

                'textColor' => '#ffffff',

                // Extra Data
                'extendedProps' => [
                    'guest_name' => $booking->guest_name,
                    'phone'      => $booking->phone,
                    'status'     => $booking->status,
                    'room'       => $booking->room->room_number ?? null,
                    'total'      => $booking->total_amount ?? 0,
                ],
            ];
        });

        return response()->json($events);
    }
}
