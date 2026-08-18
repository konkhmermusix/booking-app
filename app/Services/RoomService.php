<?php

namespace App\Services;

use App\Repositories\RoomRepository;
use App\Traits\UploadTrait;

class RoomService
{
    use UploadTrait;

    protected $repository;

    public function __construct(RoomRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * ទាញយកបន្ទប់ទាំងអស់ជាមួយ Pagination (ល្អសម្រាប់ Admin Page)
     */
    public function getAllRooms($filters = [])
    {
        return $this->repository->getAvailableRooms($filters);
    }


    /**
     * បង្កើតបន្ទប់ថ្មី
     */
    public function storeRoom($data)
    {
        if (isset($data['image'])) {
            $data['image_path'] = $this->uploadFile($data['image'], 'rooms');
        }

        return $this->repository->create($data);
    }

    /**
     * កែប្រែទិន្នន័យបន្ទប់
     */
    public function updateRoom($id, $data)
    {
        $room = $this->repository->find($id);

        if (isset($data['image'])) {
            $data['image_path'] = $this->uploadFile($data['image'], 'rooms', $room->image_path);
        }

        return $this->repository->update($id, $data);
    }

    /**
     * លុបបន្ទប់ និងរូបភាពចេញពី Storage
     */
    public function deleteRoom($id)
    {
        $room = $this->repository->find($id);

        if ($room->status === 'booked') {
            return false;
        }

        if (isset($room->image_path)) {
            $this->deleteFile($room->image_path);
        }

        return $this->repository->delete($id);
    }

    /**
     * ប្តូរស្ថានភាពបន្ទប់ (ឧទាហរណ៍៖ ពេលភ្ញៀវ Check-out រួចត្រូវការសំអាត)
     */
    public function changeRoomStatus($id, $status)
    {
        return $this->repository->updateStatus($id, $status);
    }
}
