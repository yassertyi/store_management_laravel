<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Seller;

class SellerOrderController extends Controller
{
    // عرض جميع الطلبات الخاصة بالمتجر
    public function index(Request $request)
{
    $userId = auth()->user()->user_id;

    $seller = Seller::where('user_id', $userId)->first();
    if (!$seller || !$seller->store_id) {
        return view('frontend.Seller.dashboard.orders.orders', ['orders' => collect([])]);
    }
    $storeId = $seller->store_id;

    $orders = Order::whereHas('items.product', function ($query) use ($storeId) {
        $query->where('store_id', $storeId);
    })
    ->with(['items.product', 'customer.user', 'payments'])
    ->when($request->status, function ($query) use ($request) {
        $query->where('status', $request->status);
    })
    ->when($request->payment_status, function ($query) use ($request) {
        $query->where('payment_status', $request->payment_status);
    })
    ->when($request->customer_name, function ($query) use ($request) {
        $query->whereHas('customer.user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->customer_name . '%');
        });
    })
    ->when($request->date_from, function ($query) use ($request) {
        $query->whereDate('created_at', '>=', $request->date_from);
    })
    ->when($request->date_to, function ($query) use ($request) {
        $query->whereDate('created_at', '<=', $request->date_to);
    })
    ->paginate(100);

    return view('frontend.Seller.dashboard.orders.orders', compact('orders'));
}


    // عرض تفاصيل الطلب
     public function show($orderId)
    {
        $userId = auth()->user()->user_id;
        $seller = Seller::where('user_id', $userId)->first();

        if (!$seller || !$seller->store_id) {
            abort(403, 'ليس لديك إذن للوصول لهذا الطلب');
        }

        $storeId = $seller->store_id;

        // تأكد أن الطلب فعلاً يحتوي على منتجات تخص متجرك
        $order = Order::whereHas('items.product', function ($query) use ($storeId) {
            $query->where('store_id', $storeId);
        })
        ->with([
            'items.product',
            'items.variant',
            'customer',
            'customer.user',
            'payments',
            'shipping',
            'addresses'
        ])
        ->findOrFail($orderId);

        return view('frontend.Seller.dashboard.orders.order_details', compact('order'));
    }

     // تحديث حالة الطلب أو حالة الدفع
public function updateStatus(Request $request, $orderId)
{
    $userId = auth()->user()->user_id;
    $seller = Seller::where('user_id', $userId)->first();

    if (!$seller || !$seller->store_id) {
        abort(403, 'ليس لديك إذن للوصول لهذا الطلب');
    }

    $storeId = $seller->store_id;

    $order = Order::whereHas('items.product', function ($query) use ($storeId) {
        $query->where('store_id', $storeId);
    })->with(['payments', 'shipping'])->findOrFail($orderId);

    // تحديث حالة الطلب
    if ($request->has('status')) {
        $status = $request->input('status');
        $order->status = $status;

        // تحديث جدول الشحن عند الحاجة
        if ($status == 'shipped') {
            if (!$order->shipping) {
                $order->shipping()->create([
                    'carrier' => 'شركة الشحن الافتراضية',
                    'tracking_number' => strtoupper(uniqid('TRK')),
                    'status' => 'shipped',
                    'estimated_delivery' => now()->addDays(3),
                    'shipping_cost' => $order->shipping_amount,
                ]);
            } else {
                $order->shipping->update([
                    'status' => 'shipped',
                    'tracking_number' => strtoupper(uniqid('TRK')),
                ]);
            }
        }

        if ($status == 'delivered' && $order->shipping) {
            $order->shipping->update([
                'status' => 'delivered',
                'actual_delivery' => now(),
            ]);
        }

        if ($status == 'cancelled' && $order->shipping) {
            $order->shipping->update(['status' => 'pending']);
        }
    }

    // تحديث حالة الدفع
    if ($request->has('payment_status')) {
        $paymentStatus = $request->input('payment_status');
        $order->payment_status = $paymentStatus;

        // تحديث جدول المدفوعات
        if ($order->payments->count() > 0) {
            $payment = $order->payments->first();
            if ($paymentStatus == 'paid') {
                $payment->update([
                    'status' => 'completed',
                    'payment_date' => now(),
                ]);
            } elseif ($paymentStatus == 'refunded') {
                $payment->update([
                    'status' => 'refunded',
                    'note' => 'تم استرجاع المبلغ للعميل',
                ]);
            } elseif ($paymentStatus == 'failed') {
                $payment->update(['status' => 'failed']);
            } else {
                $payment->update(['status' => 'pending']);
            }
        } else {
            // لو ما في مدفوعات مسبقة ننشئ سجل
            $order->payments()->create([
                'amount' => $order->total_amount,
                'total_amount' => $order->total_amount,
                'currency' => 'YER',
                'method' => 'cash',
                'status' => $paymentStatus == 'paid' ? 'completed' : 'pending',
                'payment_date' => $paymentStatus == 'paid' ? now() : null,
            ]);
        }
    }

    $order->save();

    return redirect()->back()->with('success', '✅ تم تحديث الطلب وجميع الجداول المرتبطة به بنجاح.');
}

}
