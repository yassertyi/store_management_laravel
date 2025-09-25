<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\TicketMessage;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    /**
     * عرض قائمة تذاكر العميل
     */
    public function index()
    {
        $tickets = SupportTicket::where('customer_id', Auth::user()->customer->customer_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('frontend.customers.dashboard.support.index', compact('tickets'));
    }

    /**
     * عرض صفحة إنشاء تذكرة جديدة
     */
    public function create()
    {
        return view('frontend.customers.dashboard.support.create');
    }

    /**
     * حفظ التذكرة الجديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'priority' => 'required|in:1,2,3'
        ]);

        $customerId = Auth::user()->customer->customer_id;

        $ticket = SupportTicket::create([
            'customer_id' => $customerId,
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority ?? 2,
            'status' => 0 // مفتوحة
        ]);

        // إرسال إشعار للمسؤولين
        $this->notifyAdmins($ticket);

        return redirect()->route('customer.support.tickets.show', $ticket->ticket_id)
            ->with('success', 'تم إنشاء التذكرة بنجاح وسيتم الرد عليها قريباً');
    }

    /**
     * عرض تفاصيل التذكرة
     */
    public function show($ticketId)
    {
        $ticket = SupportTicket::where('ticket_id', $ticketId)
            ->where('customer_id', Auth::user()->customer->customer_id)
            ->firstOrFail();

        $messages = TicketMessage::where('ticket_id', $ticket->ticket_id)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('frontend.customers.dashboard.support.show', compact('ticket', 'messages'));
    }

    /**
     * إرسال رسالة جديدة في التذكرة
     */
    public function sendMessage(Request $request, $ticketId)
    {
        $request->validate([
            'message' => 'required|string|min:2'
        ]);

        $ticket = SupportTicket::where('ticket_id', $ticketId)
            ->where('customer_id', Auth::user()->customer->customer_id)
            ->firstOrFail();

        if ($ticket->status == 2) {
            return back()->with('error', 'لا يمكن إرسال رسائل في تذكرة مغلقة');
        }

        TicketMessage::create([
            'ticket_id' => $ticket->ticket_id,
            'user_id' => Auth::id(),
            'message' => $request->message
        ]);

        if ($ticket->status == 0) {
            $ticket->update(['status' => 1]);
        }

        return back()->with('success', 'تم إرسال الرسالة بنجاح');
    }

    /**
     * إغلاق التذكرة
     */
    public function closeTicket($ticketId)
    {
        $ticket = SupportTicket::where('ticket_id', $ticketId)
            ->where('customer_id', Auth::user()->customer->customer_id)
            ->firstOrFail();

        $ticket->update(['status' => 2]);

        return back()->with('success', 'تم إغلاق التذكرة بنجاح');
    }

    /**
     * إشعار المسؤولين بوجود تذكرة جديدة
     */
    private function notifyAdmins(SupportTicket $ticket)
    {
        // يمكنك إرسال إشعار أو إيميل للمسؤولين هنا
    }
}
