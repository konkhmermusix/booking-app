<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelBooking extends Model
{

    protected $table = 'hotel_bookings';

    protected $fillable = [
        'booking_code',
        'booking_type',
        'user_id',
        'hotel_id',
        'room_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'check_in',
        'check_out',
        'check_in_time',
        'check_out_time',
        'total_price',
        'payment_method',
        'special_requests',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function details()
    {
        return $this->hasMany(HotelBookingDetail::class, 'hotel_booking_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function getNightsAttribute(): int
    {
        return $this->check_in && $this->check_out
            ? \Carbon\Carbon::parse($this->check_in)->diffInDays($this->check_out)
            : 0;
    }

    public static function statusLabel(string $status): string
    {
        return match ($status) {
            'pending'     => 'រង់ចាំពិនិត្យ',
            'confirmed'   => 'បានបញ្ជាក់',
            'completed'   => 'បានបញ្ចប់',
            'checked_in'  => 'ចូលស្នាក់នៅ',
            'checked_out', 'completed' => 'ចាកចេញ',
            'cancelled'   => 'បោះបង់',
            default       => $status,
        };
    }
}
