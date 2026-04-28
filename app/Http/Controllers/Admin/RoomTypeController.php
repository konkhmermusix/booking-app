<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomTypeRequest;
use App\Services\RoomTypeService;
use App\Repositories\RoomTypeRepository;
use App\Models\RoomImage;
use App\Models\Hotel;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RoomTypeController extends Controller
{
    protected $roomTypeService, $roomTypeRepository;

    public function __construct(RoomTypeService $roomTypeService, RoomTypeRepository $roomTypeRepository)
    {
        $this->roomTypeService = $roomTypeService;
        $this->roomTypeRepository = $roomTypeRepository; // កែសម្រួលត្រង់នេះ (មុននេះអ្នកដាក់ជាន់គ្នា)
    }

    public function index(Request $request)
    {
        // ១. ទាញយក Filter ទាំងអស់ពី Request (រួមមាន search, hotel_id, ...)
        $filters = $request->all();

        // ២. ហៅ Service ឱ្យទាញទិន្នន័យតាម Filter
        $roomTypes = $this->roomTypeService->getAllRoomType($filters);


        // ៣. បើជា AJAX (ពេលវាយ Search) ឱ្យបោះទៅ Partial List
        if ($request->ajax()) {
            return view('admin.room_types.partials.room-type-list', compact('roomTypes'))->render();
        }

        $hotels = Hotel::where('status', 1)->get();
        $facilities = Facility::where('is_active', 1)->get();

        return view('admin.room_types.index', compact('roomTypes', 'hotels', 'facilities'));
    }

    public function store(RoomTypeRequest $request)
    {
        try {
            $roomType = $this->roomTypeService->storeRoomType($request->validated());
            return redirect()->back()->with('success', 'បង្កើតប្រភេទបន្ទប់បានជោគជ័យ!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'មានបញ្ហា: ' . $e->getMessage());
        }
    }

    public function update(RoomTypeRequest $request, $id)
    {
        try {
            // ហៅ Service ឱ្យ Update ទាំងទិន្នន័យអក្សរ និងរូបភាពថ្មីៗ
            $this->roomTypeService->updateRoomType($id, $request->validated());

            return back()->with('success', 'ធ្វើបច្ចុប្បន្នភាពជោគជ័យ!');
        } catch (\Exception $e) {
            Log::error("RoomType Update Error: " . $e->getMessage());
            return back()->with('error', 'មិនអាចកែសម្រួលបានទេ៖ ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        try {
            $result = $this->roomTypeService->deleteRoomType($id);

            if (!$result) {
                return back()->with('error', 'មិនអាចលុបបានទេ!');
            }

            return back()->with('success', 'លុបប្រភេទបន្ទប់រួចរាល់!');
        } catch (\Exception $e) {
            return back()->with('error', 'មានកំហុស៖ ' . $e->getMessage());
        }
    }

    public function deleteImage($id)
    {
        $image = RoomImage::find($id); // ឬឈ្មោះ Model រូបភាពរបស់លោកអ្នក

        if ($image) {
            // លុប File ចេញពី Storage (បើមាន)
            if (Storage::exists($image->path)) {
                Storage::delete($image->path);
            }

            $image->delete(); // លុប Record ចេញពី Database

            return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Image not found'], 404);
    }

    /**
     * លុបរូបភាពមួយសន្លឹកតាមរយៈ AJAX
     */
    public function destroyImage($id)
    {
        try {
            $image = RoomImage::findOrFail($id);

            // ១. លុប File ចេញពី Storage
            if ($image->path && Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }

            // ២. លុបពី Database
            $image->delete();

            return response()->json([
                'success' => true,
                'message' => 'លុបរូបភាពរួចរាល់'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'កំហុស៖ ' . $e->getMessage()
            ], 500);
        }
    }
}
