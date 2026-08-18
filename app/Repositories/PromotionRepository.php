<?php

namespace App\Repositories;

use App\Models\Promotion;

class PromotionRepository
{
    public function getAll($search = null, $status = null, $perPage = 8)
    {
        return Promotion::with('roomType')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($subQuery) use ($search) {
                    $subQuery->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('tag', 'LIKE', "%{$search}%");
                });
            })

            ->when($status !== null && $status !== '', function ($q) use ($status) {
                $q->where('status', $status);
            })

            ->latest()
            ->paginate($perPage ?: 8)
            ->withQueryString();
    }

    public function getById($id)
    {
        return Promotion::findOrFail($id);
    }

    public function create(array $data)
    {
        return Promotion::create($data);
    }

    public function update($id, array $data)
    {
        $promo = $this->getById($id);
        $promo->update($data);
        return $promo;
    }

    public function delete($id)
    {
        $promo = $this->getById($id);
        return $promo->delete();
    }
}
