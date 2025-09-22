<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    // عرض جميع التذاكر الخاصة بالبائع
    public function index()
    {
        $tickets = SupportTicket::where('assigned_to', Auth::id())
                    ->latest()
                    ->paginate(10);

        return view('frontend.Seller.dashboard.support.index', compact('tickets'));
    }

    // إنشاء تذكرة جديدة
    public function create()
    {
        $customers = Customer::all(); // جلب جميع العملاء ليختار البائع منهم
        return view('frontend.Seller.dashboard.support.create', compact('customers'));
    }

    // حفظ التذكرة
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:1,2,3',
        ]);

        $ticket = SupportTicket::create([
            'customer_id' => $request->customer_id,
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 0, // مفتوحة
            'assigned_to' => Auth::id(), // ربط التذكرة بالبائع الحالي تلقائيًا
        ]);

        return redirect()->route('seller.seller.support.show', $ticket->ticket_id)
                         ->with('success', 'تم إنشاء التذكرة بنجاح ✅');
    }

    // عرض تفاصيل التذكرة
    public function show(SupportTicket $ticket)
    {
        // التحقق من أن البائع هو المسؤول عن التذكرة
        if ($ticket->assigned_to != Auth::id()) {
            abort(403, 'غير مصرح لك بمشاهدة هذه التذكرة');
        }

        $messages = $ticket->messages()->latest()->get();
        return view('frontend.Seller.dashboard.support.show', compact('ticket', 'messages'));
    }

    // إغلاق التذكرة
    public function close(SupportTicket $ticket)
    {
        if ($ticket->assigned_to != Auth::id()) {
            abort(403, 'غير مصرح لك بإغلاق هذه التذكرة');
        }

        $ticket->status = 2; // مغلقة
        $ticket->save();

        return back()->with('success', 'تم إغلاق التذكرة');
    }
}
