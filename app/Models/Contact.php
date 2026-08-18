<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';

    protected $fillable = [
        'name',
        'email',
        'tell',
        'description',
        'status'
    ];

    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }
}
