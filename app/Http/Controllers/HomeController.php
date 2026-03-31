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

class HomeController extends Controller
{
    public function index(Request $request)
    {

        // ២. ចាប់យកតម្លៃស្វែងរកពី Input (ជាមួយ Default Value ជាថ្ងៃនេះ និងស្អែក)
        $check_in = $request->input('check_in', Carbon::today()->format('Y-m-d'));
        $check_out = $request->input('check_out', Carbon::tomorrow()->format('Y-m-d'));
        $type_id = $request->input('room_type_id');

        // ៣. Query បន្ទប់ដែលទំនេរ
        $query = Room::with(['roomType', 'hotel']);

        if ($type_id) {
            $query->where('room_type_id', $type_id);
        }

        // Logic ឆែកបន្ទប់ដែលមិនទាន់មានគេកក់ (Available)
        if ($check_in && $check_out) {
            $query->whereDoesntHave('bookings', function ($q) use ($check_in, $check_out) {
                $q->where(function ($b) use ($check_in, $check_out) {
                    $b->whereBetween('check_in', [$check_in, $check_out])
                        ->orWhereBetween('check_out', [$check_in, $check_out])
                        ->orWhere(function ($overlap) use ($check_in, $check_out) {
                            $overlap->where('check_in', '<=', $check_in)
                                ->where('check_out', '>=', $check_out);
                        });
                })->whereIn('status', ['confirmed', 'pending']);
            });
        }

        $slides = Slideshow::where('is_active', true)
            ->orderBy('order_column', 'asc')
            ->get();

        $availableRooms = Room::with(['roomType.images', 'hotel'])
            ->where('status', 'available')
            ->latest()
            ->take(6)
            ->get();

        $roomTypes = RoomType::with(['images', 'facilities', 'rooms']) // ថែម facilities ដើម្បីបង្ហាញ Icon
            ->withCount(['rooms as available_rooms_count' => function ($query) {
                $query->where('status', 'available');
            }])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->whereHas('rooms', function ($query) {
                $query->where('status', 'available');
            })
            ->get();

        $promotions = Promotion::with(['roomType.rooms' => function ($q) {
            $q->where('status', 'available'); // យកតែបន្ទប់ណាដែលទំនេរ
        }])
            ->where('status', 1)
            ->where('expiry_date', '>=', now())
            ->latest()
            ->get();

        $facilities = Facility::where('is_active', '1')->get();

        $tours = Tour::where('status', 1)->latest()->get();

        $roomTypeImage = RoomType::with('images')->has('images', '>=', 4)->first();

        // បើរកមិនឃើញ RoomType ដែលមានរូបភាពគ្រប់ ៤ ទេ យើងទាញយកធម្មតាមកវិញ
        if (!$roomTypeImage) {
            $roomTypeImage = RoomType::with('images')->first();
        }

        $displayImages = RoomImage::with('roomType')
            ->latest()
            ->take(8)
            ->get();

        return view('frontend.index', compact('slides', 'roomTypes', 'availableRooms', 'check_in', 'check_out', 'promotions', 'facilities', 'tours', 'roomTypeImage', 'displayImages'));
    }


    public function showHotel($id)
    {
        // $roomType = RoomType::with([
        //     'images',
        //     'facilities',
        //     'rooms',
        //     'hotel'
        // ])->findOrFail($id);

        $roomType = RoomType::with(['images', 'facilities:id,name,icon', 'rooms', 'hotel'])
            ->findOrFail($id);

        $similarRooms = RoomType::where('hotel_id', $roomType->hotel_id)
            ->where('id', '!=', $roomType->id)
            ->take(3)
            ->get();

        return view('frontend.details', compact(
            'roomType',
            'similarRooms'
        ));
    }

    public function roomTypeDetails($id)
    {
        $roomType = RoomType::with(['images', 'rooms' => function ($q) {
            $q->where('status', 'available');
        }, 'facilities'])->findOrFail($id);

        // ទាញយក Room Types ដែលស្រដៀងគ្នា (Similar Rooms)
        $similarRooms = RoomType::where('hotel_id', $roomType->hotel_id)
            ->where('id', '!=', $roomType->id)
            ->take(3)
            ->get();

        return view('frontend.details', compact('roomType', 'similarRooms'));
    }
    // នៅក្នុង HotelController.php ឬ HomeController.php ត្រង់ Method ដែលបង្ហាញ Hotel Detail
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
