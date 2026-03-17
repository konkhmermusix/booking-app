<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function index()
    {
        // ទាញយក RoomType ទាំងអស់ជាមួយរូបភាព និង Facilities
        $roomTypes = RoomType::with(['images', 'facilities'])->get();

        return response()->json($roomTypes, 200);
    }

    public function show($id)
    {
        $roomType = RoomType::with(['images', 'facilities'])->find($id);

        if (!$roomType) {
            return response()->json(['message' => 'រកមិនឃើញប្រភេទបន្ទប់នេះទេ'], 404);
        }

        return response()->json($roomType, 200);
    }
}