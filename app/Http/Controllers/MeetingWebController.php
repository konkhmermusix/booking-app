<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\RoomType;
use App\Models\Room;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MeetingWebController extends Controller
{
    public function index(Request $request)
    {
        // ចាប់យក INPUT ពី Request
        $hasDateFilter = $request->filled('check_in');
        $check_in  = $request->input('check_in', Carbon::today()->format('Y-m-d'));
        $check_out = $request->input('check_out', $check_in);
        $sort      = $request->input('sort', 'asc');
        $search    = $request->input('search');
        $guests    = $request->input('guests');

        // លក្ខខណ្ឌសម្រាប់សម្គាល់ថាជា "សាលប្រជុំ" (Reusable Meeting Logic)
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

        // AVAILABILITY LOGIC (ពិនិត្យបន្ទប់ទំនេរ)
        $availabilityFilter = function ($query) use ($check_in, $check_out) {
            $query->where('status', '!=', 'maintenance')
                ->whereDoesntHave('meetingBookings', function ($b) use ($check_in, $check_out) {
                    $b->whereIn('status', ['confirmed', 'pending'])
                        ->where('start_date', '<=', $check_out)
                        ->where('end_date', '>=', $check_in);
                });
        };

        // MAIN QUERY
        $query = RoomType::with(['images', 'facilities'])
            ->withAvg('reviews as reviews_avg_rating', 'rating')
            ->withCount('reviews')
            ->withCount([
                'rooms as available_rooms_count' => $availabilityFilter
            ])
            ->where($meetingCondition);


        // បន្ថែម FILTERS (ប្រសិនបើមានការជ្រើសរើស)
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

        // រៀបលំដាប់ និងបែងចែកទំព័រ (SORT + PAGINATION)
        $meetingRooms = $query
            ->orderBy('base_price', $sort === 'desc' ? 'desc' : 'asc')
            ->paginate(6)
            ->withQueryString();

        // AJAX RESPONSE (សម្រាប់ Filter ប្តូរទិន្នន័យដោយមិន Refresh Page)
        if ($request->ajax()) {
            return view('frontend.partials.meeting_list', compact('meetingRooms', 'hasDateFilter'))->render();
        }

        // បោះទិន្នន័យទៅកាន់ View
        return view('frontend.meeting', compact(
            'meetingRooms',
            'check_in',
            'check_out',
            'sort',
            'search',
            'guests',
            'hasDateFilter'
        ));
    }

    // សម្រាប់ Meeting Hall
    public function meeting_detail(Request $request, $id)
    {
        $startDate = $request->input('start_date', Carbon::today()->format('Y-m-d'));
        $endDate   = $request->input('end_date', Carbon::today()->format('Y-m-d'));
        $startTime = $request->input('start_time', '08:00');
        $endTime   = $request->input('end_time', '17:00');

        $roomType = RoomType::with(['images', 'facilities:id,name,icon', 'rooms', 'hotel'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->findOrFail($id);

        $availableRoomsCount = Room::where('room_type_id', $roomType->id)
            ->where('status', '!=', 'maintenance')
            ->whereNotExists(function ($query) use ($startDate, $endDate, $startTime, $endTime) {
                $query->select(DB::raw(1))
                    ->from('meeting_bookings')
                    ->whereColumn('rooms.id', 'meeting_bookings.meeting_room_id')
                    ->whereIn('meeting_bookings.status', ['confirmed', 'pending'])
                    ->where(function ($q) use ($startDate, $endDate, $startTime, $endTime) {
                        $q->where('meeting_bookings.start_date', '<=', $endDate)
                          ->where('meeting_bookings.end_date', '>=', $startDate)
                          ->where('meeting_bookings.start_time', '<', $endTime)
                          ->where('meeting_bookings.end_time', '>', $startTime);
                    });
            })
            ->count();

        $similarRooms = RoomType::where('hotel_id', $roomType->hotel_id)
            ->where('id', '!=', $roomType->id)
            ->where(function ($q) {
                $q->where('name', 'like', '%សាល%')
                    ->orWhere('name', 'like', '%Meeting%')
                    ->orWhere('name', 'like', '%Hall%');
            })
            ->take(3)
            ->get();

        return view('frontend.meeting_details', compact('roomType', 'similarRooms', 'availableRoomsCount'));
    }

    public function checkAvailability(Request $request)
    {
        $roomTypeId = $request->input('room_type_id');
        $startDate  = $request->input('start_date');
        $endDate    = $request->input('end_date');
        $startTime  = $request->input('start_time');
        $endTime    = $request->input('end_time');

        if (!$roomTypeId || !$startDate || !$endDate || !$startTime || !$endTime) {
            return response()->json(['available' => false, 'count' => 0]);
        }

        $availableRoomsCount = Room::where('room_type_id', $roomTypeId)
            ->where('status', '!=', 'maintenance')
            ->whereNotExists(function ($query) use ($startDate, $endDate, $startTime, $endTime) {
                $query->select(DB::raw(1))
                    ->from('meeting_bookings')
                    ->whereColumn('rooms.id', 'meeting_bookings.meeting_room_id')
                    ->whereIn('meeting_bookings.status', ['confirmed', 'pending'])
                    ->where(function ($q) use ($startDate, $endDate, $startTime, $endTime) {
                        $q->where('meeting_bookings.start_date', '<=', $endDate)
                          ->where('meeting_bookings.end_date', '>=', $startDate)
                          ->where('meeting_bookings.start_time', '<', $endTime)
                          ->where('meeting_bookings.end_time', '>', $startTime);
                    });
            })
            ->count();

        return response()->json([
            'available' => $availableRoomsCount > 0,
            'count'     => $availableRoomsCount
        ]);
    }

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
