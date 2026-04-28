<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;

    // កំណត់ឈ្មោះ Table (ករណីអ្នកចង់ប្រើឈ្មោះផ្សេង)
    protected $table = 'contacts';

    // អនុញ្ញាតឱ្យបញ្ចូលទិន្នន័យក្នុង Field ទាំងនេះ
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
