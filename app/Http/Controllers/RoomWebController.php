<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Room;
use App\Models\HotelBooking;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoomWebController extends Controller
{
    public function index(Request $request)
    {
        // INPUT & DATE FILTER DETECT
        $hasDateFilter = $request->filled('check_in') && $request->filled('check_out');
        $check_in  = $request->input('check_in', Carbon::today()->format('Y-m-d'));
        $check_out = $request->input('check_out', Carbon::tomorrow()->format('Y-m-d'));
        $type_name = $request->input('type');
        $sort      = $request->input('sort', 'asc');
        $search    = $request->input('search');
        $guests    = $request->input('guests');
        $min_price = $request->input('min_price');
        $max_price = $request->input('max_price');
        $facility_param = $request->input('facility');

        // CATEGORIES (EXCLUDE MEETING ROOM) & FACILITIES
        $categories = RoomType::where('name', 'not like', '%សាលប្រជុំ%')
            ->select('id', 'name')
            ->distinct()
            ->get();

        $facilitiesList = \App\Models\Facility::select('id', 'name', 'icon')->get();

        // AVAILABILITY LOGIC (REUSABLE)
        $availabilityFilter = function ($query) use ($check_in, $check_out) {
            $query->where('status', '!=', 'maintenance')
                ->whereDoesntHave('hotelbookings', function ($b) use ($check_in, $check_out) {
                    $b->whereIn('status', ['confirmed', 'pending'])
                        ->where('check_in', '<', $check_out)
                        ->where('check_out', '>', $check_in);
                });
        };

        $query = RoomType::with(['images', 'facilities'])
            ->withAvg('reviews as reviews_avg_rating', 'rating')
            ->withCount('reviews')
            ->withCount([
                'rooms as available_rooms_count' => $availabilityFilter
            ])
            ->where('name', 'not like', '%សាលប្រជុំ%');

        if ($type_name) {
            $query->where('name', $type_name);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($guests) {
            $query->where('max_guests', '>=', $guests);
        }

        if ($min_price !== null && $min_price !== '') {
            $query->where('base_price', '>=', (float)$min_price);
        }
        if ($max_price !== null && $max_price !== '') {
            $query->where('base_price', '<=', (float)$max_price);
        }

        if ($facility_param) {
            $query->whereHas('facilities', function ($f) use ($facility_param) {
                $f->where('facilities.id', $facility_param)
                  ->orWhere('facilities.name', 'like', "%{$facility_param}%");
            });
        }

        if ($sort === 'rating') {
            $query->orderByDesc('reviews_avg_rating');
        } elseif ($sort === 'desc') {
            $query->orderByDesc('base_price');
        } elseif ($sort === 'newest') {
            $query->orderByDesc('created_at');
        } else {
            $query->orderBy('base_price', 'asc');
        }

        $roomTypes = $query
            ->paginate(6)
            ->withQueryString();

        if ($request->ajax()) {
            return view('frontend.partials.room_list', compact('roomTypes', 'hasDateFilter', 'check_in', 'check_out'))->render();
        }


        return view('frontend.rooms', compact(
            'roomTypes',
            'categories',
            'facilitiesList',
            'check_in',
            'check_out',
            'sort',
            'search',
            'guests',
            'type_name',
            'min_price',
            'max_price',
            'facility_param',
            'hasDateFilter'
        ));
    }

    // សម្រាប់ Stay Room
    public function room_detail(Request $request, $id)
    {
        $check_in  = $request->input('check_in', Carbon::today()->format('Y-m-d'));
        $check_out = $request->input('check_out', Carbon::tomorrow()->format('Y-m-d'));

        $roomType = RoomType::with(['images', 'facilities:id,name,icon', 'rooms', 'hotel'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->findOrFail($id);

        $availableRoomsCount = Room::where('room_type_id', $roomType->id)
            ->where('status', '!=', 'maintenance')
            ->whereNotExists(function ($query) use ($check_in, $check_out) {
                $query->select(DB::raw(1))
                    ->from('hotel_bookings')
                    ->join('hotel_booking_details', 'hotel_bookings.id', '=', 'hotel_booking_details.hotel_booking_id')
                    ->whereColumn('rooms.id', 'hotel_booking_details.room_id')
                    ->whereIn('hotel_bookings.status', ['confirmed', 'pending'])
                    ->where(function ($q) use ($check_in, $check_out) {
                        $q->where('hotel_bookings.check_in', '<', $check_out)
                          ->where('hotel_bookings.check_out', '>', $check_in);
                    });
            })
            ->count();

        $similarRooms = RoomType::where('hotel_id', $roomType->hotel_id)
            ->where('id', '!=', $roomType->id)
            ->where('name', 'not like', '%សាល%')
            ->take(3)
            ->get();

        return view('frontend.room_details', compact('roomType', 'similarRooms', 'check_in', 'check_out', 'availableRoomsCount'));
    }

    public function checkAvailability(Request $request)
    {
        $roomTypeId = $request->input('room_type_id');
        $checkIn    = $request->input('check_in');
        $checkOut   = $request->input('check_out');

        if (!$roomTypeId || !$checkIn || !$checkOut) {
            return response()->json(['available' => false, 'count' => 0]);
        }

        $availableRoomsCount = Room::where('room_type_id', $roomTypeId)
            ->where('status', '!=', 'maintenance')
            ->whereNotExists(function ($query) use ($checkIn, $checkOut) {
                $query->select(DB::raw(1))
                    ->from('hotel_bookings')
                    ->join('hotel_booking_details', 'hotel_bookings.id', '=', 'hotel_booking_details.hotel_booking_id')
                    ->whereColumn('rooms.id', 'hotel_booking_details.room_id')
                    ->whereIn('hotel_bookings.status', ['confirmed', 'pending'])
                    ->where(function ($q) use ($checkIn, $checkOut) {
                        $q->where('hotel_bookings.check_in', '<', $checkOut)
                          ->where('hotel_bookings.check_out', '>', $checkIn);
                    });
            })
            ->count();

        return response()->json([
            'available' => $availableRoomsCount > 0,
            'count'     => $availableRoomsCount
        ]);
    }

    // For 
    public function storeReview(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'parent_id'    => 'nullable|exists:reviews,id',
            'name'         => 'required_if:user_id,null|string|max:255',
            'rating'       => 'required_without:parent_id|integer|min:1|max:5',
            'comment'      => 'required|string',
        ]);

        $userId = Auth::check() ? Auth::id() : null;
        $name = Auth::check() ? Auth::user()->name : $request->input('name', 'ភ្ញៀវមិនស្គាល់ឈ្មោះ');

        Review::create([
            'room_type_id' => $request->room_type_id,
            'user_id'      => $userId,
            'parent_id'    => $request->parent_id,
            'name'         => $name,
            'rating'       => $request->parent_id ? 0 : $request->rating,
            'comment'      => $request->comment,
            'status'       => 1
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'បានផ្ញើការវាយតម្លៃដោយជោគជ័យ'
            ]);
        }

        return back()->with('success', 'បានផ្ញើការវាយតម្លៃដោយជោគជ័យ');
    }

    public function storeReply(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'parent_id'    => 'required|exists:reviews,id',
            'name'         => Auth::check() ? 'nullable' : 'required|string|max:255',
            'comment'      => 'required|string',
        ]);

        $reply = Review::create([
            'room_type_id' => $request->room_type_id,
            'user_id'      => Auth::id(),
            'parent_id'    => $request->parent_id,
            'name'         => Auth::check() ? Auth::user()->name : $request->name,
            'rating'       => 0,
            'comment'      => $request->comment,
            'status'       => 1
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'បានផ្ញើការឆ្លើយតបដោយជោគជ័យ',
                'reply'   => [
                    'id' => $reply->id,
                    'name' => $reply->name,
                    'comment' => $reply->comment,
                    'created_at' => $reply->created_at->diffForHumans(),
                    'is_admin' => ($reply->user_id == 1),
                    'can_edit' => Auth::check() && ($reply->user_id == Auth::id() || Auth::id() == 1)
                ]
            ]);
        }

        return back()->with('success', 'បានផ្ញើការឆ្លើយតបដោយជោគជ័យ');
    }

    public function updateReview(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        if ($review->user_id !== Auth::id() && Auth::id() !== 1) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'អ្នកគ្មានសិទ្ធិកែប្រែមតិយោបល់នេះទេ'], 403);
            }
            return back()->with('error', 'អ្នកគ្មានសិទ្ធិកែប្រែមតិយោបល់នេះទេ');
        }

        $request->validate([
            'comment' => 'required|string',
            'rating'  => 'nullable|integer|min:1|max:5'
        ]);

        $review->comment = $request->comment;
        if ($review->parent_id == null && $request->has('rating')) {
            $review->rating = $request->rating;
        }
        $review->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'បានកែប្រែមតិយោបល់រួចរាល់',
                'comment' => $review->comment,
                'rating'  => $review->rating
            ]);
        }

        return back()->with('success', 'បានកែប្រែមតិយោបល់រួចរាល់');
    }

    public function deleteReview(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        if ($review->user_id !== Auth::id() && Auth::id() !== 1) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'អ្នកគ្មានសិទ្ធិលុបមតិយោបល់នេះទេ'], 403);
            }
            return back()->with('error', 'អ្នកគ្មានសិទ្ធិលុបមតិយោបល់នេះទេ');
        }

        Review::where('parent_id', $review->id)->delete();
        $review->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'បានលុបមតិយោបល់ដោយជោគជ័យ'
            ]);
        }

        return back()->with('success', 'បានលុបមតិយោបល់ដោយជោគជ័យ');
    }
}
