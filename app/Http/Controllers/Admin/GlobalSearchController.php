<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelBooking;
use App\Models\MeetingBooking;
use App\Models\Room;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $hotelBookings = collect();
        $meetingBookings = collect();
        $rooms = collect();
        $users = collect();
        $posts = collect();

        if ($search && trim($search) !== '') {
            $search = trim($search);

            // 1. Hotel Bookings
            $hotelBookings = HotelBooking::with(['user', 'hotel', 'room.roomType'])
                ->where(function ($query) use ($search) {
                    $query->where('booking_code', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%")
                              ->orWhere('phone', 'like', "%{$search}%");
                        })
                        ->orWhereHas('room', function ($q) use ($search) {
                            $q->where('room_number', 'like', "%{$search}%");
                        });
                })
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // 2. Meeting Bookings
            $meetingBookings = MeetingBooking::with(['user', 'room.roomType'])
                ->where(function ($query) use ($search) {
                    $query->where('booking_code', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%")
                              ->orWhere('phone', 'like', "%{$search}%");
                        })
                        ->orWhereHas('room', function ($q) use ($search) {
                            $q->where('room_number', 'like', "%{$search}%");
                        });
                })
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // 3. Rooms
            $rooms = Room::with(['hotel', 'roomType'])
                ->where(function ($query) use ($search) {
                    $query->where('room_number', 'like', "%{$search}%")
                        ->orWhereHas('hotel', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('roomType', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                })
                ->limit(10)
                ->get();

            // 4. Users
            $users = User::where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get();

            // 5. Posts
            $posts = Post::where('title', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%")
                ->limit(10)
                ->get();
        }

        return view('admin.search.results', compact(
            'search',
            'hotelBookings',
            'meetingBookings',
            'rooms',
            'users',
            'posts'
        ));
    }
}
