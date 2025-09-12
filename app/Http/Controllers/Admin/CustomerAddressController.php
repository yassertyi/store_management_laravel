<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\Customer;

class CustomerAddressController extends Controller
{
    // عرض جميع عناوين العملاء
    public function index()
    {
        $addresses = Address::with('customer')->orderBy('address_id', 'desc')->paginate(15);
        return view('frontend.admin.dashboard.orders.user_addresses_all', compact('addresses'));
    }

    // صفحة إضافة عنوان جديد
    public function create()
    {
        $customers = Customer::all();
        return view('frontend.admin.dashboard.orders.forms_user_addresses', compact('customers'));
    }

    // حفظ عنوان جديد
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:customers,customer_id',
            'title' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'apartment' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'is_default' => 'nullable|boolean',
        ]);

        Address::create($data);

        return redirect()->route('admin.customer-addresses.index')->with('success', 'تم حفظ عنوان العميل بنجاح.');
    }

    // تعديل عنوان العميل
    public function edit(Address $customer_address)
    {
        $customers = Customer::all();
        return view('frontend.admin.dashboard.orders.forms_user_addresses', [
            'address' => $customer_address,
            'customers' => $customers
        ]);
    }

    // تحديث عنوان العميل
    public function update(Request $request, Address $customer_address)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:customers,customer_id',
            'title' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'apartment' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'is_default' => 'nullable|boolean',
        ]);

        $customer_address->update($data);

        return redirect()->route('admin.customer-addresses.index')->with('success', 'تم تحديث عنوان العميل بنجاح.');
    }

    // حذف عنوان العميل
    public function destroy(Address $customer_address)
    {
        $customer_address->delete();
        return response()->json(['success' => 'تم حذف عنوان العميل بنجاح.']);
    }
}
