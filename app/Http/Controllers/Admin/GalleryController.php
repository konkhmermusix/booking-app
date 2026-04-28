<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\GalleryRequest;
use App\Models\Gallery;
use App\Services\GalleryService;
use App\Models\Hotel;

class GalleryController extends Controller
{

    protected $galleryService;

    public function __construct(GalleryService $galleryService)
    {
        $this->galleryService = $galleryService;
    }

    public function index()
    {
        // ឧទាហរណ៍៖ ទាញយកសណ្ឋាគារដំបូងគេ ឬតាម ID ដែលអ្នកចង់គ្រប់គ្រង
        // ប្រសិនបើអ្នកមាន Hotel ច្រើន អ្នកអាចបោះ ID តាម Query String ក៏បាន
        $hotel = Hotel::with('galleries')->first();

        if (!$hotel) {
            return back()->with('error', 'មិនទាន់មានទិន្នន័យសណ្ឋាគារនៅឡើយទេ!');
        }

        return view('admin.galleries.index', compact('hotel'));
    }

    public function store(GalleryRequest $request)
    {
        $this->galleryService->uploadGallery($request);
        return back()->with('success', 'រូបភាពត្រូវបានបញ្ចូលដោយជោគជ័យ!');
    }

    /**
     * ធ្វើបច្ចុប្បន្នភាព Status (is_active) របស់រូបភាព
     */
    public function update(Request $request, Gallery $gallery)
    {
        // ទទួលយកតម្លៃ is_active ពី Form (០ ឬ ១)
        $status = $request->input('is_active');

        // ធ្វើបច្ចុប្បន្នភាពក្នុង Database
        $gallery->update([
            'is_active' => $status
        ]);

        // ត្រឡប់ទៅកាន់ទំព័រដើមវិញជាមួយសារជោគជ័យ
        return back()->with('success', 'បានកែប្រែស្ថានភាពរូបភាពដោយជោគជ័យ!');
    }

    public function destroy(Gallery $gallery)
    {
        $this->galleryService->deleteGallery($gallery);
        return back()->with('success', 'លុបរូបភាពរួចរាល់!');
    }
}
