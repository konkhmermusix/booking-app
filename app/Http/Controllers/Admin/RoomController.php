<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomRequest;
use App\Services\RoomService;
use App\Models\Hotel;
use App\Models\RoomType;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoomController extends Controller
{
    protected $roomService;

    public function __construct(RoomService $roomService)
    {
        $this->roomService = $roomService;
    }

    public function index(Request $request)
    {
        $rooms = $this->roomService->getAllRooms($request->all());

        if ($request->ajax()) {
            return view('admin.rooms.partials.rooms_list', compact('rooms'))->render();
        }

        // ៣. ទាញទិន្នន័យសម្រាប់ Select Options ក្នុង Modals
        $hotels = Hotel::where('status', '1')->get();
        $roomTypes = RoomType::all();

        return view('admin.rooms.index', compact('rooms', 'hotels', 'roomTypes'));
    }

    /**
     * យើងប្ដូរមកប្រើ RoomRequest ដើម្បីឱ្យ Controller ខ្លីជាងមុន
     */
    public function store(RoomRequest $request)
    {
        try {
            // ហៅទៅកាន់ Service ដើម្បី Handle ការរក្សាទុក
            $this->roomService->storeRoom($request->validated());

            return back()->with('success', 'បន្ថែមបន្ទប់ថ្មីបានជោគជ័យ!');
        } catch (\Exception $e) {
            Log::error("Room Store Error: " . $e->getMessage());
            return back()->with('error', 'មានបញ្ហាបច្ចេកទេស មិនអាចបង្កើតបន្ទប់បានទេ។');
        }
    }

    public function update(RoomRequest $request, $id)
    {
        try {
            $this->roomService->updateRoom($id, $request->validated());

            return back()->with('success', 'ធ្វើបច្ចុប្បន្នភាពបន្ទប់ជោគជ័យ!');
        } catch (\Exception $e) {
            Log::error("Room Update Error: " . $e->getMessage());
            return back()->with('error', 'មិនអាចធ្វើបច្ចុប្បន្នភាពបានទេ។');
        }
    }

    public function destroy($id)
    {
        try {
            // Service នឹង Check លក្ខខណ្ឌ (ដូចជា Status booked) មុននឹងលុប
            $result = $this->roomService->deleteRoom($id);

            if (!$result) {
                return back()->with('error', 'មិនអាចលុបបន្ទប់ដែលមានភ្ញៀវកំពុងស្នាក់នៅបានទេ។');
            }

            return back()->with('success', 'លុបបន្ទប់បានជោគជ័យ!');
        } catch (\Exception $e) {
            Log::error("Room Delete Error: " . $e->getMessage());
            return back()->with('error', 'មិនអាចលុបបានទេ ព្រោះវាអាចពាក់ព័ន្ធនឹងទិន្នន័យផ្សេងទៀត។');
        }
    }
}
