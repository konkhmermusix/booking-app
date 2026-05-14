<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['conversation_id', 'user_id', 'message', 'file_path', 'is_read', 'created_at', 'updated_at'];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    // អ្នកដែលបានសរសេរសារនេះ
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
