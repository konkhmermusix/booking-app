<?php

namespace App\Services;

use App\Repositories\HotelRepository;
use App\Traits\UploadTrait;

class HotelService
{
    use UploadTrait;

    protected $hotelRepo;

    public function __construct(HotelRepository $hotelRepo)
    {
        $this->hotelRepo = $hotelRepo;
    }

    public function listHotels($filters)
    {
        return $this->hotelRepo->getAll($filters);
    }

    public function storeHotel(array $data)
    {
        if (isset($data['logo'])) {
            $data['logo'] = $this->uploadFile($data['logo'], 'hotels/logos');
        }

        return $this->hotelRepo->create($data);
    }

    public function updateHotel($id, array $data)
    {
        $hotel = $this->hotelRepo->findById($id);

        if (isset($data['logo'])) {
            $data['logo'] = $this->uploadFile($data['logo'], 'hotels/logos', $hotel->logo);
        }

        return $this->hotelRepo->update($id, $data);
    }

    public function deleteHotel($id)
    {
        $hotel = $this->hotelRepo->findById($id);

        // លុបរូបភាពចេញពី Storage មុននឹងលុបទិន្នន័យ
        if ($hotel->logo) {
            $this->deleteFile($hotel->logo);
        }

        return $this->hotelRepo->delete($id);
    }
}
