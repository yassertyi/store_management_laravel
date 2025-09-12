<?php

namespace App\Http\Controllers\Admin\Messages;

use App\Http\Controllers\Controller;
use App\Models\TicketMessage;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Http\Request;

class TicketMessageController extends Controller
{
    public function index()
    {
        $messages = TicketMessage::with(['ticket', 'user'])
            ->latest()
            ->paginate(10);

        return view('frontend.admin.dashboard.supportTickets.ticket_messages', compact('messages'));
    }

    public function create()
    {
        $tickets = SupportTicket::all();
        $users   = User::all(); 
        return view('frontend.admin.dashboard.supportTickets.forms_ticket_messages', compact('tickets', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:support_tickets,ticket_id',
            'user_id'   => 'required|exists:users,user_id', 
            'message'   => 'required|string',
        ]);

        TicketMessage::create([
            'ticket_id' => $request->ticket_id,
            'user_id'   => $request->user_id,
            'message'   => $request->message,
        ]);

        return redirect()->route('admin.ticket-messages.index')
            ->with('success', '✅ تم إرسال الرسالة بنجاح');
    }

    // public function show($id)
    // {
    //     $message = TicketMessage::with(['ticket', 'user'])->findOrFail($id);
    //     return view('frontend.admin.dashboard.supportTickets.ticket_messages_details', compact('message'));
    // }

    public function edit($id)
    {
        $message = TicketMessage::findOrFail($id);
        $tickets = SupportTicket::all();
        $users   = User::all(); 
        return view('frontend.admin.dashboard.supportTickets.forms_ticket_messages', compact('message', 'tickets', 'users'));
    }

    public function update(Request $request, $id)
    {
        $message = TicketMessage::findOrFail($id);

        $request->validate([
            'ticket_id' => 'required|exists:support_tickets,ticket_id',
            'user_id'   => 'required|exists:users,user_id',
            'message'   => 'required|string',
        ]);

        $message->update([
            'ticket_id' => $request->ticket_id,
            'user_id'   => $request->user_id,
            'message'   => $request->message,
            'is_read'   => $request->is_read ?? 0,
        ]);

        return redirect()->route('admin.ticket-messages.index')
            ->with('success', '✏️ تم تعديل الرسالة بنجاح');
    }

    public function destroy($id)
    {
        $message = TicketMessage::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.ticket-messages.index')
            ->with('error', '🗑️ تم حذف الرسالة بنجاح');
    }
}
