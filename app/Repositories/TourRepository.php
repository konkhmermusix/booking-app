<?php

namespace App\Repositories;

use App\Models\Tour;

class TourRepository
{
    public function getAll($request)
    {
        $query = Tour::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->status !== null) {
            $query->where('status', $request->status);
        }

        return $query->latest()->paginate(10);
    }

    public function store($data)
    {
        return Tour::create($data);
    }

    public function update($tour, $data)
    {
        $tour->update($data);
        return $tour;
    }

    public function delete($tour)
    {
        return $tour->delete();
    }
}