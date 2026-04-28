<?php

namespace App\Services;

use App\Repositories\AboutRepository;
use App\Traits\UploadTrait;

class AboutService
{
    use UploadTrait;

    protected $repository;

    public function __construct(AboutRepository $repository)
    {
        $this->repository = $repository;
    }

    public function listAll()
    {
        return $this->repository->getAll();
    }

    public function storeData($data)
    {
        if (isset($data['image'])) {
            $data['image'] = $this->uploadFile($data['image'], 'about');
        }
        return $this->repository->create($data);
    }

    public function updateData($id, $data)
    {
        $content = $this->repository->find($id);
        if (isset($data['image'])) {
            if ($content->image) $this->deleteFile($content->image);
            $data['image'] = $this->uploadFile($data['image'], 'about');
        }
        return $this->repository->update($id, $data);
    }

    public function deleteData($id)
    {
        $content = $this->repository->find($id);
        if ($content->image) $this->deleteFile($content->image);
        return $this->repository->delete($id);
    }
}
