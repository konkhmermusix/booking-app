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
        // ប្រើ Repository សម្រាប់ទាញទិន្នន័យ (Search/Filter)
        $roomTypes = $this->roomTypeRepository->getRoomTypes($request->all());
        $hotels = Hotel::all();
        // ៣. បន្ថែមការទាញយកគ្រឿងបរិក្ខារ (ចំណុចដែលខ្វះ)
        $facilities = Facility::all();

        return view('admin.room_types.index', compact('roomTypes', 'hotels', 'facilities'));
    }

    public function store(RoomTypeRequest $request)
    {
        try {
            // បោះការងារឱ្យ Service អ្នកចាត់ចែង (ទាំង DB និង Upload រូបភាព)
            $this->roomTypeService->storeRoomType($request->validated());

            return back()->with('success', 'បង្កើតប្រភេទបន្ទប់ជោគជ័យ!');
        } catch (\Exception $e) {
            Log::error("RoomType Store Error: " . $e->getMessage());
            return back()->with('error', 'មានបញ្ហាបច្ចេកទេស៖ ' . $e->getMessage());
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

    /**
     * លុបរូបភាពមួយសន្លឹកតាមរយៈ AJAX
     */
    public function destroyImage($id)
    {
        try {
            // ប្រសិនបើអ្នកមាន ImageService ប្រើវាដើម្បីលុប File និង DB
            $image = RoomImage::findOrFail($id);

            // ប្រើ Trait ឬ Service ដើម្បីលុប File ក្នុង Storage
            if (method_exists($this->roomTypeService, 'deleteFile')) {
                $this->roomTypeService->deleteFile($image->image_path);
            }

            $image->delete();

            return response()->json([
                'success' => true,
                'message' => 'លុបរូបភាពរួចរាល់'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
