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
        // បើអត់មាន status ផ្ញើមកពី Frontend (ករណី Unchecked) ឱ្យវាទៅជា 0
        // បើមាន ឱ្យបំប្លែងទៅជា integer (1) ដើម្បីកុំឱ្យមានបញ្ហា string
        $data['status'] = isset($data['status']) ? 1 : 0;

        if (isset($data['image_path'])) {
            $data['image_path'] = $this->uploadFile($data['image_path'], 'promotions');
        }

        return $this->repo->create($data);
    }

    public function updatePromotion($id, $data)
    {
        $promo = $this->repo->getById($id);

        $data['status'] = isset($data['status']) ? 1 : 0;

        if (isset($data['image_path'])) {
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
