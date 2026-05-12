<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomType;
use Carbon\Carbon;

class MeetingWebController extends Controller
{
    public function index(Request $request)
    {
        // 1. ចាប់យក INPUT ពី Request
        $check_in  = $request->input('check_in', Carbon::today()->format('Y-m-d'));
        $check_out = $request->input('check_out', Carbon::tomorrow()->format('Y-m-d'));
        $sort      = $request->input('sort', 'asc');
        $search    = $request->input('search');
        $guests    = $request->input('guests');

        // 2. លក្ខខណ្ឌសម្រាប់សម្គាល់ថាជា "សាលប្រជុំ" (Reusable Meeting Logic)
        $meetingCondition = function ($query) {
            $query->where('name', 'like', '%សាលប្រជុំ%')
                ->orWhere('name', 'like', '%សាលប្រជុំធំ%')
                ->orWhere('name', 'like', '%សាលប្រជុំមធ្យម%')
                ->orWhere('name', 'like', '%សាលប្រជុំតូច%')
                ->orWhere('name', 'like', '%Meeting%')
                ->orWhere('name', 'like', '%Conference%')
                ->orWhere('name', 'like', '%Hall%')
                ->orWhere('name', 'like', '%Ballroom%');
        };

        // 3. AVAILABILITY LOGIC (ពិនិត្យបន្ទប់ទំនេរ)
        $availabilityFilter = function ($query) use ($check_in, $check_out) {
            $query->where('status', 'available')
                ->whereDoesntHave('bookings', function ($b) use ($check_in, $check_out) {
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
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            // ចាប់យកតែប្រភេទសាលប្រជុំ
            ->where($meetingCondition);

        // 5. បន្ថែម FILTERS (ប្រសិនបើមានការជ្រើសរើស)

        // ស្វែងរកតាមឈ្មោះ
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // ចំនួនអ្នកចូលរួម (សម្រាប់សាលប្រជុំ max_guests គឺសំដៅលើចំណុះមនុស្ស)
        if ($guests) {
            $query->where('max_guests', '>=', $guests);
        }

        // 6. រៀបលំដាប់ និងបែងចែកទំព័រ (SORT + PAGINATION)
        $meetingRooms = $query
            ->orderBy('base_price', $sort === 'desc' ? 'desc' : 'asc')
            ->paginate(6)
            ->withQueryString();

        // 7. AJAX RESPONSE (សម្រាប់ Filter ប្តូរទិន្នន័យដោយមិន Refresh Page)
        if ($request->ajax()) {
            return view('frontend.partials.meeting_list', compact('meetingRooms'))->render();
        }

        // 8. បោះទិន្នន័យទៅកាន់ View
        return view('frontend.meeting', compact(
            'meetingRooms',
            'check_in',
            'check_out',
            'sort',
            'search',
            'guests'
        ));
    }

    public function meeting_detail($id)
    {
        $roomType = RoomType::with([
            'images',
            'facilities',
            'rooms.hotel',
            'reviews.user'
        ])
            ->withCount([
                'rooms as available_rooms_count' => function ($query) {
                    $query->where('status', 'available');
                }
            ])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->findOrFail($id);

        // related rooms
        $relatedRooms = RoomType::with('images')
            ->where('id', '!=', $id)
            ->where('name', 'like', '%សាលប្រជុំ%')
            ->take(4)
            ->get();

        return view('frontend.meeting_detail', compact(
            'roomType',
            'relatedRooms'
        ));
    }
}
