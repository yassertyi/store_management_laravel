<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderAddress;
use App\Models\Payment;
use App\Models\Address;
use App\Models\Store;
use App\Models\PaymentOption;
use App\Models\StorePaymentMethod;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Customer;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    // صفحة اتمام الشراء لجميع المتاجر
    public function index()
    {
        $user = Auth::user();
        
        // الحصول على عناصر السلة مع معلومات المتجر والمنتج
        $cartItems = CartItem::with(['store', 'product.images', 'variant'])
            ->where('user_id', $user->user_id)
            ->get()
            ->groupBy('store_id');

        if ($cartItems->isEmpty()) {
            return redirect()->route('front.cart.index')->with('error', 'سلة التسوق فارغة');
        }

        // حساب الإجماليات
        $grandTotal = $cartItems->sum(function($storeItems) {
            return $storeItems->sum(function($item) {
                return $item->price * $item->quantity;
            });
        });

        $totalItems = $cartItems->sum(function($storeItems) {
            return $storeItems->sum('quantity');
        });

        // عناوين المستخدم
        $addresses = Address::where('user_id', $user->user_id)->get();

        // خيارات الدفع النشطة
        $paymentOptions = PaymentOption::where('is_active', true)->get();

        // جمع طرق الدفع للمتاجر
        $storePaymentMethods = [];
        foreach ($cartItems as $storeId => $items) {
            $storePaymentMethods[$storeId] = StorePaymentMethod::with('paymentOption')
                ->where('store_id', $storeId)
                ->where('is_active', true)
                ->get();
        }

        // الكوبونات المتاحة
        $availableCoupons = Coupon::where('is_active', true)
            ->where(function($query) {
                $query->where('start_date', '<=', now())
                      ->orWhereNull('start_date');
            })
            ->where(function($query) {
                $query->where('expiry_date', '>=', now())
                      ->orWhereNull('expiry_date');
            })
            ->where(function($query) {
                $query->where('usage_limit', '>', DB::raw('used_count'))
                      ->orWhereNull('usage_limit');
            })
            ->get();

        return view('frontend.home.checkout.index', compact(
            'cartItems', 
            'grandTotal', 
            'totalItems', 
            'addresses',
            'paymentOptions',
            'storePaymentMethods',
            'availableCoupons'
        ));
    }

    // صفحة اتمام الشراء لمتجر معين
    public function storeCheckout($storeId)
    {
        $user = Auth::user();
        
        // التحقق من وجود المتجر
        $store = Store::findOrFail($storeId);

        // الحصول على عناصر السلة للمتجر المحدد فقط
        $cartItems = CartItem::with(['store', 'product.images', 'variant'])
            ->where('user_id', $user->user_id)
            ->where('store_id', $storeId)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('front.cart.index')->with('error', 'لا توجد منتجات من هذا المتجر في السلة');
        }

        // حساب الإجماليات
        $storeTotal = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        $totalItems = $cartItems->sum('quantity');

        // عناوين المستخدم
        $addresses = Address::where('user_id', $user->user_id)->get();

        // خيارات الدفع النشطة
        $paymentOptions = PaymentOption::where('is_active', true)->get();

        // طرق الدفع الخاصة بالمتجر
        $storePaymentMethods = StorePaymentMethod::with('paymentOption')
            ->where('store_id', $storeId)
            ->where('is_active', true)
            ->get();

        // الكوبونات المتاحة
        $availableCoupons = Coupon::where('is_active', true)
            ->where(function($query) {
                $query->where('start_date', '<=', now())
                      ->orWhereNull('start_date');
            })
            ->where(function($query) {
                $query->where('expiry_date', '>=', now())
                      ->orWhereNull('expiry_date');
            })
            ->where(function($query) {
                $query->where('usage_limit', '>', DB::raw('used_count'))
                      ->orWhereNull('usage_limit');
            })
            ->get();

        return view('frontend.home.checkout.store', compact(
            'cartItems', 
            'storeTotal', 
            'totalItems', 
            'addresses', 
            'store',
            'paymentOptions',
            'storePaymentMethods',
            'availableCoupons'
        ));
    }

    // معالجة عملية الشراء
    public function processCheckout(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'address_id' => 'required|exists:addresses,address_id',
            'payment_type' => 'required|in:online,cash',
            'store_payment_method_id' => 'nullable|required_if:payment_type,online|exists:store_payment_methods,spm_id',
            'notes' => 'nullable|string|max:500',
            'discount_amount' => 'nullable|numeric|min:0',
            'store_id' => 'nullable|exists:stores,store_id',
            'coupon_code' => 'nullable|exists:coupons,code'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // الحصول على customer_id من المستخدم
            $customer = Customer::where('user_id', $user->user_id)->first();
            if (!$customer) {
                throw new \Exception('لم يتم العثور على بيانات العميل');
            }

            // التحقق من صحة الكوبون إذا كان موجوداً
            $coupon = null;
            $discountAmount = $request->discount_amount ?? 0;
            
            if ($request->filled('coupon_code')) {
                $coupon = $this->validateCouponForCheckout($request->coupon_code, $request->discount_amount);
                
                if (!$coupon) {
                    throw new \Exception('كود الخصم غير صالح');
                }
            }

            // تحديد إذا كان الشراء لمتجر معين أو لجميع المتاجر
            $storeId = $request->input('store_id');
            
            if ($storeId) {
                // شراء من متجر معين
                $cartItems = CartItem::with(['store', 'product', 'variant'])
                    ->where('user_id', $user->user_id)
                    ->where('store_id', $storeId)
                    ->get();
                
                $order = $this->createOrderForStore($customer, $cartItems, $request, $coupon);
                
                if (!$order) {
                    throw new \Exception('فشل في إنشاء الطلب');
                }

                // تسجيل استخدام الكوبون إذا كان صالحاً
                if ($coupon && $discountAmount > 0) {
                    $this->recordCouponUsage($coupon, $user->user_id, $order->order_id);
                }

                // إرسال إشعار لصاحب المتجر
                $this->sendNewOrderNotification($order);

                // حذف عناصر السلة
                CartItem::where('user_id', $user->user_id)
                    ->where('store_id', $storeId)
                    ->delete();

                DB::commit();

                return redirect()->route('front.checkout.success', ['order' => $order->order_id])
                    ->with('success', 'تم إنشاء طلبك بنجاح!');

            } else {
                // شراء من جميع المتاجر (لاحقاً يمكن تطويره)
                throw new \Exception('الشراء من متاجر متعددة غير مدعوم حالياً');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء معالجة الطلب: ' . $e->getMessage())
                ->withInput();
        }
    }

    // التحقق من الكوبون أثناء عملية الشراء
    private function validateCouponForCheckout($couponCode, $discountAmount)
    {
        $coupon = Coupon::where('code', $couponCode)
            ->where('is_active', true)
            ->where(function($query) {
                $query->where('start_date', '<=', now())
                      ->orWhereNull('start_date');
            })
            ->where(function($query) {
                $query->where('expiry_date', '>=', now())
                      ->orWhereNull('expiry_date');
            })
            ->where(function($query) {
                $query->where('usage_limit', '>', DB::raw('used_count'))
                      ->orWhereNull('usage_limit');
            })
            ->first();

        return $coupon;
    }

    // إنشاء طلب لمتجر معين
    private function createOrderForStore($customer, $cartItems, $request, $coupon = null)
    {
        if ($cartItems->isEmpty()) {
            return null;
        }

        $store = $cartItems->first()->store;
        $address = Address::find($request->address_id);

        if (!$address) {
            throw new \Exception('العنوان المحدد غير موجود');
        }

        // حساب الإجماليات
        $subtotal = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        $discountAmount = $request->discount_amount ?? 0;
        $totalAmount = $subtotal - $discountAmount;

        // إنشاء الطلب
        $order = new Order();
        $order->customer_id = $customer->customer_id;
        $order->order_number = 'ORD-' . time() . '-' . rand(1000, 9999);
        $order->subtotal = $subtotal;
        $order->tax_amount = 0;
        $order->shipping_amount = 0;
        $order->discount_amount = $discountAmount;
        $order->total_amount = $totalAmount;
        $order->status = 'pending';
        $order->payment_status = 'pending';
        $order->notes = $request->notes;
        $order->save();

        // إضافة عناصر الطلب
        foreach ($cartItems as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->order_id;
            $orderItem->product_id = $item->product_id;
            $orderItem->variant_id = $item->variant_id;
            $orderItem->quantity = $item->quantity;
            $orderItem->unit_price = $item->price;
            $orderItem->total_price = $item->price * $item->quantity;
            $orderItem->save();
        }

        // إضافة عنوان الشحن
        $this->createOrderAddress($order, $address, 'shipping');

        // إنشاء سجل الدفع
        $this->createPayment($order, $request, $store->store_id);

        return $order;
    }

    // إنشاء عنوان الطلب
    private function createOrderAddress($order, $address, $type)
    {
        $orderAddress = new OrderAddress();
        $orderAddress->order_id = $order->order_id;
        $orderAddress->address_type = $type;
        $orderAddress->first_name = $address->first_name;
        $orderAddress->last_name = $address->last_name;
        $orderAddress->email = Auth::user()->email;
        $orderAddress->phone = $address->phone;
        $orderAddress->country = $address->country;
        $orderAddress->city = $address->city;
        $orderAddress->street = $address->street;
        $orderAddress->zip_code = $address->zip_code;
        $orderAddress->save();

        return $orderAddress;
    }

    // إنشاء سجل الدفع
    private function createPayment($order, $request, $storeId)
    {
        $payment = new Payment();
        $payment->order_id = $order->order_id;
        $payment->amount = $order->total_amount;
        $payment->discount = $order->discount_amount;
        $payment->total_amount = $order->total_amount;
        $payment->currency = 'USD';
        $payment->type = $request->payment_type;
        $payment->status = 'pending';
        $payment->payment_date = now();
        $payment->note = $request->notes;

        // إذا كان الدفع إلكتروني، إضافة طريقة الدفع
        if ($request->payment_type === 'online' && $request->filled('store_payment_method_id')) {
            $paymentMethod = StorePaymentMethod::find($request->store_payment_method_id);
            if ($paymentMethod) {
                $payment->store_payment_method_id = $paymentMethod->spm_id;
                $payment->method = $paymentMethod->paymentOption->method_name;
            }
        } else {
            $payment->method = 'الدفع عند الاستلام';
        }

        $payment->save();

        // تحديث حالة الدفع للطلب
        $order->payment_status = 'pending';
        $order->save();
    }

    // تسجيل استخدام الكوبون
    private function recordCouponUsage($coupon, $userId, $orderId)
    {
        $couponUsage = new CouponUsage();
        $couponUsage->coupon_id = $coupon->coupon_id;
        $couponUsage->user_id = $userId;
        $couponUsage->order_id = $orderId;
        $couponUsage->used_at = now();
        $couponUsage->save();

        // تحديث عدد مرات الاستخدام
        $coupon->increment('used_count');
    }

    // إرسال إشعار طلب جديد لصاحب المتجر
    private function sendNewOrderNotification($order)
    {
        try {
            // الحصول على أول منتج في الطلب للوصول إلى المتجر
            $firstOrderItem = $order->orderItems->first();
            if (!$firstOrderItem) {
                return;
            }

            $product = $firstOrderItem->product;
            $store = Store::with('user')->find($product->store_id);
            
            if ($store && $store->user) {
                $storeOwner = $store->user;
                
                // إنشاء الإشعار
                $notification = new Notification();
                $notification->user_id = $storeOwner->user_id;
                $notification->title = 'طلب جديد #' . $order->order_number;
                $notification->content = 'تم استلام طلب جديد من العميل ' . Auth::user()->name . ' بقيمة ' . number_format($order->total_amount, 2) . ' ر.ي';
                $notification->type = 'new_order';
                $notification->related_id = $order->order_id;
                $notification->related_type = Order::class;
                $notification->is_read = false;
                $notification->save();

                \Log::info('تم إرسال إشعار طلب جديد لصاحب المتجر: ' . $storeOwner->email);
            }
        } catch (\Exception $e) {
            \Log::error('فشل في إرسال إشعار الطلب الجديد: ' . $e->getMessage());
        }
    }

    // صفحة النجاح
    public function success(Order $order)
    {
        $user = Auth::user();
        $customer = Customer::where('user_id', $user->user_id)->first();
        
        if ($order->customer_id !== $customer->customer_id) {
            abort(403);
        }

        // تحميل بيانات الدفع والطلب
        $order->load(['payment.storePaymentMethod.paymentOption', 'orderItems.product.images', 'orderAddresses']);

        return view('frontend.home.checkout.success', compact('order'));
    }

    // صفحة الفشل
    public function failed()
    {
        return view('frontend.home.checkout.failed');
    }

    // التحقق من صحة الكوبون (AJAX) - محدث
    public function validateCoupon(Request $request)
    {
        \Log::info('Coupon Validation Request:', $request->all());

        $request->validate([
            'coupon_code' => 'required|string',
            'subtotal' => 'required|numeric'
        ]);

        $coupon = Coupon::where('code', $request->coupon_code)->first();

        \Log::info('Coupon Found:', $coupon ? $coupon->toArray() : ['message' => 'No coupon found']);

        if (!$coupon) {
            \Log::warning('Coupon not found with code: ' . $request->coupon_code);
            return response()->json([
                'valid' => false,
                'message' => 'كود الخصم غير موجود'
            ]);
        }

        if (!$coupon->is_active) {
            \Log::warning('Coupon is inactive: ' . $request->coupon_code);
            return response()->json([
                'valid' => false,
                'message' => 'كود الخصم غير مفعل'
            ]);
        }

        // التحقق من تاريخ البدء
        if ($coupon->start_date && Carbon::parse($coupon->start_date)->gt(now())) {
            \Log::warning('Coupon not started yet: ' . $request->coupon_code);
            return response()->json([
                'valid' => false,
                'message' => 'كود الخصم لم يبدأ بعد. يبدأ في ' . $coupon->start_date->format('Y-m-d')
            ]);
        }

        // التحقق من تاريخ الانتهاء
        if ($coupon->expiry_date && Carbon::parse($coupon->expiry_date)->lt(now())) {
            \Log::warning('Coupon expired: ' . $request->coupon_code);
            return response()->json([
                'valid' => false,
                'message' => 'كود الخصم منتهي الصلاحية. انتهى في ' . $coupon->expiry_date->format('Y-m-d')
            ]);
        }

        // التحقق من حد الاستخدام
        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            \Log::warning('Coupon usage limit reached: ' . $request->coupon_code);
            return response()->json([
                'valid' => false,
                'message' => 'تم استخدام الحد الأقصى لمرات الاستخدام لهذا الكوبون'
            ]);
        }

        // التحقق من الحد الأدنى للطلب
        if ($request->subtotal < $coupon->min_order_amount) {
            \Log::warning('Minimum order amount not met for coupon: ' . $request->coupon_code);
            return response()->json([
                'valid' => false,
                'message' => 'الحد الأدنى للطلب لاستخدام هذا الكوبون هو ' . number_format($coupon->min_order_amount, 2) . ' ر.ي'
            ]);
        }

        $discountAmount = $this->calculateDiscount($coupon, $request->subtotal);

        \Log::info('Coupon applied successfully:', [
            'coupon_code' => $request->coupon_code,
            'discount_amount' => $discountAmount,
            'subtotal' => $request->subtotal
        ]);

        return response()->json([
            'valid' => true,
            'discount_amount' => $discountAmount,
            'discount_type' => $coupon->discount_type,
            'discount_value' => $coupon->discount_value,
            'message' => 'كود الخصم صالح! خصم: ' . number_format($discountAmount, 2) . ' ر.ي'
        ]);
    }

    // حساب الخصم
    private function calculateDiscount($coupon, $subtotal)
    {
        if ($coupon->discount_type === 'percentage') {
            $discount = ($subtotal * $coupon->discount_value) / 100;
            return $coupon->max_discount_amount ? min($discount, $coupon->max_discount_amount) : $discount;
        } else {
            return $coupon->discount_value;
        }
    }

    // دالة مساعدة للتحقق من الكوبونات في قاعدة البيانات
    public function debugCoupons()
    {
        $coupons = Coupon::all();
        return response()->json($coupons);
    }
}