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
        $data['image'] = $images; // រក្សាទុកជា Array

        return $this->tourRepository->store($data);
    }

    public function updateTour($request, $tour)
    {
        $data = $request->validated();

        // ១. យកបញ្ជីរូបភាពដែលមានស្រាប់ (Default ជា Array ទទេបើគ្មាន)
        $currentImages = is_array($tour->image) ? $tour->image : [];

        // ២. ប្រសិនបើ User ចុចលុបរូបភាពចាស់ (អ្នកត្រូវផ្ញើ Array នៃរូបដែលនៅសល់មកតាម Request)
        if ($request->has('existing_images')) {
            // រកមើលរូបណាដែលបាត់ពី existing_images ហើយលុបវាចេញពី Storage
            $imagesToDelete = array_diff($currentImages, $request->existing_images);
            foreach ($imagesToDelete as $oldImg) {
                $this->deleteFile($oldImg);
            }
            $currentImages = $request->existing_images;
        }

        // ៣. ប្រសិនបើមានការ Upload រូបថ្មីបន្ថែម
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
        if ($tour->image) {
            foreach ($tour->image as $img) {
                $this->deleteFile($img);
            }
        }
        return $this->tourRepository->delete($tour);
    }
}
