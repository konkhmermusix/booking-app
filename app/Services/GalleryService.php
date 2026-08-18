<?php

namespace App\Services;

use App\Repositories\GalleryRepository;
use App\Traits\UploadTrait;

class GalleryService
{
    use UploadTrait;

    protected $repo;

    public function __construct(GalleryRepository $repo)
    {
        $this->repo = $repo;
    }

    public function uploadGallery($request)
    {
        $hotel_id = $request->hotel_id;
        $status = $request->has('is_active') ? 1 : 1;

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $this->uploadFile($file, 'gallery');
                
                $this->repo->create([
                    'hotel_id' => $hotel_id,
                    'image' => $path,
                    'is_active' => $status
                ]);
            }
        }
    }

    public function updateGallery($request, $gallery)
    {
        $data = [];

        if ($request->has('is_active')) {
            $data['is_active'] = (int) $request->input('is_active');
        }

        if ($request->hasFile('image')) {
            if (!empty($gallery->image)) {
                $this->deleteFile($gallery->image);
            }
            $data['image'] = $this->uploadFile($request->file('image'), 'gallery');
        }

        if (!empty($data)) {
            $gallery->update($data);
        }

        return $gallery;
    }

    public function deleteGallery($gallery)
    {
        $this->deleteFile($gallery->image);
        return $this->repo->delete($gallery);
    }
}
