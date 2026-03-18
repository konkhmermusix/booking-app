<?php

// namespace App\Http\Controllers;

// use App\Models\RoomType;
// use App\Models\Hotel;
// use Illuminate\Http\Request;

// class RoomTypeController extends Controller
// {
//     public function index()
//     {
//         $roomTypes = RoomType::with('hotel')->get();
//         return view('admin.room_types.index', compact('roomTypes'));
//     }

//     public function create()
//     {
//         $hotels = Hotel::all();
//         return view('admin.room_types.create', compact('hotels'));
//     }

//     public function store(Request $request)
//     {
//         $data = $request->validate([
//             'hotel_id' => 'required|exists:hotels,id',
//             'name' => 'required|string|max:255',
//             'base_price' => 'required|numeric',
//             'max_guests' => 'required|integer',
//             'description' => 'nullable|string'
//         ]);

//         RoomType::create($data);
//         return redirect()->route('room_types.index')->with('success', 'បង្កើតប្រភេទបន្ទប់ជោគជ័យ!');
//     }

//     public function edit(RoomType $roomType)
//     {
//         $hotels = Hotel::all();
//         return view('admin.room_types.edit', compact('roomType', 'hotels'));
//     }

//     public function update(Request $request, RoomType $roomType)
//     {
//         $data = $request->validate([
//             'hotel_id' => 'required|exists:hotels,id',
//             'name' => 'required|string|max:255',
//             'base_price' => 'required|numeric',
//             'max_guests' => 'required|integer',
//             'description' => 'nullable|string'
//         ]);

//         $roomType->update($data);
//         return redirect()->route('room_types.index')->with('success', 'កែសម្រួលជោគជ័យ!');
//     }

//     public function destroy(RoomType $roomType)
//     {
//         $roomType->delete();
//         return redirect()->route('room_types.index');
//     }
// }
