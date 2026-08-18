<?php

namespace App\Services;

use App\Repositories\ChatRepository;
use App\Events\MessageSent;
use App\Traits\UploadTrait;
use App\Models\Message;

class ChatService
{
    use UploadTrait;
    protected $repository;

    public function __construct(
        ChatRepository $repository,
    ) {
        $this->repository = $repository;
    }

    public function sendMessage($data, $senderId)
    {
        if (isset($data['images']) && is_array($data['images'])) {
            $data['file_path'] = $this->uploadFile($data['images'][0]);
        }

        $message = Message::create([
            'conversation_id' => $data['conversation_id'],
            'user_id'         => $senderId,
            'message'         => $data['message'] ?? null,
            'file_path'       => $data['file_path'] ?? null,
            'is_read'         => false,
        ]);

        broadcast(new MessageSent($message->load('user')))->toOthers();

        return $message;
    }
}
