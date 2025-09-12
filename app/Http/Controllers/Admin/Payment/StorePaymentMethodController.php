<?php

namespace App\Http\Controllers\Admin\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StorePaymentMethod;
use App\Models\Store;
use App\Models\PaymentOption;

class StorePaymentMethodController extends Controller
{
    public function index()
    {
        $methods = StorePaymentMethod::with(['store','paymentOption'])->paginate(10);
        return view('frontend.admin.dashboard.payment.store_payment_methods', compact('methods'));
    }

    public function create()
    {
        $stores = Store::all();
        $options = PaymentOption::all();
        return view('frontend.admin.dashboard.payment.forms_store_payment_methods', compact('stores','options'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'store_id' => 'required|exists:stores,store_id',
            'payment_option_id' => 'required|exists:payment_options,option_id',
            'account_name' => 'required|string',
            'account_number' => 'required|string',
            'iban' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        StorePaymentMethod::create($data);
        return redirect()->route('admin.store-payment-methods.index')->with('success','تم إضافة طريقة الدفع بنجاح');
    }

    public function edit(StorePaymentMethod $storePaymentMethod)
    {
        $stores = Store::all();
        $options = PaymentOption::all();
        return view('frontend.admin.dashboard.payment.forms_store_payment_methods', compact('storePaymentMethod','stores','options'));
    }

    public function update(Request $request, StorePaymentMethod $storePaymentMethod)
    {
        $data = $request->validate([
            'store_id' => 'required|exists:stores,store_id',
            'payment_option_id' => 'required|exists:payment_options,option_id',
            'account_name' => 'required|string',
            'account_number' => 'required|string',
            'iban' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $storePaymentMethod->update($data);
        return redirect()->route('admin.store-payment-methods.index')->with('success','تم تحديث طريقة الدفع بنجاح');
    }

    public function destroy(StorePaymentMethod $storePaymentMethod)
    {
        $storePaymentMethod->delete();
        return redirect()->route('admin.store-payment-methods.index')->with('success','تم حذف طريقة الدفع بنجاح');
    }
}
