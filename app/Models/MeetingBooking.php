<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetingBooking extends Model
{
    // ១. កំណត់ឈ្មោះតារាងឱ្យត្រូវទៅនឹង Database (ព្រោះវាមាន "s" នៅខាងចុង)
    protected $table = 'meeting_bookings';

    // ២. អនុញ្ញាតឱ្យធ្វើ Mass Assignment (បង្កើត ឬកែប្រែទិន្នន័យបាន)
    protected $fillable = [
        'user_id',
        'meeting_room_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'total_hours',
        'total_price',
        'status',
        'booking_code',
        'attendees_count',
        'setup_style',
        'special_requests',
        'payment_method'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
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
}
