<?php

namespace App\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Hotel;

class HotelRepository
{
    public function getAll(array $filters = [])
    {
        return Hotel::query()
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->where('name', 'LIKE', '%' . $filters['search'] . '%');
            })

            // ប្រើ strlen ដើម្បីឱ្យវាចាប់យកតម្លៃ 0 បាន (បើប្រើ !empty($filters['status']) វានឹងរំលងលេខ 0)
            ->when(isset($filters['status']) && $filters['status'] !== '', function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })

            ->latest()
            ->paginate($filters['per_page'] ?? 4)
            ->withQueryString();
    }

    public function find($id)
    {
        return Hotel::findOrFail($id);
    }

    /**
     * បន្ថែម Method នេះសម្រាប់កែសម្រួល (update)
     */
    public function update($id, array $data)
    {
        $hotel = $this->find($id);
        $hotel->update($data);
        return $hotel;
    }

    public function findById($id)
    {
        return Hotel::findOrFail($id);
    }

    public function create(array $data)
    {
        return Hotel::create($data);
    }

    public function delete($id)
    {
        $hotel = $this->findById($id);
        return $hotel->delete();
    }
}
