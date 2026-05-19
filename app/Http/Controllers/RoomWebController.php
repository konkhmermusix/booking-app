<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Room;
use App\Models\HotelBooking;
use App\Models\Review;

class RoomWebController extends Controller
{
    public function index(Request $request)
    {
        // 1. INPUT
        $check_in  = $request->input('check_in', Carbon::today()->format('Y-m-d'));
        $check_out = $request->input('check_out', Carbon::tomorrow()->format('Y-m-d'));
        $type_name = $request->input('type');
        $sort      = $request->input('sort', 'asc');
        $search    = $request->input('search');
        $guests    = $request->input('guests');

        // 2. CATEGORIES (EXCLUDE MEETING ROOM)
        $categories = RoomType::where('name', 'not like', '%សាលប្រជុំ%')
            ->select('id', 'name')
            ->distinct()
            ->get();

        // 3. AVAILABILITY LOGIC (REUSABLE)
        $availabilityFilter = function ($query) use ($check_in, $check_out) {
            $query->where('status', 'available')
                ->whereDoesntHave('hotelbookings', function ($b) use ($check_in, $check_out) {

                    $b->whereIn('status', ['confirmed', 'pending'])
                        ->where(function ($overlap) use ($check_in, $check_out) {

                            $overlap->whereBetween('check_in', [$check_in, $check_out])
                                ->orWhereBetween('check_out', [$check_in, $check_out])
                                ->orWhere(function ($full) use ($check_in, $check_out) {
                                    $full->where('check_in', '<=', $check_in)
                                        ->where('check_out', '>=', $check_out);
                                });
                        });
                });
        };

        // 4. MAIN QUERY
        $query = RoomType::with(['images', 'facilities'])

            ->withCount([
                'rooms as available_rooms_count' => $availabilityFilter
            ])

            ->whereHas('rooms', $availabilityFilter)

            ->where('name', 'not like', '%សាលប្រជុំ%');

        // 5. FILTERS

        // type filter
        if ($type_name) {
            $query->where('name', $type_name);
        }

        // search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // guests filter
        if ($guests) {
            $query->where('max_guests', '>=', $guests);
        }

        // 6. SORT + PAGINATION
        $roomTypes = $query
            ->orderBy('base_price', $sort === 'desc' ? 'desc' : 'asc')
            ->paginate(6)
            ->withQueryString();

        // 7. AJAX RESPONSE
        if ($request->ajax()) {
            return view('frontend.partials.room_list', compact('roomTypes'))->render();
        }

        // 8. RETURN VIEW
        return view('frontend.rooms', compact(
            'roomTypes',
            'categories',
            'check_in',
            'check_out',
            'sort',
            'search',
            'guests',
            'type_name'
        ));
    }

    // សម្រាប់ Stay Room
    public function room_detail($id)
    {
        $roomType = RoomType::with(['images', 'facilities:id,name,icon', 'rooms', 'hotel'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->findOrFail($id);

        $similarRooms = RoomType::where('hotel_id', $roomType->hotel_id)
            ->where('id', '!=', $roomType->id)
            ->where('name', 'not like', '%សាល%')
            ->take(3)
            ->get();

        return view('frontend.room_details', compact('roomType', 'similarRooms'));
    }

    // For 
    public function storeReview(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        Review::create([
            'room_type_id' => $request->room_type_id,
            'name' => $request->name,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 1 // ដាក់ឱ្យបង្ហាញភ្លាមៗ ឬដាក់ ០ បើចង់ឱ្យ Admin ពិនិត្យសិន
        ]);

        return back()->with('success', 'សូមអរគុណសម្រាប់ការវាយតម្លៃរបស់អ្នក!');
    }


    // public function show($id)
    // {
    //     $room = Room::with('roomType.facilities')->findOrFail($id);
    //     return view('frontend.details', compact('room'));
    // }
}
