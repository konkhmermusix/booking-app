<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HotelRequest;
use App\Services\HotelService;
use App\Repositories\HotelRepository;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function __construct(
        protected HotelService $service,
        protected HotelRepository $repo
    ) {}

    public function index(Request $request)
    {
        // បោះ request ទាំងអស់ (search, status, etc.) ទៅឱ្យ Repo
        $hotels = $this->repo->getAll($request->all());

        return view('admin.hotels.index', compact('hotels'));
    }
    

    public function store(HotelRequest $request)
    {
        $this->service->store($request->validated());
        return back()->with('success', 'បង្កើតជោគជ័យ!');
    }

    public function update(HotelRequest $request, $id)
    {
        $this->service->update($id, $request->validated());
        return back()->with('success', 'កែសម្រួលជោគជ័យ!');
    }

    public function destroy($id)
    {
        try {
            $this->service->delete($id);
            return back()->with('success', 'លុបសណ្ឋាគារបានជោគជ័យ!');
        } catch (\Exception $e) {
            // ប្រសិនបើសណ្ឋាគារមានបន្ទប់ ឬទិន្នន័យផ្សេងជាប់ពាក់ព័ន្ធ វានឹងចូលមកទីនេះ
            return back()->with('error', 'មិនអាចលុបបានទេ! សណ្ឋាគារនេះអាចមានទិន្នន័យបន្ទប់ជាប់ពាក់ព័ន្ធ។');
        }
    }
}