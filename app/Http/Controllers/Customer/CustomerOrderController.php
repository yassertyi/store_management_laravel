<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use App\Models\OrderAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerOrderController extends Controller
{
    // عرض كل الطلبات الخاصة بالعميل مع الفلترة
    public function index(Request $request)
    {
        $user = Auth::user();

        // بناء الاستعلام الأساسي
        $query = $user->orders()->with(['items.product.store', 'shipping']);

        // تطبيق الفلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // تطبيق الفلترة حسب حالة الدفع
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // تطبيق الفلترة حسب التاريخ
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // جلب الطلبات مع التصنيف
        $orders = $query->latest()->paginate(10);

        return view('frontend.customers.dashboard.orders.index', compact('orders'));
    }

    // عرض تفاصيل الطلب
    public function show(Order $order)
    {
        $user = Auth::user();

        // التأكد أن الطلب يخص العميل الحالي
        if ($order->customer_id !== $user->customer->customer_id) {
            abort(403, 'غير مسموح بالوصول لهذا الطلب');
        }

        // تحميل العلاقات اللازمة
        $order->load([
            'items.product.images',
            'items.product.store',
            'items.variant',
            'addresses',
            'payments.storePaymentMethod.paymentOption',
            'shipping'
        ]);

        // جلب التقييمات المرتبطة بالطلب والعميل
        $reviews = Review::where('order_id', $order->order_id)
            ->where('user_id', $user->user_id)
            ->get()
            ->keyBy('product_id');

        return view('frontend.customers.dashboard.orders.show', compact('order', 'reviews'));
    }

    // إلغاء الطلب
    public function cancel(Request $request, Order $order)
    {
        $user = Auth::user();

        // التأكد أن الطلب يخص العميل الحالي
        if ($order->customer_id !== $user->customer->customer_id) {
            abort(403, 'غير مسموح بالوصول لهذا الطلب');
        }

        // الشروط المسموح فيها بالإلغاء
        $allowedStatuses = ['pending', 'processing'];
        
        if (!in_array($order->status, $allowedStatuses)) {
            return redirect()->back()
                ->with('error', 'لا يمكن إلغاء الطلب في مرحلته الحالية');
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        DB::transaction(function () use ($order, $request) {
            // تحديث حالة الطلب
            $order->update([
                'status' => 'cancelled',
                'notes' => ($order->notes ? $order->notes . "\n" : '') . 
                          "تم الإلغاء من قبل العميل - السبب: " . $request->cancellation_reason . " - التاريخ: " . now()
            ]);

            // إذا كان هناك شحن، تحديث حالته
            if ($order->shipping) {
                $order->shipping->update(['status' => 'cancelled']);
            }

            // إذا كان هناك مدفوعات، تحديث حالتها
            if ($order->payments->count() > 0) {
                $payment = $order->payments->first();
                if ($payment->status == 'completed') {
                    // إذا كان تم الدفع، نغير الحالة إلى مسترجع
                    $payment->update([
                        'status' => 'refunded',
                        'note' => 'تم الاسترجاع بسبب إلغاء الطلب من قبل العميل - ' . now()
                    ]);
                } else {
                    $payment->update(['status' => 'cancelled']);
                }
            }

            // TODO: إرسال إشعار للمسؤول والبائع
            // TODO: إرجاع الكميات إلى المخزون
        });

        return redirect()->back()
            ->with('success', 'تم إلغاء الطلب بنجاح وسيتم معالجة الاسترجاع خلال 24 ساعة');
    }

    // تحديث عنوان الشحن
    public function updateShippingAddress(Request $request, Order $order)
    {
        $user = Auth::user();

        // التأكد أن الطلب يخص العميل الحالي
        if ($order->customer_id !== $user->customer->customer_id) {
            abort(403, 'غير مسموح بالوصول لهذا الطلب');
        }

        // يمكن تعديل العنوان فقط قبل الشحن
        if (!in_array($order->status, ['pending', 'processing'])) {
            return redirect()->back()
                ->with('error', 'لا يمكن تعديل العنوان بعد بدء الشحن');
        }

        $request->validate([
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'phone' => 'required|string|max:20'
        ]);

        $shippingAddress = $order->addresses()
            ->where('address_type', 'shipping')
            ->first();

        if ($shippingAddress) {
            $shippingAddress->update([
                'street' => $request->street,
                'city' => $request->city,
                'country' => $request->country,
                'zip_code' => $request->zip_code,
                'phone' => $request->phone
            ]);

            // إضافة ملاحظة عن التعديل
            $order->update([
                'notes' => ($order->notes ? $order->notes . "\n" : '') . 
                          "تم تعديل عنوان الشحن من قبل العميل في: " . now()->format('Y-m-d H:i')
            ]);
        }

        return redirect()->back()
            ->with('success', 'تم تحديث عنوان الشحن بنجاح');
    }

    // إضافة ملاحظة للطلب
    public function addNote(Request $request, Order $order)
    {
        $user = Auth::user();

        // التأكد أن الطلب يخص العميل الحالي
        if ($order->customer_id !== $user->customer->customer_id) {
            abort(403, 'غير مسموح بالوصول لهذا الطلب');
        }

        $request->validate([
            'note' => 'required|string|max:1000'
        ]);

        $order->update([
            'notes' => ($order->notes ? $order->notes . "\n" : '') . 
                      "ملاحظة من العميل [" . now()->format('Y-m-d H:i') . "]: " . $request->note
        ]);

        return redirect()->back()
            ->with('success', 'تم إضافة الملاحظة بنجاح');
    }
}