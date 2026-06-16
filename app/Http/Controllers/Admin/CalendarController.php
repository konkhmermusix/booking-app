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

        // ទាញយកទិន្នន័យបន្ទប់ និងការកក់ដោយប្រើ Eager Loading ការពារ N+1 Problem
        $allRooms = Room::with([
            'roomType',
            'hotelBookings' => function ($query) use ($startDate, $endDate) {
                $query->where('check_in', '<=', $endDate->toDateString())
                    ->where('check_out', '>=', $startDate->toDateString());
            },
            'meetingBookings' => function ($query) use ($startDate, $endDate) {
                $query->where('start_date', '<=', $endDate->toDateString())
                    ->where('end_date', '>=', $startDate->toDateString());
            }
        ])->get();

        // បំបែកក្រុមបន្ទប់
        $stayRooms    = $allRooms->where('roomType.category', 'stay');
        $meetingRooms = $allRooms->where('roomType.category', 'meeting');
        $daysInMonth  = $startDate->daysInMonth;

        // 🌟 ពិនិត្យលក្ខខណ្ឌបើជា AJAX Request គឺ Render តែផ្ទាំងតារាងប្រតិទិនប៉ុណ្ណោះ
        if ($request->ajax()) {
            return view('admin.calendar.partials.calendar_table', compact(
                'stayRooms',
                'meetingRooms',
                'daysInMonth',
                'month',
                'year'
            ))->render();
        }

        // សម្រាប់ Normal Request ចូលមកកាន់ Page លើកដំបូង
        return view('admin.calendar.index', compact(
            'stayRooms',
            'meetingRooms',
            'daysInMonth',
            'month',
            'year'
        ));
    }

    /**
     * API សម្រាប់ FullCalendar JS (ទុកប្រើប្រាស់បន្ថែមបើត្រូវការ)
     */
    public function getEvents(Request $request)
    {
        $start = $request->input('start', now()->startOfMonth()->toDateString());
        $end   = $request->input('end', now()->endOfMonth()->toDateString());

        $bookings = HotelBooking::with('room')
            ->where('check_in', '<=', $end)
            ->where('check_out', '>=', $start)
            ->get();

        $events = $bookings->map(function ($booking) {
            $colors = [
                'available'   => '#10b981',
                'booked'      => '#ef4444',
                'checked_in'  => '#3b82f6',
                'maintenance' => '#f59e0b',
                'cleaning'    => '#6b7280',
            ];

            return [
                'id'    => $booking->id,
                'title' => 'Room ' . ($booking->room->room_number ?? '-') . ' - ' . ($booking->guest_name ?? 'Guest'),
                'start' => $booking->check_in,
                'end'   => $booking->check_out,
                'backgroundColor' => $colors[$booking->status] ?? '#6366f1',
                'borderColor'     => $colors[$booking->status] ?? '#6366f1',
                'textColor'       => '#ffffff',
                'extendedProps' => [
                    'guest_name' => $booking->guest_name,
                    'phone'      => $booking->phone,
                    'status'     => $booking->status,
                    'room'       => $booking->room->room_number ?? null,
                    'total'      => $booking->total_price ?? 0,
                ],
            ];
        });

        return response()->json($events);
    }
}
