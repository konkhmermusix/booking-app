<?php

namespace App\Services;

use App\Repositories\RoomTypeRepository;
use App\Traits\UploadTrait;
use App\Models\RoomType;
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
            // ១. បង្កើត Room Type
            $roomType = $this->repository->create($data);

            // ២. ភ្ជាប់គ្រឿងបរិក្ខារ (Pivot Table)
            if (!empty($data['facilities'])) {
                $roomType->facilities()->sync($data['facilities']);
            }

            // ៣. រក្សាទុករូបភាពច្រើនសន្លឹក
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

            // ១. Update ទិន្នន័យមេ
            $this->repository->update($id, $data);

            // ២. Sync គ្រឿងបរិក្ខារ (លុបអាចាស់ ថែមអាថ្មីតាមការដូរ)
            if (isset($data['facilities'])) {
                $roomType->facilities()->sync($data['facilities']);
            }

            // ៣. បន្ថែមរូបភាពថ្មីៗ (បើមាន)
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
     * លុបប្រភេទបន្ទប់ រួមទាំងរូបភាពក្នុង Storage និង Relations
     */
    public function deleteRoomType($id)
    {
        return DB::transaction(function () use ($id) {
            $roomType = $this->repository->find($id);

            if (!$roomType) return false;

            // ១. លុបរូបភាពចេញពី Physical Storage (Public folder)
            foreach ($roomType->images as $image) {
                $this->deleteFile($image->image_path);
                $image->delete(); // លុប Record ក្នុង Table room_images
            }

            // ២. ផ្ដាច់ទំនាក់ទំនងជាមួយ Facilities (Pivot Table)
            $roomType->facilities()->detach();

            // ៣. លុប Room Type (មេ)
            return $this->repository->delete($id);
        });
    }
}
