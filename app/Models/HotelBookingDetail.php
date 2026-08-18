<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HotelBookingDetail extends Model
{
    use HasFactory;

    protected $table = 'hotel_booking_details';

    protected $fillable = [
        'hotel_booking_id',
        'room_type_id',
        'room_id',
        'price_at_booking'
    ];

    public function booking()
    {
        return $this->belongsTo(HotelBooking::class, 'hotel_booking_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }
}
