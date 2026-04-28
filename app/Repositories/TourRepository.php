<?php

namespace App\Repositories;

use App\Models\Tour;

class TourRepository
{

    public function getAll($request)
    {
        $query = Tour::query();

        // បន្ថែមការ search ឱ្យកាន់តែឆ្លាត
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        return $query->latest()->paginate(8);
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
