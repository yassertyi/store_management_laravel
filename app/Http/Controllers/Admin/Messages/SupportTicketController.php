<?php

namespace App\Http\Controllers\Admin\Messages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\Customer;
use App\Models\User;

class SupportTicketController extends Controller
{
    /**
     * عرض جميع التذاكر
     */
    public function index()
    {
        $tickets = SupportTicket::with(['customer', 'messages'])->latest()->paginate(15);
        return view('frontend.admin.dashboard.supportTickets.support_tickets', compact('tickets'));
    }

    /**
     * عرض نموذج إنشاء تذكرة جديدة
     */
    public function create()
    {
        $customers = Customer::all();
        // المسؤولون هم المستخدمون من نوع 2
        $admins = User::where('user_type', 2)->get();
        return view('frontend.admin.dashboard.supportTickets.forms_support_tickets', compact('customers', 'admins'));
    }

    /**
     * حفظ تذكرة جديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string',
            'priority' => 'required|string',
            'assigned_to' => 'nullable|exists:users,user_id', 
        ]);

        SupportTicket::create($request->all());

        return redirect()->route('admin.support-tickets.index')
            ->with('success', '📝 تم إنشاء التذكرة بنجاح');
    }

    /**
     * عرض تفاصيل تذكرة معينة
     */
    public function show($id)
    {
        $ticket = SupportTicket::with(['customer', 'messages'])->findOrFail($id);
        return view('frontend.admin.dashboard.supportTickets.support_tickets_details', compact('ticket'));
    }

    /**
     * عرض نموذج تعديل تذكرة
     */
    public function edit($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $customers = Customer::all();
        $admins = User::where('user_type', 2)->get();
        return view('frontend.admin.dashboard.supportTickets.forms_support_tickets', compact('ticket', 'customers', 'admins'));
    }

    /**
     * تحديث تذكرة
     */
    public function update(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string',
            'priority' => 'required|string',
            'assigned_to' => 'nullable|exists:users,user_id',
        ]);

        $ticket->update($request->all());

        return redirect()->route('admin.support-tickets.index')
            ->with('success', '✏️ تم تعديل التذكرة بنجاح');
    }

    /**
     * حذف تذكرة
     */
    public function destroy($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->delete();

        return redirect()->route('admin.support-tickets.index')
            ->with('success', '🗑️ تم حذف التذكرة بنجاح');
    }
}
