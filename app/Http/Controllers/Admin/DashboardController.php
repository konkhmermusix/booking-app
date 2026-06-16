<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // ១. គណនាចំណូលខែនេះ (សណ្ឋាគារ + សាលប្រជុំ) ដែលមិនទាន់ Cancel
        $hotelRevenueMonth = DB::table('hotel_bookings')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->where('status', '!=', 'cancelled')
            ->sum('total_price');

        $meetingRevenueMonth = DB::table('meeting_bookings')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->where('status', '!=', 'cancelled')
            ->sum('total_price');

        $totalRevenueMonth = $hotelRevenueMonth + $meetingRevenueMonth;

        // ២. គណនាចំណូលខែមុន (ដើម្បីរកភាគរយលូតលាស់ Growth)
        $hotelRevenueLastMonth = DB::table('hotel_bookings')
            ->whereMonth('created_at', $now->copy()->subMonth()->month)
            ->whereYear('created_at', $now->copy()->subMonth()->year)
            ->where('status', '!=', 'cancelled')
            ->sum('total_price');

        $meetingRevenueLastMonth = DB::table('meeting_bookings')
            ->whereMonth('created_at', $now->copy()->subMonth()->month)
            ->whereYear('created_at', $now->copy()->subMonth()->year)
            ->where('status', '!=', 'cancelled')
            ->sum('total_price');

        $lastMonthRevenue = $hotelRevenueLastMonth + $meetingRevenueLastMonth;

        $growth = $lastMonthRevenue > 0
            ? (($totalRevenueMonth - $lastMonthRevenue) / $lastMonthRevenue) * 100
            : 0;

        // ៣. គណនាស្ថានភាពបន្ទប់បច្ចុប្បន្ន (Room Occupancy)
        $totalRooms = Room::count();
        $availableRooms = Room::where('status', 'available')->count();
        $occupancyPercent = $totalRooms > 0 ? (($totalRooms - $availableRooms) / $totalRooms) * 100 : 0;

        // ៤. រាប់ចំនួនការកក់ដែលរង់ចាំពិនិត្យ (Pending Bookings)
        $pendingHotelBookings = DB::table('hotel_bookings')->where('status', 'pending')->count();
        $pendingMeetingBookings = DB::table('meeting_bookings')->where('status', 'pending')->count();
        $totalPendingBookings = $pendingHotelBookings + $pendingMeetingBookings;

        // ៥. ទាញយកប្រវត្តិកក់ចុងក្រោយ ៥ ករណី (Hotel Bookings) មកបង្ហាញក្នុងតារាង
        $recentBookings = HotelBooking::with(['user']) // ភ្ជាប់ជាមួយ User Relationship
            ->latest()
            ->take(5)
            ->get();

        // គណនាចំនួនការកក់សរុបក្នុងប្រព័ន្ធ (សណ្ឋាគារ + សាលប្រជុំ)
        $hotelBookingsCount = DB::table('hotel_bookings')->count();
        $meetingBookingsCount = DB::table('meeting_bookings')->count();
        $bookingsCount = $hotelBookingsCount + $meetingBookingsCount;

        // ៦. រៀបចំ Array សម្រាប់បោះទៅ View
        $stats = [
            'revenue'            => $totalRevenueMonth,
            'revenue_growth'     => round($growth, 1),
            'total_pending'      => $totalPendingBookings,
            'available_rooms'    => $availableRooms,
            'total_rooms'        => $totalRooms,
            'occupancy_percent'  => round($occupancyPercent, 1),
            'recent_bookings'    => $recentBookings,
            'bookings_count'     => $bookingsCount,
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function approve($id)
    {
        // ស្វែងរកការកក់ បើអត់ឃើញបោះទៅ Page 404
        $booking = HotelBooking::findOrFail($id);

        // ធ្វើបច្ចុប្បន្នភាព Status ទៅជា confirmed
        $booking->update(['status' => 'confirmed']);

        // 🌟 បើចង់ឱ្យបន្ទប់ប្រែទៅជា 'occupied' (ជាប់ភ្ញៀវ) ស្វ័យប្រវត្តិ
        DB::table('rooms')->where('id', $booking->room_id)->update(['status' => 'booked']);

        return redirect()->back()->with('success', 'ការកក់នេះត្រូវបានអនុម័តជោគជ័យ!');
    }

    public function reject($id)
    {
        $booking = HotelBooking::findOrFail($id);
        $booking->update(['status' => 'cancelled']);

        // បើបដិសេធ ត្រូវដូរស្ថានភាពបន្ទប់ទៅជាទំនេរ 'available' វិញ
        DB::table('rooms')->where('id', $booking->room_id)->update(['status' => 'available']);

        return redirect()->back()->with('error', 'ការកក់នេះត្រូវបានបដិសេធ!');
    }
}
