<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ChatService;
use App\Http\Requests\ChatRequest;
use App\Models\Conversation;
use App\Models\User;

class ChatWebController extends Controller
{

    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function store(ChatRequest $request)
    {
        try {
            $userId = auth()->id();
            $adminId = 1;

            $conversation = \App\Models\Conversation::firstOrCreate([
                'sender_id' => $userId,
                'receiver_id' => $adminId,
            ]);

            $data = $request->validated();
            $data['conversation_id'] = $conversation->id;

            $message = $this->chatService->sendMessage($data, $userId);

            return response()->json(['status' => 'success', 'data' => $message]);
        } catch (\Exception $e) {
            // បើមាន Error វានឹងបោះសារមកក្នុង Console ឱ្យយើងឃើញ
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
