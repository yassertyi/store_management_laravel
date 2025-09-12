<?php

namespace App\Http\Controllers\Admin\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;
use App\Models\StorePaymentMethod;

class PaymentController extends Controller
{
    // عرض قائمة المدفوعات
    public function index()
    {
        $payments = Payment::with(['order','storePaymentMethod'])->paginate(10);
        return view('frontend.admin.dashboard.payment.payments', compact('payments'));
    }

    // صفحة إضافة دفع جديد
    public function create()
    {
        $orders = Order::all();
        $methods = StorePaymentMethod::where('is_active', 1)->get();
        return view('frontend.admin.dashboard.payment.forms_payments', compact('orders','methods'));
    }

    // حفظ الدفع الجديد
    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|exists:orders,order_id',
            'store_payment_method_id' => 'nullable|exists:store_payment_methods,spm_id',
            'amount' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'total_amount' => 'required|numeric',
            'currency' => 'required|string',
            'method' => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'status' => 'required|in:pending,completed,failed,refunded',
            'type' => 'required|in:online,cash',
            'note' => 'nullable|string',
            'payment_date' => 'required|date',
        ]);

        Payment::create($data);

        return redirect()->route('admin.payments.index')->with('success','تم إضافة عملية الدفع بنجاح');
    }

    // صفحة تعديل الدفع
    public function edit(Payment $payment)
    {
        $orders = Order::all();
        $methods = StorePaymentMethod::where('is_active', 1)->get();
        return view('frontend.admin.dashboard.payment.forms_payments', compact('payment','orders','methods'));
    }

    // تحديث الدفع
    public function update(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'order_id' => 'required|exists:orders,order_id',
            'store_payment_method_id' => 'nullable|exists:store_payment_methods,spm_id',
            'amount' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'total_amount' => 'required|numeric',
            'currency' => 'required|string',
            'method' => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'status' => 'required|in:pending,completed,failed,refunded',
            'type' => 'required|in:online,cash',
            'note' => 'nullable|string',
            'payment_date' => 'required|date',
        ]);

        $payment->update($data);

        return redirect()->route('admin.payments.index')->with('success','تم تحديث عملية الدفع بنجاح');
    }

    // حذف الدفع
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('admin.payments.index')->with('success','تم حذف عملية الدفع بنجاح');
    }

    // Ajax لجلب بيانات الطلب
    public function getOrderData(Order $order)
    {
        return response()->json([
            'subtotal' => $order->subtotal,
            'discount' => $order->discount_amount,
            'shipping' => $order->shipping_amount,
            'tax'      => $order->tax_amount,
            'total'    => $order->total_amount,
            'currency' => $order->currency ?? 'ريال يمني',
        ]);
    }
}
