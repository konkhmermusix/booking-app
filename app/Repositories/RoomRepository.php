<?php

namespace App\Repositories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Builder;

class RoomRepository extends BaseRepository
{
    public function __construct(Room $model)
    {
        parent::__construct($model);
    }

    /**
     * ទាញយកទិន្នន័យបន្ទប់ ជាមួយការ Search, Filter, Status Sorting និង Pagination
     */
    public function getAvailableRooms(array $filters = [])
    {
        return $this->model->newQuery()
            ->with(['roomType.images', 'hotel', 'roomType.facilities'])

            // 1. Search តាមលេខបន្ទប់ ឬឈ្មោះសណ្ឋាគារ
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $search = $filters['search'];
                $query->where(function ($q) use ($search) {
                    $q->where('room_number', 'like', "%{$search}%")
                        ->orWhereHas('hotel', function ($h) use ($search) {
                            $h->where('name', 'like', "%{$search}%");
                        });
                });
            })

            // 2. Filter តាមស្ថានភាព (Available, Booked, Maintenance)
            ->when(!empty($filters['status']), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })

            // 3. Filter តាមប្រភេទបន្ទប់
            ->when(!empty($filters['room_type_id']), function ($query) use ($filters) {
                $query->where('room_type_id', $filters['room_type_id']);
            })

            // 4. Filter តាមសណ្ឋាគារ
            ->when(!empty($filters['hotel_id']), function ($query) use ($filters) {
                $query->where('hotel_id', $filters['hotel_id']);
            })

            // 5. Filter តាមជាន់
            ->when(!empty($filters['floor']), function ($query) use ($filters) {
                $query->where('floor', $filters['floor']);
            })

            // 6. តម្រៀបទិន្នន័យ (Sorting)
            // បើមានការបញ្ជូន sort_status មក (asc/desc) វានឹងរៀបតាមហ្នឹង បើមិនមានទេគឺរៀបតាមថ្មីបំផុត
            ->when(!empty($filters['sort_status']), function ($query) use ($filters) {
                $query->orderBy('status', $filters['sort_status']);
            }, function ($query) {
                $query->latest();
            })

            // 7. បែងចែកទំព័រ និងរក្សាទុកលក្ខខណ្ឌ Search ក្នុង URL
            ->paginate($filters['per_page'] ?? 4)
            ->withQueryString();
    }
    /**
     * ទាញយកបន្ទប់តាមរយៈ ID របស់ RoomType
     */
    public function getByRoomType($roomTypeId)
    {
        return $this->model->where('room_type_id', $roomTypeId)
            ->where('status', 'available')
            ->get();
    }

    /**
     * ឆែកមើលថាតើបន្ទប់ជាក់លាក់ណាមួយទំនេរឬអត់
     */
    public function isRoomAvailable($roomId): bool
    {
        return $this->model->where('id', $roomId)
            ->where('status', 'available')
            ->exists();
    }

    /**
     * ប្តូរស្ថានភាពបន្ទប់ (ឧទាហរណ៍៖ ពី available ទៅ booked)
     */
    public function updateStatus($roomId, $status)
    {
        return $this->update($roomId, ['status' => $status]);
    }
}
