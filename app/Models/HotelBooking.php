<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelBooking extends Model
{

    protected $table = 'hotel_bookings';

    // Mass assignable fields

    protected $fillable = [
        'user_id',
        'hotel_id',
        'room_id',
        'check_in',
        'check_out',
        'total_price',
        'status',
        'booking_code',
        'check_in_time',
        'check_out_time',
        'special_requests'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Booking ត្រូវបានធ្វើនៅ Hotel មួយ
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    // Booking ត្រូវបាន assign ទៅ Room មួយ
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function details()
    {
        return $this->hasMany(BookingDetail::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Optional: Accessor for number of nights
    public function getNightsAttribute(): int
    {
        return $this->check_in && $this->check_out
            ? \Carbon\Carbon::parse($this->check_in)->diffInDays($this->check_out)
            : 0;
    }
}
