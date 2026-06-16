<?php

namespace App\Repositories;

use App\Models\RoomType;
use Illuminate\Pagination\LengthAwarePaginator;

class RoomTypeRepository extends BaseRepository
{
    public function __construct(RoomType $model)
    {
        parent::__construct($model);
    }

    /**
     * ទាញយកបញ្ជីប្រភេទបន្ទប់ ជាមួយការ Search និង Filter តាមលក្ខខណ្ឌស្មុគស្មាញ
     */
    // នៅក្នុង RoomTypeRepository.php
    public function getRoomTypes(array $filters = []): LengthAwarePaginator
    {
        // បន្ថែម withCount('rooms') ដើម្បីបង្ហាញចំនួនបន្ទប់ក្នុងតារាងបញ្ជីតែម្ដង
        $query = $this->model->newQuery()->with(['hotel', 'images'])->withCount('rooms');

        if (!empty($filters['hotel_id'])) {
            $query->where('hotel_id', $filters['hotel_id']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('hotel', function ($h) use ($search) {
                        $h->where('name', 'like', "%{$search}%");
                    });
            });
        }

        return $query->latest()
            ->paginate($filters['per_page'] ?? 4)
            ->withQueryString();
    }

    /**
     * ទាញយកទិន្នន័យប្រភេទបន្ទប់មួយ ជាមួយរូបភាព និងបរិក្ខារទាំងអស់
     */
    public function findRoomTypeDetail($id)
    {
        return $this->model->with(['hotel', 'images', 'facilities'])->findOrFail($id);
    }

    /**
     * រាប់ចំនួនបន្ទប់ដែលមាននៅក្នុងប្រភេទបន្ទប់នេះ
     */
    public function getRoomsCount($roomTypeId)
    {
        $roomType = $this->model->withCount('rooms')->find($roomTypeId);
        return $roomType ? $roomType->rooms_count : 0;
    }
}
