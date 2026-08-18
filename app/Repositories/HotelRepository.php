<?php

namespace App\Repositories;

use App\Models\Hotel;

class HotelRepository
{
    public function getAll($filters = [])
    {
        $query = Hotel::query()->withCount('rooms');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%');
            });
        }

        if (isset($filters['status']) && $filters['status'] !== "") {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate(10);
    }

    public function findById($id)
    {
        return Hotel::findOrFail($id);
    }

    public function create(array $data)
    {
        return Hotel::create($data);
    }

    public function update($id, array $data)
    {
        $hotel = $this->findById($id);
        $hotel->update($data);
        return $hotel;
    }

    public function delete($id)
    {
        $hotel = $this->findById($id);
        return $hotel->delete();
    }
}