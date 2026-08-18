<?php

namespace App\Repositories;

use App\Models\HotelBooking;
use Illuminate\Support\Str;

class BookingRepository extends BaseRepository
{
    public function __construct(HotelBooking $model)
    {
        parent::__construct($model);
    }

    public function getAllBookings(array $filters = [])
    {
        return $this->model->newQuery()
            ->with(['user', 'hotel', 'room.roomType'])
            ->when(!empty($filters['search']), function ($q) use ($filters) {
                $search = $filters['search'];

                $q->where(function ($query) use ($search) {
                    // ស្វែងរកតាម លេខកូដការកក់ (Booking Code)
                    $query->where('booking_code', 'like', "%{$search}%")

                        // ស្វែងរកតាម ឈ្មោះភ្ញៀវ (User Table)
                        ->orWhereHas('user', function ($uQ) use ($search) {
                            $uQ->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })

                        // ស្វែងរកតាម លេខបន្ទប់ ឬ ឈ្មោះបន្ទប់ (Room Table)
                        ->orWhereHas('room', function ($rQ) use ($search) {
                            $rQ->where('room_number', 'like', "%{$search}%")
                                ->orWhereHas('roomType', function ($rtQ) use ($search) {
                                    $rtQ->where('name', 'like', "%{$search}%");
                                });
                        });
                });
            })
            ->when(!empty($filters['status']), function ($q) use ($filters) {
                $q->where('status', $filters['status']);
            })
            // បន្ថែម Filter តាមកាលបរិច្ឆេទ (បើមានការបញ្ជូន start/end មក)
            ->when(!empty($filters['start']), function ($q) use ($filters) {
                $q->whereDate('check_in', '>=', $filters['start']);
            })
            ->when(!empty($filters['end']), function ($q) use ($filters) {
                $q->whereDate('check_out', '<=', $filters['end']);
            })
            ->latest()
            ->paginate($filters['per_page'] ?? 100)
            ->withQueryString();
    }

    public function generateBookingCode()
    {
        return 'PNT-' . strtoupper(Str::random(6));
    }
}
