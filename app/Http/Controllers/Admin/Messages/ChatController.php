<?php

namespace App\Http\Controllers\Admin\Messages;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat::with('user')->latest()->paginate(10);
        return view('frontend.admin.dashboard.supportTickets.chat', compact('chats'));
    }

    public function show($id)
    {
        $chat = Chat::with(['user', 'messages.sender'])->findOrFail($id);
        return view('frontend.admin.dashboard.supportTickets.chat_show', compact('chat'));
    }

    
public function store(Request $request)
{
    $request->validate([
        'chat_id' => 'required|exists:chats,chat_id',
        'content' => 'required|string',
    ]);

    $chat = Chat::findOrFail($request->chat_id);

    Message::create([
        'chat_id'     => $request->chat_id,
        'sender_id'   => auth()->id(),
        'receiver_id' => $chat->user_id,
        'content'     => $request->content,
    ]);

    return redirect()->back()->with('success', 'تم إرسال الرسالة بنجاح');
}



    public function destroy($id)
    {
        $chat = Chat::findOrFail($id);
        $chat->delete();

        return redirect()->route('admin.chats.index')->with('success', 'تم حذف المحادثة بنجاح');
    }
}