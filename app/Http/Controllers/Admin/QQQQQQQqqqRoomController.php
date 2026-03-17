<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with(['hotel', 'roomType']); // Eager Loading ការពារ N+1 Problem

        // Search Logic
        if ($request->filled('search')) {
            $query->where('room_number', 'like', '%' . $request->search . '%');
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rooms = $query->latest()->paginate(10)->withQueryString();
        $hotels = Hotel::all();
        $roomTypes = RoomType::all();

        return view('admin.rooms.index', compact('rooms', 'hotels', 'roomTypes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_type_id' => 'required|exists:room_types,id',
            'room_number' => 'required|string|max:50',
            'floor' => 'nullable|string|max:20',
            'status' => 'required|in:available,booked,maintenance',
        ]);

        Room::create($data);
        return back()->with('success', 'បន្ថែមបន្ទប់ថ្មីបានជោគជ័យ!');
    }

    public function update(Request $request, Room $room)
    {
        $data = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_type_id' => 'required|exists:room_types,id',
            'room_number' => 'required|string|max:50',
            'floor' => 'nullable|string|max:20',
            'status' => 'required|in:available,booked,maintenance',
        ]);

        $room->update($data);
        return back()->with('success', 'ធ្វើបច្ចុប្បន្នភាពបន្ទប់ជោគជ័យ!');
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return back()->with('success', 'លុបបន្ទប់បានជោគជ័យ!');
    }
}
