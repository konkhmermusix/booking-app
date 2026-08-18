<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = ['hotel_id', 'room_type_id', 'room_number', 'floor', 'status'];

    protected $casts = [
        'gallery' => 'array',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    public function hotelBookings(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(HotelBooking::class, 'hotel_booking_details', 'room_id', 'hotel_booking_id');
    }

    public function meetingBookings():HasMany
    {
        return $this->hasMany(MeetingBooking::class, 'meeting_room_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('status', 1)->latest();
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
