<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    // ទាញយកបញ្ជីសណ្ឋាគារទាំងអស់
    public function index()
    {
        $hotels = Hotel::where('status', 1)->get();
        return response()->json([
            'success' => true,
            'data' => $hotels
        ]);
    }

    // ទាញយកព័ត៌មានលម្អិតនៃសណ្ឋាគារមួយ រួមទាំងប្រភេទបន្ទប់របស់វា
    public function show($id)
    {
        $hotel = Hotel::with('roomTypes')->find($id);

        if (!$hotel) {
            return response()->json([
                'success' => false,
                'message' => 'រកមិនឃើញសណ្ឋាគារនេះទេ'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $hotel
        ]);
    }
}