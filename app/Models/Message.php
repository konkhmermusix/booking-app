<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Throwable;

class Message extends Model
{
    protected $fillable = ['conversation_id', 'user_id', 'message', 'images', 'file_path', 'is_read', 'created_at', 'updated_at'];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function setMessageAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['message'] = Crypt::encryptString($value);
        } else {
            $this->attributes['message'] = $value;
        }
    }


    public function getMessageAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }
        try {
            return Crypt::decryptString($value);
        } catch (Throwable $e) {
            return $value;
        }
    }
}
