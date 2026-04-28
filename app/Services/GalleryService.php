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
                // Upload តាម Trait
                $path = $this->uploadFile($file, 'gallery');

                // រក្សាទុកតាម Repo
                $this->repo->create([
                    'hotel_id' => $hotel_id,
                    'image' => $path,
                    'is_active' => $status
                ]);
            }
        }
    }

    public function deleteGallery($gallery)
    {
        $this->deleteFile($gallery->image);
        return $this->repo->delete($gallery);
    }
}
