<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ChatService;
use App\Http\Requests\ChatRequest;

class ChatController extends Controller
{
    protected $chatService;
    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function store(ChatRequest $request)
    {
        // Admin ផ្ញើសារទៅកាន់ Customer
        $message = $this->chatService->sendMessage($request->validated(), auth()->id());
        return response()->json($message);
    }
}
