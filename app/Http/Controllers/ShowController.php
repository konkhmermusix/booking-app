<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class ShowController extends Controller
{
    public function show($id)
    {
        // ទាញយកបន្ទប់ រួមជាមួយ Type, រូបភាព Gallery, សណ្ឋាគារ និង សម្ភារៈ (Facilities)
        $room = Room::with([
            'roomType.images',   // ទាញយករូបភាពទាំងអស់ក្នុង Gallery
            'roomType.facilities', // ទាញយកសម្ភារៈបន្ទប់ (បើមានចង Relation)
            'hotel'              // ទាញយកព័ត៌មានសណ្ឋាគារ (address, phone...)
        ])->findOrFail($id);

        // ទាញយកបន្ទប់ផ្សេងទៀតដែលស្រដៀងគ្នា (សម្រាប់បង្ហាញជា Recommendation)
        $relatedRooms = Room::with(['roomType.images', 'hotel'])
            ->where('room_type_id', $room->room_type_id)
            ->where('id', '!=', $id) // កុំឱ្យបង្ហាញបន្ទប់ដដែល
            ->where('status', 'available')
            ->limit(3)
            ->get();

        return view('frontend.show', compact('room', 'relatedRooms'));
    }
}
