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
    public function getRoomTypes(array $filters = []): LengthAwarePaginator
    {
        return $this->model->newQuery()
            // កំណត់ឱ្យទាញយក Relation មកជាមួយ (facilities មកតាមរយៈ $with ក្នុង Model)
            ->with(['hotel', 'images'])

            // 1. Search តាមឈ្មោះប្រភេទបន្ទប់ ឬ ឈ្មោះសណ្ឋាគារ
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $search = $filters['search'];
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhereHas('hotel', function ($h) use ($search) {
                            $h->where('name', 'like', "%{$search}%");
                        });
                });
            })

            // 2. Filter តាមសណ្ឋាគារ (Dropdown)
            ->when(!empty($filters['hotel_id']), function ($query) use ($filters) {
                $query->where('hotel_id', $filters['hotel_id']);
            })

            // 3. Filter តាមចំនួនភ្ញៀវអតិបរមា
            ->when(!empty($filters['max_guests']), function ($query) use ($filters) {
                $query->where('max_guests', '>=', $filters['max_guests']);
            })

            // 4. តម្រៀបទិន្នន័យតាមតម្លៃ (Sort by Price)
            ->when(!empty($filters['sort_price']), function ($query) use ($filters) {
                $query->orderBy('base_price', $filters['sort_price']); // asc ឬ desc
            }, function ($query) {
                $query->latest(); // បើគ្មានការ sort ទេ យកអាថ្មីបំផុតមកមុន
            })

            // 5. បែងចែកទំព័រ (Pagination)
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
