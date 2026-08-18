<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $authId = Auth::id();

        $conversations = Conversation::where('sender_id', $authId)
            ->orWhere('receiver_id', $authId)
            ->with(['messages' => function ($query) {
                $query->latest();
            }, 'sender', 'receiver'])
            ->get()
            ->sortByDesc(function ($conversation) {
                return $conversation->messages->first()?->created_at ?? $conversation->updated_at;
            });

        $activeConversation = null;
        $chatMessages = collect();

        if ($request->has('conversation_id')) {
            $activeConversation = Conversation::where(function ($query) use ($authId) {
                $query->where('sender_id', $authId)->orWhere('receiver_id', $authId);
            })
                ->with(['sender', 'receiver'])
                ->find($request->conversation_id);

            if ($activeConversation) {
                $chatMessages = Message::where('conversation_id', $activeConversation->id)
                    ->with('user')
                    ->orderBy('created_at', 'asc')
                    ->get();

                Message::where('conversation_id', $activeConversation->id)
                    ->where('user_id', '!=', $authId)
                    ->where('is_read', 0)
                    ->update(['is_read' => 1]);
            }
        } else if ($conversations->count() > 0) {
            return redirect()->route('messages.index', ['conversation_id' => $conversations->first()->id]);
        }

        return view('admin.messages.index', compact('conversations', 'activeConversation', 'chatMessages'));
    }

    // មុខងារសម្រាប់ផ្ញើសារថ្មី
    public function store(Request $request, $conversationId)
    {
        $request->validate([
            'message' => 'required_without_all:images,file_path|nullable|string',
            'images'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            'file_path' => 'nullable|mimes:pdf,docx,xlsx,zip|max:51200',
        ]);

        $conversation = Conversation::findOrFail($conversationId);

        $imagePath = null;
        if ($request->hasFile('images')) {
            $imagePath = $request->file('images')->store('chats/images', 'public');
        }

        $filePath = null;
        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('chats/files', 'public');
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'images' => $imagePath,
            'file_path' => $filePath,
            'is_read' => 0
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }

    public function userSendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required_without_all:images|nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
        ]);

        $admin = User::where('role', 'admin')->first();
        $adminId = $admin ? $admin->id : 1;

        $authId = Auth::id();

        if ($authId) {
            $conversation = Conversation::firstOrCreate([
                'sender_id' => $authId,
                'receiver_id' => $adminId
            ]);
        } else {
            $sessionKey = session()->get('chat_session_key', Str::random(40));
            session(['chat_session_key' => $sessionKey]);

            $conversation = Conversation::firstOrCreate([
                'chat_session_key' => $sessionKey,
                'receiver_id' => $adminId
            ]);
        }

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagePaths[] = $file->store('chats/images', 'public');
            }
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id'         => $authId ?? null,
            'message'         => $request->message,
            'images'          => !empty($imagePaths) ? json_encode($imagePaths) : null,
            'is_read'         => 0
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'សារត្រូវបានផ្ញើជូនអ្នកគ្រប់គ្រងរួចរាល់',
            'data' => $message
        ]);
    }
}
