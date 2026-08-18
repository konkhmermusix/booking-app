<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatWebController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user && in_array($user->role, ['admin', 'staff'])) {
            return redirect()->route('messages.index');
        }

        $userId = Auth::id();

        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            abort(500, 'No admin found');
        }

        $conversation = Conversation::where(function($q) use ($userId, $admin) {
            $q->where('sender_id', $userId)->where('receiver_id', $admin->id);
        })->orWhere(function($q) use ($userId, $admin) {
            $q->where('sender_id', $admin->id)->where('receiver_id', $userId);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'sender_id' => $userId,
                'receiver_id' => $admin->id,
            ]);
        }

        Message::where('conversation_id', $conversation->id)
            ->where('user_id', '!=', $userId)
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        $messages = Message::where('conversation_id', $conversation->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('frontend.chat', compact('conversation', 'messages'));
    }

    public function fetchMessages($id)
    {
        $conversation = Conversation::findOrFail($id);

        if (
            $conversation->sender_id != Auth::id() &&
            $conversation->receiver_id != Auth::id()
        ) {
            abort(403);
        }

        Message::where('conversation_id', $id)
            ->where('user_id', '!=', Auth::id())
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return Message::where('conversation_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'nullable|string',
            'image' => 'nullable|image|max:20480',
            'file' => 'nullable|mimes:pdf,docx,xlsx,txt|max:5120',
        ]);

        $conversation = Conversation::findOrFail($request->conversation_id);

        if (
            $conversation->sender_id != Auth::id() &&
            $conversation->receiver_id != Auth::id()
        ) {
            abort(403);
        }

        $imagePath = null;
        $filePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat_images', 'public');
        }

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('chat_files', 'public');
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'images' => $imagePath,
            'file_path' => $filePath,
            'is_read' => 0,
        ]);

        $conversation->touch();

        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return redirect()->back();
    }

    public function updateMessage(Request $request, $id)
    {
        $message = Message::findOrFail($id);

        if ($message->user_id != Auth::id() && !in_array(Auth::user()->role, ['admin', 'staff'])) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        $message->update([
            'message' => $request->message,
        ]);

        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return redirect()->back();
    }

    public function destroyMessage($id)
    {
        $message = Message::findOrFail($id);

        if ($message->user_id != Auth::id() && !in_array(Auth::user()->role, ['admin', 'staff'])) {
            abort(403);
        }

        $message->delete();

        if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()->back();
    }
}
