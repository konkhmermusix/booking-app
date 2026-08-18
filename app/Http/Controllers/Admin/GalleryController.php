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
        $hotel = Hotel::with('galleries')->first();

        if (!$hotel) {
            return back()->with('error', 'មិនទាន់មានទិន្នន័យសណ្ឋាគារនៅឡើយទេ');
        }

        $totalPhotos = $hotel->galleries()->count();
        $activePhotos = $hotel->galleries()->where('is_active', 1)->count();
        $inactivePhotos = $hotel->galleries()->where('is_active', 0)->count();

        return view('admin.galleries.index', compact('hotel', 'totalPhotos', 'activePhotos', 'inactivePhotos'));
    }

    public function store(GalleryRequest $request)
    {
        $this->galleryService->uploadGallery($request);
        return back()->with('success', 'រូបភាពត្រូវបានបញ្ចូលដោយជោគជ័យ');
    }

    /**
     * ធ្វើបច្ចុប្បន្នភាព Status (is_active) របស់រូបភាព
     */
    public function update(Request $request, Gallery $gallery)
    {
        $this->galleryService->updateGallery($request, $gallery);
        return back()->with('success', 'បានកែប្រែរូបភាពដោយជោគជ័យ');
    }

    public function destroy(Gallery $gallery)
    {
        $this->galleryService->deleteGallery($gallery);
        return back()->with('success', 'លុបរូបភាពរួចរាល់');
    }
}
