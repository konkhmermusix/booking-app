<?php

namespace App\Repositories;

use App\Models\Promotion;

class PromotionRepository
{
    public function getAll($search = null, $status = null, $perPage = 8)
    {
        return Promotion::with('roomType')
            // ប្រើ Parameter Grouping ដើម្បីការពារ logic conflict ជាមួយ status
            ->when($search, function ($q) use ($search) {
                $q->where(function ($subQuery) use ($search) {
                    $subQuery->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('tag', 'LIKE', "%{$search}%");
                });
            })

            // លក្ខខណ្ឌ status នឹងនៅតែត្រឹមត្រូវជានិច្ច ទោះបីជាមានការ search ក៏ដោយ
            ->when($status !== null && $status !== '', function ($q) use ($status) {
                $q->where('status', $status);
            })

            ->latest()
            // កែសម្រួល perPage ឱ្យខ្លី និងការពារ null
            ->paginate($perPage ?: 8)
            ->withQueryString();
    }

    /**
     * សម្រាប់ប្រើក្នុង Service ដើម្បីទាញយកទិន្នន័យមកឆែកមុននឹង Update ឬ Delete
     */
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
        // យើងប្រើ find ហើ់យទើប delete ដើម្បីឱ្យវាទាត់ Eloquent Events (ប្រសិនបើមាន)
        $promo = $this->getById($id);
        return $promo->delete();
    }
}
