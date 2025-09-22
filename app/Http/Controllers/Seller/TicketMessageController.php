<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\TicketMessage;
use Illuminate\Support\Facades\Auth;

class TicketMessageController extends Controller
{
    public function store(Request $request, SupportTicket $ticket)
    {
        // التحقق من أن البائع هو المسؤول عن التذكرة
        if ($ticket->assigned_to != Auth::id()) {
            abort(403, 'غير مصرح لك بإرسال رسالة في هذه التذكرة');
        }

        $request->validate([
            'message' => 'required|string'
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->ticket_id,
            'user_id' => Auth::id(),
            'message' => $request->message
        ]);

        return back()->with('success', 'تم إرسال الرسالة ✅');
    }
}
