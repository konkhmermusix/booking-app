<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{

    // បង្ហាញបញ្ជីសណ្ឋាគារទាំងអស់
    public function index()
    {
        $hotels = Hotel::all();
        return view('admin.hotels.index', compact('hotels'));
    }

    // បង្ហាញ Form បង្កើតថ្មី
    public function create()
    {
        return view('admin.hotels.create');
    }

    // រក្សាទុកទិន្នន័យដែលបញ្ចូល
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'phone' => 'nullable',
        ]);

        Hotel::create($data);
        return redirect()->route('hotels.index')->with('success', 'បង្កើតបានជោគជ័យ');
    }

    // បង្ហាញ Form កែសម្រួល (Edit)
    public function edit(Hotel $hotel)
    {
        return view('admin.hotels.edit', compact('hotel'));
    }

    // Update ទិន្នន័យ
    public function update(Request $request, Hotel $hotel)
    {
        $data = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'phone' => 'nullable',
        ]);

        $hotel->update($data);
        return redirect()->route('hotels.index')->with('success', 'កែសម្រួលបានជោគជ័យ');
    }

    // លុបទិន្នន័យ
    public function destroy(Hotel $hotel)
    {
        $hotel->delete();
        return redirect()->route('hotels.index');
    }
   
}
