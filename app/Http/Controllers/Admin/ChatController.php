<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $conversations = Conversation::with(['messages', 'sender', 'receiver'])
            ->withCount(['messages as unread_count' => function ($query) {
                $query->where('user_id', '!=', auth()->id())->where('is_read', 0);
            }])
            ->orderBy('updated_at', 'desc')
            ->get();

        $activeConversation = null;
        $chatMessages = [];

        if ($request->has('conversation_id')) {
            $activeConversation = Conversation::find($request->conversation_id);

            if ($activeConversation) {
                Message::where('conversation_id', $activeConversation->id)
                    ->where('user_id', '!=', auth()->id())
                    ->where('is_read', 0)
                    ->update(['is_read' => 1]);

                $chatMessages = Message::where('conversation_id', $activeConversation->id)
                    ->orderBy('created_at', 'asc')
                    ->get();
            }
        } elseif ($conversations->count() > 0) {
            $activeConversation = $conversations->first();
            Message::where('conversation_id', $activeConversation->id)
                ->where('user_id', '!=', auth()->id())
                ->where('is_read', 0)
                ->update(['is_read' => 1]);

            $chatMessages = Message::where('conversation_id', $activeConversation->id)
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('admin.messages.index', compact(
            'conversations',
            'activeConversation',
            'chatMessages'
        ));
    }

    public function show($id)
    {
        $conversation = Conversation::findOrFail($id);

        $messages = Message::where('conversation_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.messages.show', compact('conversation', 'messages'));
    }

    public function store(Request $request, $conversationId)
    {
        $request->validate([
            'message' => 'nullable|string',
            'images' => 'nullable|image|max:20480',
            'file' => 'nullable|mimes:pdf,docx,xlsx,txt|max:51200',
        ]);

        $conversation = Conversation::findOrFail($conversationId);

        $imagePath = null;
        $filePath = null;

        if ($request->hasFile('images')) {
            $imagePath = $request->file('images')->store('chat_images', 'public');
        }

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('chat_files', 'public');
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'images' => $imagePath,
            'file_path' => $filePath,
            'is_read' => 0,
        ]);

        // Touch conversation updated_at for sorting
        $conversation->touch();

        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return redirect()->route('messages.index', ['conversation_id' => $conversation->id]);
    }

    public function destroyConversation($id)
    {
        $conversation = Conversation::findOrFail($id);
        
        // Delete all associated messages
        Message::where('conversation_id', $conversation->id)->delete();
        
        // Delete the conversation
        $conversation->delete();

        if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'បានលុបការជជែកដោយជោគជ័យ'
            ]);
        }

        return redirect()->route('messages.index')->with('success', 'បានលុបការជជែកដោយជោគជ័យ');
    }
}
