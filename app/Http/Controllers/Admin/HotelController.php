<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HotelRequest;
use App\Services\HotelService;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    protected $hotelService;

    public function __construct(HotelService $hotelService)
    {
        $this->hotelService = $hotelService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status']);
        $hotels = $this->hotelService->listHotels($filters);

        return view('frontend.index', compact('hotels'));
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

    public function update(HotelRequest $request, $id)
    {
        try {
            $this->hotelService->updateHotel($id, $request->validated());
            return back()->with('success', 'សណ្ឋាគារត្រូវបានធ្វើបច្ចុប្បន្នភាព!');
        } catch (\Exception $e) {
            return back()->with('error', 'មានបញ្ហា៖ ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->hotelService->deleteHotel($id);
            return back()->with('success', 'សណ្ឋាគារត្រូវបានលុប!');
        } catch (\Exception $e) {
            return back()->with('error', 'មិនអាចលុបបាន៖ ' . $e->getMessage());
        }
    }
}
