<?php

namespace App\Http\Controllers\Customer\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        // الحصول على معرف العميل الحالي
        $customerId = Auth::user()->customer->customer_id;

        // الاستعلام عن المدفوعات من خلال الطلبات الخاصة بالعميل
        $query = Payment::whereHas('order', function($q) use ($customerId) {
            $q->where('customer_id', $customerId);
        });

        // فلترة حسب الحالة
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // فلترة حسب التاريخ
        if ($request->date_from) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        $payments = $query->with([
                        'order.customer.user',
                        'storePaymentMethod.paymentOption'
                    ])
                    ->latest()
                    ->paginate(10);

        return view('frontend.customers.dashboard.payment.payments', compact('payments'));
    }

    // عرض تفاصيل الدفع
    public function show($id)
    {
        $customerId = Auth::user()->customer->customer_id;
        
        $payment = Payment::whereHas('order', function($q) use ($customerId) {
                        $q->where('customer_id', $customerId);
                    })
                    ->with([
                        'order.orderItems.product.images',
                        'order.orderItems.variant',
                        'order.customer.user',
                        'storePaymentMethod.paymentOption',
                        'order.orderAddresses',
                        // تحميل المتجر مع علاقاته
                        'order.orderItems.product.store.addresses',
                        'order.orderItems.product.store.phones'
                    ])
                    ->findOrFail($id);

        // الحصول على المتجر من أول منتج في الطلب
        $store = null;
        if ($payment->order && $payment->order->orderItems->count() > 0) {
            $firstProduct = $payment->order->orderItems->first()->product;
            if ($firstProduct && $firstProduct->store) {
                $store = $firstProduct->store;
            }
        }

        return view('frontend.customers.dashboard.payment.payment_details', compact('payment', 'store'));
    }
}