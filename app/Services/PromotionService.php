<?php

namespace App\Services;

use App\Repositories\PromotionRepository;
use App\Traits\UploadTrait;
use Illuminate\Support\Facades\Storage;

class PromotionService
{
    use UploadTrait;

    protected $repo;

    public function __construct(PromotionRepository $repo)
    {
        $this->repo = $repo;
    }

    public function listPromotions($search, $status)
    {
        return $this->repo->getAll($search, $status);
    }

    public function storePromotion($data)
    {
        if (isset($data['image_path'])) {
            // ប្រើ Method ពីក្នុង Trait (ឧបមាថាឈ្មោះ uploadFile)
            $data['image_path'] = $this->uploadFile($data['image_path'], 'promotions');
        }

        return $this->repo->create($data);
    }

    // public function updatePromotion($id, $data)
    // {
    //     $promotion = $this->repo->getById($id);

    //     if (isset($data['image_path'])) {
    //         // លុបរូបភាពចាស់ចោលមុននឹង Upload រូបថ្មី
    //         if ($promotion->image_path) {
    //             $this->deleteFile($promotion->image_path);
    //         }
    //         // Upload រូបភាពថ្មី
    //         $data['image_path'] = $this->uploadFile($data['image_path'], 'promotions');
    //     }

    //     return $this->repo->update($id, $data);
    // }

    // public function deletePromotion($id)
    // {
    //     $promotion = $this->repo->getById($id);

    //     // លុបរូបភាពចេញពី Storage មុននឹងលុបទិន្នន័យពី Database
    //     if ($promotion->image_path) {
    //         $this->deleteFile($promotion->image_path);
    //     }

    //     return $this->repo->delete($id);
    // }

    public function updatePromotion($id, $data)
    {
        $promo = $this->repo->getById($id);
        if (isset($data['image_path'])) {
            // លុបរូបចាស់ បើមានរូបថ្មីមកជំនួស
            if ($promo->image_path) {
                $this->deleteFile($promo->image_path);
            }
            $data['image_path'] = $this->uploadFile($data['image_path'], 'promotions');
        }
        return $this->repo->update($id, $data);
    }

    public function deletePromotion($id)
    {
        $promo = $this->repo->getById($id);
        if ($promo->image_path) {
            $this->deleteFile($promo->image_path);
        }
        return $this->repo->delete($id);
    }
}
