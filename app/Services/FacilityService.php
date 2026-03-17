<?php

namespace App\Services;

use App\Models\Facility;

class FacilityService
{
    /**
     * ទាញយកបញ្ជី Facility ទាំងអស់ដែល Active
     */
    public function getAllActive()
    {
        return Facility::where('is_active', true)
            ->orderBy('name', 'asc')
            ->paginate(5);
    }

    /**
     * បង្កើត Facility ថ្មី
     */
    // public function createFacility(array $data)
    // {
    //     return Facility::create([
    //         'name' => $data['name'],
    //         'icon' => $data['icon'] ?? null,
    //         'type' => $data['type'] ?? 'room',
    //         'is_active' => isset($data['is_active']) ? (bool)$data['is_active'] : true,
    //     ]);
    // }

    public function createFacility(array $data)
    {
        return Facility::create([
            'name'      => $data['name'],
            'icon'      => $data['icon'] ?? null,
            'type'      => $data['type'],
            'is_active' => $data['is_active'], 
        ]);
    }

    /**
     * បច្ចុប្បន្នភាព Facility
     */
    public function updateFacility(Facility $facility, array $data)
    {
        return $facility->update($data);
    }

}
