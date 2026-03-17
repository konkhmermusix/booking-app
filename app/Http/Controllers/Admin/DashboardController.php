<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Booking;
use App\Models\RoomType;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users_count' => User::count(),
            'hotels_count' => Hotel::count(),
            'bookings_count' => Booking::count(),
            'room_types_count' => RoomType::count(),
            'recent_bookings' => Booking::with(['user', 'hotel'])->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}