<?php

namespace App\Repositories;

use App\Models\Gallery;

class GalleryRepository
{
    public function create(array $data)
    {
        return Gallery::create($data);
    }

    public function delete($gallery)
    {
        return $gallery->delete();
    }

    public function updateStatus($gallery, $status)
    {
        return $gallery->update(['is_active' => $status]);
    }
}
