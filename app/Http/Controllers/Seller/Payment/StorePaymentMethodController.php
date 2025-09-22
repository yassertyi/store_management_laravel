<?php

namespace App\Http\Controllers\Seller\Payment;

use App\Http\Controllers\Controller;
use App\Models\StorePaymentMethod;
use App\Models\PaymentOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StorePaymentMethodController extends Controller
{
    // عرض جميع طرق الدفع الخاصة بالمتجر
    public function index()
    {
        $storeId = Auth::user()->seller->store_id;
        $methods = StorePaymentMethod::with('paymentOption')
            ->where('store_id', $storeId)
            ->paginate(10);

        return view('frontend.Seller.dashboard.payment.store_payment_methods', compact('methods'));
    }

    // عرض نموذج إنشاء طريقة دفع جديدة
    public function create()
    {
        $options = PaymentOption::where('is_active', true)->get();
        return view('frontend.Seller.dashboard.payment.forms_store_payment_methods', compact('options'));
    }

    // حفظ طريقة الدفع الجديدة
    public function store(Request $request)
    {
        $request->validate([
            'payment_option_id' => 'required|exists:payment_options,option_id',
            'account_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
        ]);

        StorePaymentMethod::create([
            'store_id' => Auth::user()->seller->store_id,
            'payment_option_id' => $request->payment_option_id,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'iban' => $request->iban,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('seller.storePaymentMethods.index')->with('success', 'تمت إضافة طريقة الدفع بنجاح');
    }

    // عرض نموذج تعديل طريقة الدفع
    public function edit($id)
    {
        $method = StorePaymentMethod::findOrFail($id);
        $options = PaymentOption::where('is_active', true)->get();

        return view('frontend.Seller.dashboard.payment.forms_store_payment_methods', compact('method', 'options'));
    }

    // تحديث طريقة الدفع
    public function update(Request $request, $id)
    {
        $request->validate([
            'payment_option_id' => 'required|exists:payment_options,option_id',
            'account_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
        ]);

        $method = StorePaymentMethod::findOrFail($id);
        $method->update([
            'payment_option_id' => $request->payment_option_id,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'iban' => $request->iban,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('seller.storePaymentMethods.index')->with('success', 'تم تحديث طريقة الدفع بنجاح');
    }

    // حذف طريقة الدفع
    public function destroy($id)
    {
        $method = StorePaymentMethod::findOrFail($id);
        $method->delete();

        return redirect()->route('seller.storePaymentMethods.index')->with('success', 'تم حذف طريقة الدفع بنجاح');
    }
}
