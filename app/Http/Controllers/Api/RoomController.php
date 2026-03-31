<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        // ប្រើ with(['roomType']) ដើម្បីឱ្យវាជាប់ data room_type មកជាមួយ
        $rooms = Room::with(['roomType'])->get();
        return response()->json($rooms);
    }

    public function show($id)
    {
        $roomType = Room::with(['images', 'facilities'])->find($id);

        if (!$roomType) {
            return response()->json(['message' => 'រកមិនឃើញប្រភេទបន្ទប់នេះទេ'], 404);
        }

        return response()->json($roomType, 200);
    }
}
