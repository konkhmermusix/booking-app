<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class ShowController extends Controller
{
    public function show($id)
    {
        $room = Room::with([
            'roomType.images',
            'roomType.facilities',
            'hotel'
        ])->findOrFail($id);

        $relatedRooms = Room::with(['roomType.images', 'hotel'])
            ->where('room_type_id', $room->room_type_id)
            ->where('id', '!=', $id)
            ->where('status', 'available')
            ->limit(3)
            ->get();

        return view('frontend.show', compact('room', 'relatedRooms'));
    }
}
