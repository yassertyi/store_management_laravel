<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Seller;
use App\Services\UserActivityService;

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

        // تسجيل نشاط عرض الطلبات
        UserActivityService::log(
            $userId,
            'view_orders',
            'قام بعرض قائمة طلبات المتجر',
            $request->ip(),
            $request->userAgent()
        );

        return view('frontend.Seller.dashboard.orders.orders', compact('orders'));
    }

    // عرض تفاصيل الطلب
    public function show(Request $request, $orderId)
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

        // تسجيل نشاط عرض تفاصيل الطلب
        UserActivityService::log(
            $userId,
            'view_order_details',
            "قام بعرض تفاصيل الطلب رقم #{$order->order_number}",
            $request->ip(),
            $request->userAgent()
        );

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

        $oldStatus = $order->status;
        $oldPaymentStatus = $order->payment_status;
        $changes = [];

        // تحديث حالة الطلب
        if ($request->has('status') && $request->status != $order->status) {
            $status = $request->input('status');
            $order->status = $status;
            $changes[] = "حالة الطلب: {$oldStatus} → {$status}";

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
                    $changes[] = "تم إنشاء بيانات الشحن برقم تتبع: {$order->shipping->tracking_number}";
                } else {
                    $order->shipping->update([
                        'status' => 'shipped',
                        'tracking_number' => strtoupper(uniqid('TRK')),
                    ]);
                    $changes[] = "تم تحديث بيانات الشحن";
                }
            }

            if ($status == 'delivered' && $order->shipping) {
                $order->shipping->update([
                    'status' => 'delivered',
                    'actual_delivery' => now(),
                ]);
                $changes[] = "تم تسليم الطلب";
            }

            if ($status == 'cancelled' && $order->shipping) {
                $order->shipping->update(['status' => 'pending']);
                $changes[] = "تم إلغاء الطلب";
            }
        }

        // تحديث حالة الدفع
        if ($request->has('payment_status') && $request->payment_status != $order->payment_status) {
            $paymentStatus = $request->input('payment_status');
            $order->payment_status = $paymentStatus;
            $changes[] = "حالة الدفع: {$oldPaymentStatus} → {$paymentStatus}";

            // تحديث جدول المدفوعات
            if ($order->payments->count() > 0) {
                $payment = $order->payments->first();
                $oldPaymentStatus = $payment->status;
                
                if ($paymentStatus == 'paid') {
                    $payment->update([
                        'status' => 'completed',
                        'payment_date' => now(),
                    ]);
                    $changes[] = "تم تأكيد الدفع";
                } elseif ($paymentStatus == 'refunded') {
                    $payment->update([
                        'status' => 'refunded',
                        'note' => 'تم استرجاع المبلغ للعميل',
                    ]);
                    $changes[] = "تم استرجاع المبلغ";
                } elseif ($paymentStatus == 'failed') {
                    $payment->update(['status' => 'failed']);
                    $changes[] = "فشل في عملية الدفع";
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
                $changes[] = "تم إنشاء سجل دفع جديد";
            }
        }

        $order->save();

        // تسجيل نشاط تحديث الطلب
        if (!empty($changes)) {
            $description = "قام بتحديث الطلب رقم #{$order->order_number}: " . implode('، ', $changes);
            
            UserActivityService::log(
                $userId,
                'update_order',
                $description,
                $request->ip(),
                $request->userAgent()
            );
        }

        return redirect()->back()->with('success', '✅ تم تحديث الطلب وجميع الجداول المرتبطة به بنجاح.');
    }

    // طرق إضافية لتسجيل النشاطات

    // طباعة فاتورة الطلب
    public function printInvoice(Request $request, $orderId)
    {
        $userId = auth()->user()->user_id;
        $seller = Seller::where('user_id', $userId)->first();

        if (!$seller || !$seller->store_id) {
            abort(403, 'ليس لديك إذن للوصول لهذا الطلب');
        }

        $storeId = $seller->store_id;

        $order = Order::whereHas('items.product', function ($query) use ($storeId) {
            $query->where('store_id', $storeId);
        })->with(['items.product', 'customer.user'])->findOrFail($orderId);

        // تسجيل نشاط طباعة الفاتورة
        UserActivityService::log(
            $userId,
            'print_invoice',
            "قام بطباعة فاتورة الطلب رقم #{$order->order_number}",
            $request->ip(),
            $request->userAgent()
        );

        // هنا كود الطباعة
        return view();
    }

    // إضافة ملاحظة للطلب
    public function addNote(Request $request, $orderId)
    {
        $userId = auth()->user()->user_id;
        $seller = Seller::where('user_id', $userId)->first();

        if (!$seller || !$seller->store_id) {
            abort(403, 'ليس لديك إذن للوصول لهذا الطلب');
        }

        $storeId = $seller->store_id;

        $order = Order::whereHas('items.product', function ($query) use ($storeId) {
            $query->where('store_id', $storeId);
        })->findOrFail($orderId);

        $request->validate([
            'note' => 'required|string|max:500'
        ]);

        $order->update([
            'notes' => $request->note
        ]);

        // تسجيل نشاط إضافة ملاحظة
        UserActivityService::log(
            $userId,
            'add_order_note',
            "قام بإضافة ملاحظة على الطلب رقم #{$order->order_number}: " . substr($request->note, 0, 100),
            $request->ip(),
            $request->userAgent()
        );

        return redirect()->back()->with('success', '✅ تم إضافة الملاحظة بنجاح.');
    }

    // إرسال إشعار للعميل
    public function sendNotification(Request $request, $orderId)
    {
        $userId = auth()->user()->user_id;
        $seller = Seller::where('user_id', $userId)->first();

        if (!$seller || !$seller->store_id) {
            abort(403, 'ليس لديك إذن للوصول لهذا الطلب');
        }

        $storeId = $seller->store_id;

        $order = Order::whereHas('items.product', function ($query) use ($storeId) {
            $query->where('store_id', $storeId);
        })->with(['customer.user'])->findOrFail($orderId);

        $request->validate([
            'notification_type' => 'required|in:status_update,shipping_update,general',
            'message' => 'required|string|max:255'
        ]);

        // هنا كود إرسال الإشعار للعميل

        // تسجيل نشاط إرسال إشعار
        UserActivityService::log(
            $userId,
            'send_notification',
            "قام بإرسال إشعار {$request->notification_type} للعميل بخصوص الطلب رقم #{$order->order_number}",
            $request->ip(),
            $request->userAgent()
        );

        return redirect()->back()->with('success', '✅ تم إرسال الإشعار للعميل بنجاح.');
    }
}