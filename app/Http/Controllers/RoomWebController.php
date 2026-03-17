<?php

namespace App\Http\Controllers;

use App\Models\RoomType; // កុំភ្លេច Import Model
use Illuminate\Http\Request;

class RoomWebController extends Controller
{
    public function index()
    {
        // ទាញយក RoomType ទាំងអស់ រួមជាមួយរូបភាព និងសេវាកម្ម
        // យើងប្រើ with() ដើម្បីការពារបញ្ហា N+1 Query
        $roomTypes = RoomType::with(['images', 'facilities'])
            ->withCount(['rooms as available_rooms_count' => function ($query) {
                $query->where('status', 'available'); // រាប់តែបន្ទប់ណាដែលទំនេរ
            }])
            ->get();

        return view('frontend.rooms', compact('roomTypes'));
    }
}
