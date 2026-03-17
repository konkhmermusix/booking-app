<?php

namespace App\Services;

use App\Repositories\HotelRepository;
use App\Traits\UploadTrait;
use Illuminate\Support\Facades\DB;

class HotelService
{
    use UploadTrait;

    public function __construct(protected HotelRepository $repo) {}

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            if (isset($data['logo'])) {
                $data['logo'] = $this->uploadFile($data['logo'], 'hotels/logos');
            }
            return $this->repo->create($data);
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $hotel = $this->repo->find($id);
            if (isset($data['logo'])) {
                if ($hotel->logo) $this->deleteFile($hotel->logo);
                $data['logo'] = $this->uploadFile($data['logo'], 'hotels/logos');
            }
            return $this->repo->update($id, $data);
        });
    }

    public function delete($id)
    {
        $hotel = $this->repo->find($id);
        if ($hotel->logo) $this->deleteFile($hotel->logo);
        return $this->repo->delete($id);
    }
    
}