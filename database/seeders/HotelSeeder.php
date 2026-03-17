<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\RoomType;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        // បង្កើតសណ្ឋាគារទី ១
        $hotel1 = Hotel::create([
            'name' => 'Sokha Palace Hotel',
            'description' => 'សណ្ឋាគារលំដាប់ផ្កាយ ៥ នៅកណ្តាលក្រុងភ្នំពេញ',
            'address' => 'ភ្នំពេញ, កម្ពុជា',
            'phone' => '023 123 456',
            'email' => 'info@sokha.com',
            'status' => 1
        ]);

        // បន្ថែមប្រភេទបន្ទប់ឱ្យសណ្ឋាគារទី ១
        RoomType::create([
            'hotel_id' => $hotel1->id,
            'name' => 'Single Room',
            'description' => 'បន្ទប់គេងទោល ស្អាត និងមានផាសុកភាព',
            'max_guests' => 1,
            'base_price' => 25.00
        ]);

        RoomType::create([
            'hotel_id' => $hotel1->id,
            'name' => 'VIP Suite',
            'description' => 'បន្ទប់ VIP ធំទូលាយ ជាមួយទិដ្ឋភាពទីក្រុង',
            'max_guests' => 2,
            'base_price' => 55.00
        ]);

        // បង្កើតសណ្ឋាគារទី ២
        Hotel::create([
            'name' => 'Siem Reap Resort',
            'description' => 'សម្រាកកាយនៅជិតប្រាសាទអង្គរវត្ត',
            'address' => 'សៀមរាប, កម្ពុជា',
            'phone' => '063 999 888',
            'status' => 1
        ]);
    }
}
