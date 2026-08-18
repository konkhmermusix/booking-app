<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\HotelBooking;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        $newBookingsCount = DB::table('hotel_bookings')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        $lastMonthBookings = DB::table('hotel_bookings')->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $bookingsGrowth = $lastMonthBookings > 0 ? (($newBookingsCount - $lastMonthBookings) / $lastMonthBookings) * 100 : 0;

        $pendingBookingsCount = DB::table('hotel_bookings')->where('status', 'pending')->count()
            + DB::table('meeting_bookings')->where('status', 'pending')->count();

        $checkInCount = DB::table('hotel_bookings')->where('status', 'confirmed')->count();
        $checkInGrowth = 3.56;

        $checkOutCount = DB::table('hotel_bookings')->where('status', 'completed')->count();
        $checkOutLoss = 1.06;

        $totalRevenue = DB::table('payments')->where('status', 'paid')->sum('amount');
        $thisMonthRevenue = DB::table('payments')->where('status', 'paid')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('amount');
        $lastMonthRevenue = DB::table('payments')->where('status', 'paid')->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->sum('amount');
        $revenueGrowth = $lastMonthRevenue > 0 ? (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;

        $totalRooms = DB::table('rooms')->count() ?: 1;
        $roomStatusCounts = [
            'booked'      => DB::table('rooms')->where('status', 'booked')->count(),
            'available'   => DB::table('rooms')->where('status', 'available')->count(),
            'maintenance' => DB::table('rooms')->where('status', 'maintenance')->count(),
        ];
        $roomPercentages = [
            'booked'      => ($roomStatusCounts['booked'] / $totalRooms) * 100,
            'available'   => ($roomStatusCounts['available'] / $totalRooms) * 100,
            'maintenance' => ($roomStatusCounts['maintenance'] / $totalRooms) * 100,
        ];

        $sixMonthsTotalRevenue = 0;
        $timelineLabels = [];
        $revenueData6Months = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $timelineLabels[] = $monthDate->format('M Y');

            $monthRev = DB::table('payments')
                ->where('status', 'paid')
                ->whereMonth('created_at', $monthDate->month)
                ->whereYear('created_at', $monthDate->year)
                ->sum('amount');

            $revenueData6Months[] = $monthRev;
            $sixMonthsTotalRevenue += $monthRev;
        }

        $chartData = ['labels' => [], 'booked' => [], 'canceled' => []];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartData['labels'][] = $date->format('d M');

            $chartData['booked'][] = DB::table('hotel_bookings')
                ->whereDate('created_at', $date->toDateString())
                ->whereIn('status', ['confirmed', 'completed'])
                ->count();

            $chartData['canceled'][] = DB::table('hotel_bookings')
                ->whereDate('created_at', $date->toDateString())
                ->where('status', 'cancelled')
                ->count();
        }


        $methodLabels = [
            'cash'     => 'សាច់ប្រាក់',
            'qr'       => 'ឃ្យូអរកូដ',
        ];

        $totalPayments = DB::table('payments')->count();
        $platformData = [];

        if ($totalPayments > 0) {
            $paymentsByMethod = DB::table('payments')
                ->select('method', DB::raw('count(*) as total'))
                ->groupBy('method')
                ->get();

            foreach ($paymentsByMethod as $p) {
                $rawMethod = strtolower(trim($p->method ?? 'cash'));
                $name = $methodLabels[$rawMethod] ?? ucfirst($rawMethod);
                $platformData[$name] = round(($p->total / $totalPayments) * 100, 2);
            }
        }

        if (empty($platformData)) {
            $platformData = ['សាច់ប្រាក់' => 100];
        }


        $roomBookings = HotelBooking::with(['user', 'details.room.roomType', 'details.roomType', 'room.roomType'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($b) {
                $detailsCount = ($b->details && $b->details->count() > 0) ? $b->details->count() : 1;
                if ($b->details && $b->details->count() > 0) {
                    $roomNumbers = $b->details->map(fn($d) => $d->room->room_number ?? null)->filter()->values()->all();
                    $roomTypes = $b->details->map(fn($d) => $d->roomType->name ?? ($d->room->roomType->name ?? null))->filter()->unique()->values()->all();

                    $roomNumStr = count($roomNumbers) > 0 ? implode(', ', $roomNumbers) : ($b->room->room_number ?? 'N/A');
                    $roomTypeStr = count($roomTypes) > 0 ? implode(', ', $roomTypes) : ($b->room->roomType->name ?? 'បន្ទប់ស្នាក់នៅ');
                } else {
                    $roomNumStr = $b->room->room_number ?? 'N/A';
                    $roomTypeStr = $b->room->roomType->name ?? 'បន្ទប់ស្នាក់នៅ';
                }

                $checkIn = $b->check_in ? Carbon::parse($b->check_in)->format('d/m/Y') : 'N/A';
                $checkOut = $b->check_out ? Carbon::parse($b->check_out)->format('d/m/Y') : 'N/A';

                return (object)[
                    'id'            => $b->id,
                    'category'      => 'room',
                    'booking_code'  => $b->booking_code,
                    'guest_name'    => $b->customer_name ?: ($b->user->name ?? 'អតិថិជនអនឡាញ'),
                    'room_number'   => $roomNumStr,
                    'room_type'     => $roomTypeStr,
                    'details_count' => $detailsCount,
                    'check_in'      => $b->check_in,
                    'check_out'     => $b->check_out,
                    'date_display'  => "{$checkIn} - {$checkOut}",
                    'total_price'   => $b->total_price,
                    'status'        => $b->status,
                    'created_at'    => $b->created_at,
                    'url'           => route('room-bookings.index'),
                ];
            });

        $meetingBookings = \App\Models\MeetingBooking::with(['user', 'room.roomType'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($mb) {
                $roomNumStr = $mb->room->room_number ?? 'N/A';
                $roomTypeStr = $mb->room->roomType->name ?? 'សាលប្រជុំ';
                $startDate = $mb->start_date ? Carbon::parse($mb->start_date)->format('d/m/Y') : 'N/A';
                $endDate = $mb->end_date ? Carbon::parse($mb->end_date)->format('d/m/Y') : 'N/A';

                return (object)[
                    'id'            => $mb->id,
                    'category'      => 'meeting',
                    'booking_code'  => $mb->booking_code,
                    'guest_name'    => $mb->customer_name ?: ($mb->user->name ?? 'ភ្ញៀវកក់ផ្ទាល់'),
                    'room_number'   => $roomNumStr,
                    'room_type'     => $roomTypeStr,
                    'details_count' => 1,
                    'check_in'      => $mb->start_date,
                    'check_out'     => $mb->end_date,
                    'date_display'  => "{$startDate} - {$endDate} ({$mb->start_time} - {$mb->end_time})",
                    'total_price'   => $mb->total_price,
                    'status'        => $mb->status,
                    'created_at'    => $mb->created_at,
                    'url'           => route('meeting-bookings.index'),
                ];
            });

        $recentBookings = $roomBookings->concat($meetingBookings)
            ->sortByDesc('created_at')
            ->take(8)
            ->values();


        $rawAvg = DB::table('reviews')->where('status', 1)->avg('rating');
        $realAvg = $rawAvg ? round($rawAvg, 1) : 4.6;

        $ratingData = [
            'avg_rating'  => $realAvg,
            'facilities'  => round(min(5.0, $realAvg * 0.96), 1),
            'cleanliness' => round(min(5.0, $realAvg * 1.02), 1),
            'services'    => round(min(5.0, $realAvg * 1.00), 1),
        ];

        $tasks = DB::table('meeting_bookings')
            ->select('booking_code', 'start_date as execution_date', 'status')
            ->limit(3)
            ->get();

        $meetingRooms = DB::table('rooms')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('room_types.category', 'meeting')
            ->select('rooms.id', DB::raw("CONCAT('សាល ', rooms.room_number) as room_name"), 'room_types.name as location')
            ->get();

        $activities = DB::table('payments')
            ->leftJoin('hotel_bookings', 'payments.hotel_booking_id', '=', 'hotel_bookings.id')
            ->select(
                DB::raw("COALESCE(hotel_bookings.booking_code, 'PAYMENT') as booking_code"),
                'payments.amount',
                'payments.method as type',
                'payments.created_at'
            )
            ->orderBy('payments.created_at', 'desc')
            ->limit(4)
            ->get()
            ->map(function ($act) use ($methodLabels) {
                $rawType = strtolower(trim($act->type ?? ''));
                $act->type = $methodLabels[$rawType] ?? 'សាច់ប្រាក់';
                return $act;
            });

        $roomTypesCount = DB::table('room_types')
            ->where(function($q) {
                $q->whereNull('category')
                  ->orWhere('category', 'room')
                  ->orWhere('category', '!=', 'meeting');
            })->count();

        $meetingRoomTypesCount = DB::table('room_types')
            ->where(function($q) {
                $q->where('category', 'meeting')
                  ->orWhere('name', 'like', '%សាលប្រជុំ%')
                  ->orWhere('name', 'like', '%Meeting%')
                  ->orWhere('name', 'like', '%Conference%')
                  ->orWhere('name', 'like', '%Hall%');
            })->count();

        $allAvailableRooms = \App\Models\Room::with('roomType')
            ->where('status', 'available')
            ->get();

        $allAvailableMeetingRooms = \App\Models\Room::with('roomType')
            ->where('status', 'available')
            ->whereHas('roomType', function($query) {
                $query->where('category', 'meeting')
                    ->orWhere('name', 'like', '%សាលប្រជុំ%')
                    ->orWhere('name', 'like', '%Meeting%')
                    ->orWhere('name', 'like', '%Conference%')
                    ->orWhere('name', 'like', '%Hall%');
            })->get();

        return view('admin.dashboard', compact(
            'newBookingsCount',
            'bookingsGrowth',
            'checkInCount',
            'checkInGrowth',
            'checkOutCount',
            'checkOutLoss',
            'totalRevenue',
            'revenueGrowth',
            'roomStatusCounts',
            'roomPercentages',
            'sixMonthsTotalRevenue',
            'timelineLabels',
            'revenueData6Months',
            'chartData',
            'platformData',
            'recentBookings',
            'ratingData',
            'tasks',
            'meetingRooms',
            'activities',
            'roomTypesCount',
            'meetingRoomTypesCount',
            'allAvailableRooms',
            'allAvailableMeetingRooms',
            'pendingBookingsCount'
        ));
    }

    public function approve($id)
    {
        $booking = HotelBooking::with('payment')->findOrFail($id);

        $booking->update(['status' => 'confirmed']);

        DB::table('rooms')->where('id', $booking->room_id)->update(['status' => 'booked']);

        if ($booking->payment) {
            $booking->payment->update([
                'status'  => 'paid',
                'paid_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'ការកក់នេះត្រូវបានអនុម័តជោគជ័យ');
    }

    public function reject($id)
    {
        $booking = HotelBooking::with('payment')->findOrFail($id);
        $booking->update(['status' => 'cancelled']);

        DB::table('rooms')->where('id', $booking->room_id)->update(['status' => 'available']);

        if ($booking->payment) {
            $booking->payment->update([
                'status' => 'failed',
            ]);
        }

        return redirect()->back()->with('error', 'ការកក់នេះត្រូវបានបដិសេធ');
    }
}
