<?php

namespace App\Http\Controllers\Seller\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $storeId = Auth::user()->seller->store_id;

        $query = Payment::whereHas('order.orderItems.product', function ($q) use ($storeId) {
            $q->where('store_id', $storeId);
        });

        // فلترة حسب الحالة
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // فلترة حسب التاريخ
        if ($request->date) {
            $query->whereDate('payment_date', $request->date);
        }

        $payments = $query->with(['order', 'storePaymentMethod'])
                          ->latest()
                          ->paginate(10);

        return view('frontend.Seller.dashboard.payment.payments', compact('payments'));
    }

    // عرض تفاصيل الدفع
    public function show($id)
    {
        $payment = Payment::with([
            'order.orderItems.product',
            'order.orderItems.variant',
            'order.customer.user',
            'storePaymentMethod.paymentOption',
            'order.orderAddresses'
        ])->findOrFail($id);

        // الحصول على بيانات المتجر الحالي
        $store = Store::with(['addresses', 'phones'])
                     ->where('store_id', Auth::user()->seller->store_id)
                     ->first();

        return view('frontend.Seller.dashboard.payment.payment_details', compact('payment', 'store'));
    }
}