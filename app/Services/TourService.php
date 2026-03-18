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

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadFile($request->file('image'), 'tours');
        }

        return $this->tourRepository->store($data);
    }

    public function updateTour($request, $tour)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadFile(
                $request->file('image'),
                $tour->image,
                'tours'
            );
        }

        return $this->tourRepository->update($tour, $data);
    }

    public function deleteTour($tour)
    {
        $this->deleteFile($tour->image);

        return $this->tourRepository->delete($tour);
    }
}
