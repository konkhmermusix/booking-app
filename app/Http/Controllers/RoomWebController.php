<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Room;
use App\Models\Booking;

class RoomWebController extends Controller
{

    public function index(Request $request)
    {
        // 1. Get Inputs & Validation
        $check_in = $request->input('check_in', now()->format('Y-m-d'));
        $check_out = $request->input('check_out', now()->addDay()->format('Y-m-d'));
        $type_name = $request->input('type');
        $sort = $request->input('sort', 'asc');
        $search = $request->input('search');
        $guests = $request->input('guests');

        // 2. Get Categories for Filter
        $categories = RoomType::select('name')->distinct()->get();

        // 3. Main Query with Availability Check Logic
        // បង្កើត closure ទុកសម្រាប់ប្រើឡើងវិញ កុំឱ្យសរសេរស្ទួន
        $availabilityFilter = function ($q) use ($check_in, $check_out) {
            $q->where('status', 'available')
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

        $query = RoomType::with(['images', 'facilities'])
            ->withCount(['rooms as available_rooms_count' => $availabilityFilter])
            ->whereHas('rooms', $availabilityFilter);

        // 4. Advanced Filters
        if ($type_name) {
            $query->where('name', $type_name);
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($guests) {
            $query->where('max_guests', '>=', $guests);
        }

        // 5. Sorting & Pagination
        $roomTypes = $query->orderBy('base_price', $sort)
            ->paginate(6)
            ->withQueryString();

        // 6. Response Handlers
        if ($request->ajax()) {
            return view('frontend.partials.room_list', compact('roomTypes'))->render();
        }

        return view('frontend.rooms', compact(
            'roomTypes',
            'categories',
            'check_in',
            'check_out',
            'sort'
        ));
    }

    public function storeBooking(Request $request)
    {
        // 1. Validation
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1',
        ]);

        try {
            // 2. ស្វែងរកបន្ទប់ដែលទំនេរ (Available) សម្រាប់ប្រភេទដែលគេរើស
            $room = Room::where('room_type_id', $request->room_type_id)
                ->where('status', 'available')
                ->whereDoesntHave('bookings', function ($query) use ($request) {
                    $query->where(function ($q) use ($request) {
                        $q->whereBetween('check_in', [$request->check_in, $request->check_out])
                            ->orWhereBetween('check_out', [$request->check_in, $request->check_out]);
                    })->whereIn('status', ['confirmed', 'pending']);
                })->first();

            if (!$room) {
                return response()->json([
                    'success' => false,
                    'message' => 'សូមអភ័យទោស បន្ទប់ប្រភេទនេះត្រូវបានគេកក់អស់ហើយ សម្រាប់កាលបរិច្ឆេទនេះ!'
                ], 422);
            }

            // 3. បង្កើតទិន្នន័យកក់
            Booking::create([
                'user_id' => auth()->id(),
                'room_id' => $room->id,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'guests' => $request->guests,
                'status' => 'pending',
                'total_price' => 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'ការកក់របស់អ្នកត្រូវបានបញ្ជូនដោយជោគជ័យ! យើងនឹងទាក់ទងទៅអ្នកឆាប់ៗ។'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'មានបញ្ហាបច្ចេកទេស៖ ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $room = Room::with('roomType.facilities')->findOrFail($id);
        return view('frontend.details', compact('room'));
    }
}
