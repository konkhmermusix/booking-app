<?php

namespace App\Services;

use App\Repositories\TourRepository;
use App\Traits\UploadTrait;

class TourService
{
    use UploadTrait;

    protected $tourRepository;

    public function __construct(TourRepository $tourRepository)
    {
        $this->tourRepository = $tourRepository;
    }

    public function getTours($request)
    {
        return $this->tourRepository->getAll($request);
    }

    public function createTour($request)
    {
        $data = $request->validated();
        $images = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $images[] = $this->uploadFile($file, 'tours');
            }
        }
        $data['image'] = $images;

        return $this->tourRepository->store($data);
    }

    public function updateTour($request, $tour)
    {
        $data = $request->validated();
        $currentImages = is_array($tour->image) ? $tour->image : [];

        if ($request->has('existing_images')) {
            $imagesToDelete = array_diff($currentImages, $request->existing_images);
            foreach ($imagesToDelete as $oldImg) {
                $this->deleteFile($oldImg);
            }
            $currentImages = $request->existing_images;
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $currentImages[] = $this->uploadFile($file, 'tours');
            }
        }

        $data['image'] = $currentImages;
        return $this->tourRepository->update($tour, $data);
    }

    public function deleteTour($tour)
    {
        $images = is_array($tour->image) ? $tour->image : (is_string($tour->image) ? json_decode($tour->image, true) : []);
        if (is_array($images)) {
            foreach ($images as $img) {
                if ($img) {
                    $this->deleteFile($img);
                }
            }
        }
        return $this->tourRepository->delete($tour);
    }
}
