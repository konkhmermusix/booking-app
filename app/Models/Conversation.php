<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['sender_id', 'receiver_id', 'created_at', 'updated_at'];


    // សារទាំងអស់ដែលស្ថិតក្នុងទំនាក់ទំនងនេះ
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // អ្នកផ្ញើ (Customer)
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // អ្នកទទួល (Admin)
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
