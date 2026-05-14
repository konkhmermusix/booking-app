<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Models\Message;
use App\Models\Conversation;

class ChatRepository
{
    public function getMessagesByConversation($conversationId)
    {
        return Message::where('conversation_id', $conversationId)->with('sender')->get();
    }

    public function createMessage(array $data)
    {
        return Message::create($data);
    }

    public function getOrCreateConversation($customerId, $adminId)
    {
        return Conversation::firstOrCreate([
            'customer_id' => $customerId,
            'admin_id' => $adminId
        ]);
    }
}
