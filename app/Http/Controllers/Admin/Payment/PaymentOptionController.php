<?php

namespace App\Http\Controllers\Admin\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentOption;

class PaymentOptionController extends Controller
{
    public function index()
    {
        $options = PaymentOption::paginate(10);
        return view('frontend.admin.dashboard.payment.payment_options', compact('options'));
    }

    public function create()
    {
        return view('frontend.admin.dashboard.payment.forms_payment_options');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'method_name' => 'required|string',
            'logo' => 'nullable|image',
            'currency' => 'required|string',
            'is_active' => 'required|boolean',
        ]);

        if($request->hasFile('logo')){
            $data['logo'] = $request->file('logo')->store('payment_options','public');
        }

        PaymentOption::create($data);
        return redirect()->route('admin.payment-options.index')->with('success','تم إضافة خيار الدفع بنجاح');
    }

    public function edit(PaymentOption $paymentOption)
    {
        return view('frontend.admin.dashboard.payment.forms_payment_options', compact('paymentOption'));
    }

    public function update(Request $request, PaymentOption $paymentOption)
    {
        $data = $request->validate([
            'method_name' => 'required|string',
            'logo' => 'nullable|image',
            'currency' => 'required|string',
            'is_active' => 'required|boolean',
        ]);

        if($request->hasFile('logo')){
            $data['logo'] = $request->file('logo')->store('payment_options','public');
        }

        $paymentOption->update($data);
        return redirect()->route('admin.payment-options.index')->with('success','تم تحديث خيار الدفع بنجاح');
    }

    public function destroy(PaymentOption $paymentOption)
    {
        $paymentOption->delete();
        return redirect()->route('admin.payment-options.index')->with('success','تم حذف خيار الدفع بنجاح');
    }
}
