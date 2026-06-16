<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HotelRequest;
use App\Models\Hotel;
use App\Services\HotelService;
use Illuminate\Http\Request;
use Exception;

class HotelController extends Controller
{
    public function __construct(
        protected hotelService $hotelService,
    ) {}


    public function index(Request $request)
    {

        $filters = $request->only(['search', 'status']);
        $hotels = $this->hotelService->listHotels($filters);

        if ($request->ajax()) {
            return view('admin.hotels.partials.hotel_list', compact('hotels'))->render();
        }

        return view('admin.hotels.index', compact('hotels'));
    }

    public function store(HotelRequest $request)
    {
        try {
            $this->hotelService->storeHotel($request->validated());
            return back()->with('success', 'សណ្ឋាគារត្រូវបានរក្សាទុកដោយជោគជ័យ!');
        } catch (\Exception $e) {
            return back()->with('error', 'មានបញ្ហា៖ ' . $e->getMessage());
        }
    }

    public function update(HotelRequest $request, int $id)
    {
        try {
            $this->hotelService->updateHotel($id, $request->validated());
            return back()->with('success', 'សណ្ឋាគារត្រូវបានធ្វើបច្ចុប្បន្នភាព!');
        } catch (\Exception $e) {
            return back()->with('error', 'មានបញ្ហា៖ ' . $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->hotelService->deleteHotel($id);
            return back()->with('success', 'សណ្ឋាគារត្រូវបានលុប!');
        } catch (\Exception $e) {
            return back()->with('error', 'មិនអាចលុបបាន៖ ' . $e->getMessage());
        }
    }
}
