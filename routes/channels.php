<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;
use App\Models\Message;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    $conversation = Conversation::find($conversationId);
    // អនុញ្ញាតឱ្យតែ Admin និង Customer ដែលពាក់ព័ន្ធប៉ុណ្ណោះ
    return $user->id === $conversation->customer_id || $user->id === $conversation->admin_id;
});
