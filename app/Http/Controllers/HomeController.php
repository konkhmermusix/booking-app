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
use App\Models\Review;

class HomeController extends Controller
{

    public function index(Request $request)
    {
        // 1. INPUT (DEFAULT DATES)
        $check_in = $request->input('check_in', Carbon::today()->format('Y-m-d'));
        $check_out = $request->input('check_out', Carbon::tomorrow()->format('Y-m-d'));
        $type_id = $request->input('room_type_id');

        // 2. ROOM QUERY (AVAILABLE ONLY)
        $roomsQuery = Room::with(['roomType', 'hotel']);

        if ($type_id) {
            $roomsQuery->where('room_type_id', $type_id);
        }

        // overlap booking check
        if ($check_in && $check_out) {
            $roomsQuery->whereDoesntHave('hotelbookings', function ($q) use ($check_in, $check_out) {
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

        // 3. SLIDES
        $slides = Slideshow::where('is_active', true)
            ->orderBy('order_column', 'asc')
            ->get();

        // 4. AVAILABLE ROOMS (HIGHLIGHT)
        $availableRooms = Room::with(['roomType.images', 'hotel'])
            ->where('status', 'available')
            ->latest()
            ->take(6)
            ->get();

        // 5. ROOM TYPES
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

        // 6. MEETING ROOM TYPES
        $roomMeeting = RoomType::with(['images', 'facilities', 'rooms'])
            ->withCount([
                'rooms as available_rooms_count' => function ($q) {
                    $q->where('status', 'available');
                }
            ])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('name', 'like', '%សាលប្រជុំ%')
            ->whereHas('rooms', function ($q) {
                $q->where('status', 'available');
            })
            ->get();

        // 7. PROMOTIONS
        $promotions = Promotion::with(['roomType.rooms' => function ($q) {
            $q->where('status', 'available');
        }])
            ->where('status', 1)
            ->where('expiry_date', '>=', now())
            ->latest()
            ->get();

        // 8. FACILITIES + TOURS
        $facilities = Facility::where('is_active', 1)->get();
        $tours = Tour::where('status', 1)->latest()->get();

        // 9. GALLERY / IMAGES
        $galleries = Gallery::with('hotel')
            ->where('is_active', 1)
            ->orderBy('sort_order', 'asc')
            ->take(9)
            ->get();

        $reviews = Review::with('roomType')
            ->where('status', 1)
            ->whereNull('parent_id')
            ->latest()
            ->take(8)
            ->get();

        return view('frontend.index', compact(
            'rooms',
            'slides',
            'availableRooms',
            'check_in',
            'check_out',
            'roomTypes',
            'roomMeeting',
            'facilities',
            'tours',
            'galleries',
            'promotions',
            'reviews',
        ));
    }

    public function promotion_detail($id)
    {
        $promotion = Promotion::with(['roomType.images', 'roomType.facilities'])->findOrFail($id);

        $roomType = $promotion->roomType;

        if (!$roomType) {
            return redirect()->back()->with('error', 'មិនមានទិន្នន័យប្រភេទបន្ទប់សម្រាប់ប្រូម៉ូសិននេះទេ។');
        }

        return view('frontend.promotion_details', compact('promotion', 'roomType'));
    }


    public function show($id)
    {
        $hotel = Hotel::find($id);

        $slides = RoomImage::whereHas('roomType', function ($query) use ($id) {
            $query->where('hotel_id', $id);
        })->get();

        return view('frontend.index', compact('hotel', 'slides'));
    }


    public function toursdetail($id)
    {
        $tour = Tour::where('status', 1)
            ->findOrFail($id);
        $otherTours = Tour::where('id', '!=', $id)
            ->where('status', 1)
            ->limit(10)
            ->get();

        return view('frontend.toursdetail', compact('tour', 'otherTours'));
    }
}
