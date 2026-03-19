<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomWebController extends Controller
{
    public function index(Request $request)
    {
        // ១. ទាញយកឈ្មោះប្រភេទបន្ទប់ទាំងអស់សម្រាប់ធ្វើប៊ូតុង Filter (Admin បញ្ចូលអ្វី បង្ហាញហ្នឹង)
        $categories = RoomType::select('name')->distinct()->get();

        // ២. Query សម្រាប់បង្ហាញ Card បន្ទប់
        $query = RoomType::with(['images', 'facilities'])
            ->withCount(['rooms as available_rooms_count' => function ($q) {
                $q->where('status', 'available');
            }]);

        // បង្ហាញតែប្រភេទណាដែលមានបន្ទប់ Status 'available' យ៉ាងតិច ១
        $query->whereHas('rooms', function ($q) {
            $q->where('status', 'available');
        });

        // Filter តាមប្រភេទដែល Admin បានបញ្ចូល (ចុចលើប៊ូតុងណា Filter តាមហ្នឹង)
        if ($request->filled('type')) {
            $query->where('name', $request->type);
        }

        $roomTypes = $query->paginate(6)->withQueryString();

        return view('frontend.rooms', compact('roomTypes', 'categories'));
    }
}
