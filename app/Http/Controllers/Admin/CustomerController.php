<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // عرض قائمة العملاء
    public function index()
    {
        $customers = Customer::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('frontend.admin.dashboard.users.customer_all', compact('customers'));
    }

    // عرض نموذج إضافة عميل
    public function create()
    {
        $users = User::where('user_type', 'عميل')->doesntHave('customer')->get();
        return view('frontend.admin.dashboard.users.forms_customer', compact('users'));
    }

    // حفظ العميل الجديد
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id|unique:customers,user_id',
            'loyalty_points' => 'nullable|integer',
            'total_orders' => 'nullable|integer',
        ]);

        Customer::create($request->all());

        return redirect()->route('admin.customers.index')->with('success', 'تمت إضافة العميل بنجاح');
    }

    // عرض نموذج تعديل العميل
    public function edit(Customer $customer)
    {
        $users = User::where('user_type', 'عميل')->get();
        return view('frontend.admin.dashboard.users.forms_customer', compact('customer', 'users'));
    }

    // تحديث بيانات العميل
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id|unique:customers,user_id,' . $customer->customer_id . ',customer_id',
            'loyalty_points' => 'nullable|integer',
            'total_orders' => 'nullable|integer',
        ]);

        $customer->update($request->all());

        return redirect()->route('admin.customers.index')->with('success', 'تم تحديث بيانات العميل بنجاح');
    }

    // حذف العميل
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'تم حذف العميل بنجاح');
    }
}
