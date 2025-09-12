<?php

namespace App\Http\Controllers\Admin\Messages;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;

class MessageController extends Controller
{
    // عرض جميع الرسائل
    public function index()
    {
        $messages = Message::with(['sender', 'receiver'])->latest()->paginate(15);
        return view('frontend.admin.dashboard.supportTickets.message', compact('messages'));
    }

    // نموذج إنشاء رسالة جديدة
    public function create()
    {
        $users = User::all();
        $chats = Chat::all();
        return view('frontend.admin.dashboard.supportTickets.forms_message', compact('users', 'chats'));
    }

public function store(Request $request)
{
    $request->validate([
        'sender_id'   => 'required|exists:users,user_id',
        'receiver_id' => 'required|exists:users,user_id',
        'chat_id'     => 'required|exists:chats,chat_id',
        'content'     => 'required|string',
    ]);

    Message::create([
        'sender_id'   => $request->sender_id,
        'receiver_id' => $request->receiver_id,
        'chat_id'     => $request->chat_id,
        'content'     => $request->content,
        'is_read'     => $request->is_read ?? 0,
    ]);

    return redirect()->route('admin.messages.index')
        ->with('success', '📝 تم إنشاء الرسالة بنجاح');
}


    // نموذج تعديل رسالة
// تعديل الدوال edit و update
public function edit($message_id)
{
    $message = Message::findOrFail($message_id);
    $users = User::all();
    $chats = Chat::all();
    return view('frontend.admin.dashboard.supportTickets.forms_message', compact('message', 'users', 'chats'));
}

public function update(Request $request, $message_id)
{
    $message = Message::findOrFail($message_id);

    $request->validate([
        'sender_id'   => 'required|exists:users,user_id',
        'receiver_id' => 'required|exists:users,user_id',
        'chat_id'     => 'required|exists:chats,chat_id',
        'content'     => 'required|string',
    ]);

    $message->update([
        'sender_id'   => $request->sender_id,
        'receiver_id' => $request->receiver_id,
        'chat_id'     => $request->chat_id,
        'content'     => $request->content,
        'is_read'     => $request->is_read ?? 0,
    ]);

    return redirect()->route('admin.messages.index')
        ->with('success', '✏️ تم تعديل الرسالة بنجاح');
}



    // حذف رسالة
    public function destroy($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.messages.index')
            ->with('success', '🗑️ تم حذف الرسالة بنجاح');
    }
    
    public function markAsRead(Request $request)
    {
        Message::where('receiver_id', auth()->id())
               ->where('is_read', 0)
               ->update(['is_read' => 1]);

        return response()->json(['success' => true]);
    }

}