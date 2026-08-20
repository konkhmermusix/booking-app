<?php

namespace App\Services;

use App\Repositories\RoomTypeRepository;
use App\Traits\UploadTrait;
use App\Models\RoomType;
use App\Models\RoomImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoomTypeService
{
    use UploadTrait;

    protected $repository;

    public function __construct(RoomTypeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * ទាញយក RoomType ទាំងអស់ជាមួយ Pagination & Filters
     */
    public function getAllRoomType($filters = [])
    {
        return $this->repository->getRoomTypes($filters);
    }

    /**
     * បង្កើតប្រភេទបន្ទប់ថ្មី (Multi-tables transaction)
     */
    public function storeRoomType(array $data)
    {
        return DB::transaction(function () use ($data) {
            $roomType = $this->repository->create($data);

            if (!empty($data['facilities'])) {
                $roomType->facilities()->sync($data['facilities']);
            }

            if (isset($data['images'])) {
                foreach ($data['images'] as $image) {
                    $path = $this->uploadFile($image, 'room_types');
                    $roomType->images()->create(['image_path' => $path]);
                }
            }

            return $roomType;
        });
    }

    /**
     * កែប្រែទិន្នន័យប្រភេទបន្ទប់
     */
    public function updateRoomType($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $roomType = $this->repository->find($id);
            if (!$roomType) throw new \Exception("Room Type not found.");

            $this->repository->update($id, $data);

            if (isset($data['facilities'])) {
                $roomType->facilities()->sync($data['facilities']);
            }

            if (!empty($data['images'])) {
                foreach ($data['images'] as $image) {
                    $path = $this->uploadFile($image, 'room_types');
                    $roomType->images()->create(['image_path' => $path]);
                }
            }
            return $roomType->load(['facilities', 'images']);
        });
    }

    /**
     * លុបប្រភេទបន្ទប់ រួមទាំងរូបភាពក្នុង Storage និង Relations
     */
    public function deleteRoomType($id)
    {
        return DB::transaction(function () use ($id) {
            $roomType = $this->repository->find($id);

            if (!$roomType) return false;

            foreach ($roomType->images as $image) {
                $this->deleteFile($image->image_path);
                $image->delete(); // លុប Record ក្នុង Table room_images
            }

            $roomType->facilities()->detach();

            return $this->repository->delete($id);
        });
    }

    // បន្ថែមក្នុង RoomTypeService.php
    public function deleteRoomImage($imageId)
    {
        $image = RoomImage::find($imageId);
        if ($image) {
            $this->deleteFile($image->image_path);
            return $image->delete();
        }
        return false;
    }
}
