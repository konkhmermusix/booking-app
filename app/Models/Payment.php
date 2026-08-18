<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'hotel_booking_id',
        'meeting_booking_id',
        'method',
        'amount',
        'currency',
        'transaction_id',
        'payment_slip',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'amount'  => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function hotelBooking(): BelongsTo
    {
        return $this->belongsTo(HotelBooking::class, 'hotel_booking_id');
    }

    public function meetingBooking(): BelongsTo
    {
        return $this->belongsTo(MeetingBooking::class, 'meeting_booking_id');
    }
}