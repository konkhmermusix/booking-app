<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetingBooking extends Model
{
    protected $table = 'meeting_bookings';

    protected $fillable = [
        'booking_code',
        'booking_type',
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'meeting_room_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'total_hours',
        'total_price',
        'payment_method',
        'attendees_count',
        'setup_style',
        'special_requests',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'total_hours' => 'decimal:2',
        'total_price' => 'decimal:2',
        'attendees_count' => 'integer',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'meeting_room_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'meeting_booking_id');
    }
}
