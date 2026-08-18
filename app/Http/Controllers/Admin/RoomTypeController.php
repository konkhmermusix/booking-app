<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomTypeRequest;
use App\Services\RoomTypeService;
use App\Models\RoomType;
use App\Models\RoomImage;
use App\Models\Hotel;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class RoomTypeController extends Controller
{
    public function __construct(
        protected RoomTypeService $roomTypeService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->all();
        $roomTypes = $this->roomTypeService->getAllRoomType($filters);

        if ($request->ajax()) {
            return view('admin.room_types.partials.roomtype_list', compact('roomTypes'))->render();
        }

        $totalRoomTypes = RoomType::count();
        $stayTypesCount = RoomType::where(function($q) {
            $q->where('category', 'stay')->orWhereNull('category')->orWhere('category', '');
        })->count();
        $meetingTypesCount = RoomType::where('category', 'meeting')->count();
        $avgPrice = RoomType::avg('base_price') ?? 0;

        $hotels = Hotel::where('status', 1)->get();
        $facilities = Facility::where('is_active', 1)->get();

        return view('admin.room_types.index', compact('roomTypes', 'hotels', 'facilities', 'totalRoomTypes', 'stayTypesCount', 'meetingTypesCount', 'avgPrice'));
    }

    public function store(RoomTypeRequest $request)
    {
        try {
            $this->roomTypeService->storeRoomType($request->validated());
            return redirect()->back()->with('success', 'បង្កើតប្រភេទបន្ទប់បានជោគជ័យ');
        } catch (Exception $e) {
            Log::error("RoomType Store Error: " . $e->getMessage());
            return redirect()->back()->with('error', 'មានបញ្ហា: ' . $e->getMessage());
        }
    }

    public function update(RoomTypeRequest $request, RoomType $roomType)
    {
        try {
            $this->roomTypeService->updateRoomType($roomType->id, $request->validated());
            return back()->with('success', 'ធ្វើបច្ចុប្បន្នភាពជោគជ័យ');
        } catch (Exception $e) {
            Log::error("RoomType Update Error: " . $e->getMessage());
            return back()->with('error', 'មិនអាចកែសម្រួលបានទេ៖ ' . $e->getMessage());
        }
    }

    public function destroy(RoomType $roomType)
    {
        try {
            $result = $this->roomTypeService->deleteRoomType($roomType->id);

            if (!$result) {
                return back()->with('error', 'មិនអាចលុបបានទេ');
            }

            return back()->with('success', 'លុបប្រភេទបន្ទប់រួចរាល់');
        } catch (Exception $e) {
            Log::error("RoomType Delete Error: " . $e->getMessage());
            return back()->with('error', 'មានកំហុស៖ ' . $e->getMessage());
        }
    }

    public function destroyImage($id)
    {
        try {
            $image = RoomImage::findOrFail($id);

            if ($image->path && Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }

            $image->delete();

            return response()->json([
                'success' => true,
                'message' => 'លុបរូបភាពរួចរាល់'
            ]);
        } catch (Exception $e) {
            Log::error("RoomImage Delete Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'កំហុស៖ ' . $e->getMessage()
            ], 500);
        }
    }
}
