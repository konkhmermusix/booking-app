<?php

namespace App\Services;

use App\Models\Slideshow;
use Illuminate\Support\Facades\Storage;

class SlideshowService
{
    public function getAllSlides()
    {
        return Slideshow::orderBy('order_column', 'asc')->paginate(4);
    }

    public function createSlide(array $data)
    {
        if (isset($data['image'])) {
            $data['image_path'] = $data['image']->store('slides', 'public');
        }
        return Slideshow::create($data);
    }

    public function updateSlide(Slideshow $slide, array $data)
    {
        if (isset($data['image'])) {
            if ($slide->image_path) Storage::disk('public')->delete($slide->image_path);
            $data['image_path'] = $data['image']->store('slides', 'public');
        }
        return $slide->update($data);
    }

    public function deleteSlide(Slideshow $slide)
    {
        if ($slide->image_path) Storage::disk('public')->delete($slide->image_path);
        return $slide->delete();
    }
}
