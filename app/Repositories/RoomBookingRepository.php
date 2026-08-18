<?php

namespace App\Repositories;

use App\Models\HotelBooking;
use Illuminate\Support\Str;

class RoomBookingRepository extends BaseRepository
{
    public function __construct(HotelBooking $model)
    {
        parent::__construct($model);
    }

    public function getAllBookings(array $filters = [])
    {
        return $this->model->newQuery()
            ->with(['user', 'hotel', 'room.roomType', 'details.room.roomType', 'details.roomType', 'payment'])
            ->when(!empty($filters['search']), function ($q) use ($filters) {
                $search = $filters['search'];

                $q->where(function ($query) use ($search) {
                    $query->where('booking_code', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($uQ) use ($search) {
                            $uQ->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
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

            ->when(!empty($filters['start']), function ($q) use ($filters) {
                $q->whereDate('check_in', '>=', $filters['start']);
            })
            ->when(!empty($filters['end']), function ($q) use ($filters) {
                $q->whereDate('check_out', '<=', $filters['end']);
            })
            ->latest()
            ->paginate($filters['per_page'] ?? 10)
            ->withQueryString();
    }

    public function generateBookingCode()
    {
        return 'PNT-' . strtoupper(Str::random(6));
    }
}
