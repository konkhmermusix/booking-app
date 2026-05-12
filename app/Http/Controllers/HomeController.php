<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\RoomType;
use Carbon\Carbon;
use App\Models\Slideshow;
use App\Models\Promotion;
use App\Models\Facility;
use App\Models\Tour;
use App\Models\RoomImage;
use App\Models\Gallery;
use App\Models\Hotel;

class HomeController extends Controller
{

    public function index(Request $request)
    {
        // =========================
        // 1. INPUT (DEFAULT DATES)
        // =========================
        $check_in = $request->input('check_in', Carbon::today()->format('Y-m-d'));
        $check_out = $request->input('check_out', Carbon::tomorrow()->format('Y-m-d'));
        $type_id = $request->input('room_type_id');

        // =========================
        // 2. ROOM QUERY (AVAILABLE ONLY)
        // =========================
        $roomsQuery = Room::with(['roomType', 'hotel']);

        if ($type_id) {
            $roomsQuery->where('room_type_id', $type_id);
        }

        // overlap booking check
        if ($check_in && $check_out) {
            $roomsQuery->whereDoesntHave('bookings', function ($q) use ($check_in, $check_out) {
                $q->whereIn('status', ['confirmed', 'pending'])
                    ->where(function ($b) use ($check_in, $check_out) {

                        $b->whereBetween('check_in', [$check_in, $check_out])
                            ->orWhereBetween('check_out', [$check_in, $check_out])
                            ->orWhere(function ($overlap) use ($check_in, $check_out) {
                                $overlap->where('check_in', '<=', $check_in)
                                    ->where('check_out', '>=', $check_out);
                            });
                    });
            });
        }

        $rooms = $roomsQuery->get();

        // =========================
        // 3. SLIDES
        // =========================
        $slides = Slideshow::where('is_active', true)
            ->orderBy('order_column', 'asc')
            ->get();

        // =========================
        // 4. AVAILABLE ROOMS (HIGHLIGHT)
        // =========================
        $availableRooms = Room::with(['roomType.images', 'hotel'])
            ->where('status', 'available')
            ->latest()
            ->take(6)
            ->get();

        // =========================
        // 5. ROOM TYPES
        // =========================
        $roomTypes = RoomType::with(['images', 'facilities', 'rooms'])
            ->withCount([
                'rooms as available_rooms_count' => function ($q) {
                    $q->where('status', 'available');
                }
            ])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('name', 'not like', '%សាលប្រជុំ%')
            ->whereHas('rooms', function ($q) {
                $q->where('status', 'available');
            })
            ->get();

        // =========================
        // 6. PROMOTIONS
        // =========================
        $promotions = Promotion::with(['roomType.rooms' => function ($q) {
            $q->where('status', 'available');
        }])
            ->where('status', 1)
            ->where('expiry_date', '>=', now())
            ->latest()
            ->get();

        // =========================
        // 7. FACILITIES + TOURS
        // =========================
        $facilities = Facility::where('is_active', 1)->get();
        $tours = Tour::where('status', 1)->latest()->get();

        // =========================
        // 8. ROOM TYPE IMAGE (SAFE FALLBACK)
        // =========================
        $roomTypeImage = RoomType::with('images')
            ->has('images', '>=', 4)
            ->first();

        if (!$roomTypeImage) {
            $roomTypeImage = RoomType::with('images')->first();
        }

        // =========================
        // 9. GALLERY / IMAGES
        // =========================
        $displayImages = RoomImage::with('roomType')
            ->latest()
            ->take(8)
            ->get();

        $galleries = Gallery::with('hotel')
            ->where('is_active', 1)
            ->orderBy('sort_order', 'asc')
            ->take(9)
            ->get();

        // =========================
        // 10. RETURN VIEW
        // =========================
        return view('frontend.index', compact(
            'rooms',
            'slides',
            'roomTypes',
            'availableRooms',
            'check_in',
            'check_out',
            'promotions',
            'facilities',
            'tours',
            'roomTypeImage',
            'displayImages',
            'galleries'
        ));
    }


    // សម្រាប់បន្ទប់ស្នាក់នៅ (Stay Room)
    public function room_detail($id)
    {
        $roomType = RoomType::with(['images', 'facilities:id,name,icon', 'rooms', 'hotel'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->findOrFail($id);

        $similarRooms = RoomType::where('hotel_id', $roomType->hotel_id)
            ->where('id', '!=', $roomType->id)
            ->where('name', 'not like', '%សាល%') // ច្រោះយកតែបន្ទប់ស្នាក់នៅ
            ->take(3)
            ->get();

        return view('frontend.room_details', compact('roomType', 'similarRooms'));
    }

    // សម្រាប់សាលប្រជុំ (Meeting Hall)
    public function meeting_detail($id)
    {
        $roomType = RoomType::with(['images', 'facilities:id,name,icon', 'rooms', 'hotel'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->findOrFail($id);

        $similarRooms = RoomType::where('hotel_id', $roomType->hotel_id)
            ->where('id', '!=', $roomType->id)
            ->where(function ($q) {
                $q->where('name', 'like', '%សាល%')
                    ->orWhere('name', 'like', '%Meeting%')
                    ->orWhere('name', 'like', '%Hall%');
            })
            ->take(3)
            ->get();

        return view('frontend.meeting_details', compact('roomType', 'similarRooms'));
    }

    public function show($id)
    {
        $hotel = Hotel::find($id);

        // ទាញយករូបភាពពី RoomImage ដែលជារបស់ Hotel នេះមកធ្វើជា Slide
        $slides = RoomImage::whereHas('roomType', function ($query) use ($id) {
            $query->where('hotel_id', $id);
        })->get();

        return view('frontend.index', compact('hotel', 'slides'));
    }
}
