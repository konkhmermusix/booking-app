<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\RoomType;
use Carbon\Carbon;
use App\Models\Slideshow;

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

        $roomTypes = RoomType::with(['images', 'rooms'])
            ->whereHas('rooms', function ($query) {
                $query->where('status', 'available'); // បង្ហាញតែប្រភេទណាដែលមានបន្ទប់ទំនេរ
            })
            ->get();

        return view('frontend.index', compact('slides', 'roomTypes', 'availableRooms', 'check_in', 'check_out'));
    }

    // public function showHotel($id)
    // {
    //     return view('frontend.hotel-details');
    // }

    public function showHotel($id)
    {
        $roomType = RoomType::with([
            'images',
            'facilities',
            'rooms',
            'hotel'
        ])->findOrFail($id);

        $similarRooms = RoomType::where('hotel_id', $roomType->hotel_id)
            ->where('id', '!=', $roomType->id)
            ->take(3)
            ->get();

        return view('frontend.details', compact(
            'roomType',
            'similarRooms'
        ));
    }

    // public function roomTypeDetails($id)
    // {
    //     // ទាញយកប្រភេទបន្ទប់ដែលមាន ID ត្រូវគ្នា
    //     $roomType = RoomType::with(['images', 'rooms' => function ($q) {
    //         $q->where('status', 'available'); // បង្ហាញតែបន្ទប់ណាដែលទំនេរ
    //     }])->findOrFail($id);

    //     return view('frontend.details', compact('roomType'));
    // }

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
}
