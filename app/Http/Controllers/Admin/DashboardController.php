<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Models\Room; // Assuming you have a Room model
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Revenue for current month
        $totalRevenueMonth = HotelBooking::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->where('status', 'confirmed') // Only count paid/confirmed bookings
            ->sum('total_price');

        // 2. Growth Percentage (Revenue vs Last Month)
        $lastMonthRevenue = HotelBooking::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->where('status', 'confirmed')
            ->sum('total_price');

        $growth = $lastMonthRevenue > 0
            ? (($totalRevenueMonth - $lastMonthRevenue) / $lastMonthRevenue) * 100
            : 0;

        // 3. Room Availability (Total rooms vs Available ones)
        // Assuming you have a status column on your Room model
        $averageRating = 0;
        $totalReviews = 0;
        $totalRooms = Room::count();
        // $availableRooms = Room::where('status', 'available')->count();
        // $occupancyPercent = $totalRooms > 0 ? (($totalRooms - $availableRooms) / $totalRooms) * 100 : 0;

        // 4. Average Rating
        // Assuming you have a Review model or a rating column in Bookings/Hotels
        // $averageRating = HotelBooking::whereNotNull('rating')->avg('rating') ?? 0;
        // $totalReviews = HotelBooking::whereNotNull('rating')->count();

        $stats = [
            'revenue' => $totalRevenueMonth,
            'revenue_growth' => round($growth, 1),
            'bookings_count' => HotelBooking::count(),
            // 'available_rooms' => $availableRooms,
            'total_rooms' => $totalRooms,
            // 'occupancy_percent' => $occupancyPercent,
            'average_rating' => round($averageRating, 1),
            'total_reviews' => $totalReviews,
            'recent_bookings' => HotelBooking::with(['user', 'hotel'])->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
