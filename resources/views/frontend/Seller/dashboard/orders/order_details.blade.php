@extends('frontend.Seller.dashboard.index')

@section('title', 'تفاصيل الطلب')
@section('page_title', 'تفاصيل الطلب')

@section('contects')
    <br><br><br>
    <div class="dashboard-main-content">
        <div class="container-fluid">
            {{-- رسائل النجاح والخطأ --}}
            @if (session('success'))
                <div id="flash-message" class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                </div>
            @endif

            @if (session('error'))
                <div id="flash-message" class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                </div>
            @endif

            <div class="form-box">
                <div class="form-title-wrap d-flex justify-content-between align-items-center mb-4">
                    <h3 class="title">تفاصيل الطلب</h3>
                    <a href="{{ url()->previous() ?? route('seller.orders.index') }}"
                        class="theme-btn theme-btn-small bg-secondary me-2">
                        <i class="la la-arrow-right"></i> رجوع
                    </a>

                </div>

                <div class="form-content">
                    {{-- أزرار التحكم في حالة الطلب --}}
                    <div class="action-buttons mb-4">
                        <div class="d-flex flex-wrap gap-2">
                            @if ($order->status != 'cancelled' && $order->status != 'delivered')
                                <form action="{{ route('seller.orders.updateStatus', $order->order_id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('هل أنت متأكد من إلغاء الطلب؟')">
                                        <i class="la la-times-circle me-1"></i> إلغاء الطلب
                                    </button>
                                </form>
                            @endif

                            @if ($order->status == 'pending')
                                <form action="{{ route('seller.orders.updateStatus', $order->order_id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="processing">
                                    <button type="submit" class="btn btn-info text-white">
                                        <i class="la la-cog me-1"></i> بدء المعالجة
                                    </button>
                                </form>
                            @endif

                            @if ($order->status == 'processing')
                                <form action="{{ route('seller.orders.updateStatus', $order->order_id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="shipped">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="la la-shipping-fast me-1"></i> تم الشحن
                                    </button>
                                </form>
                            @endif

                            @if ($order->status == 'shipped')
                                <form action="{{ route('seller.orders.updateStatus', $order->order_id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="delivered">
                                    <button type="submit" class="btn btn-success">
                                        <i class="la la-check-circle me-1"></i> تم التسليم
                                    </button>
                                </form>
                            @endif

                            @if ($order->payment_status == 'pending')
                                <form action="{{ route('seller.orders.updateStatus', $order->order_id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="payment_status" value="paid">
                                    <button type="submit" class="btn btn-success">
                                        <i class="la la-money-bill-wave me-1"></i> تأكيد الدفع
                                    </button>
                                </form>
                            @endif

                            @if ($order->payment_status == 'paid' && $order->status != 'cancelled')
                                <form action="{{ route('seller.orders.updateStatus', $order->order_id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="payment_status" value="refunded">
                                    <button type="submit" class="btn btn-warning"
                                        onclick="return confirm('هل أنت متأكد من استرجاع المبلغ؟')">
                                        <i class="la la-undo me-1"></i> استرجاع المبلغ
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="order-grid-layout">
                        {{-- صندوق تفاصيل الطلب --}}
                        <div class="order-card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <i class="la la-shopping-cart me-2"></i>معلومات الطلب
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="order-details-grid">

                                    {{-- رقم الطلب --}}
                                    <div class="detail-item">
                                        <span class="detail-label">رقم الطلب:</span>
                                        <span class="detail-value">{{ $order->order_number }}</span>
                                    </div>

                                    {{-- المجموع --}}
                                    <div class="detail-item">
                                        <span class="detail-label">المجموع:</span>
                                        <span class="detail-value">{{ $order->total_amount }} ر.ي</span>
                                    </div>

                                    {{-- المجموع الفرعي --}}
                                    <div class="detail-item">
                                        <span class="detail-label">المجموع الفرعي:</span>
                                        <span class="detail-value">{{ $order->subtotal }} ر.ي</span>
                                    </div>

                                    {{-- ضريبة القيمة المضافة --}}
                                    <div class="detail-item">
                                        <span class="detail-label">ضريبة القيمة المضافة:</span>
                                        <span class="detail-value">{{ $order->tax_amount }} ر.ي</span>
                                    </div>

                                    {{-- تكلفة الشحن --}}
                                    <div class="detail-item">
                                        <span class="detail-label">تكلفة الشحن:</span>
                                        <span class="detail-value">{{ $order->shipping_amount }} ر.ي</span>
                                    </div>

                                    {{-- مبلغ الخصم --}}
                                    <div class="detail-item">
                                        <span class="detail-label">مبلغ الخصم:</span>
                                        <span class="detail-value">{{ $order->discount_amount }} ر.ي</span>
                                    </div>

                                    {{-- حالة الطلب --}}
                                    <div class="detail-item">
                                        <span class="detail-label">الحالة:</span>
                                        <span class="detail-value">
                                            <span
                                                class="badge 
                        @if ($order->status == 'pending') text-bg-warning
                        @elseif($order->status == 'processing') text-bg-info
                        @elseif($order->status == 'shipped') text-bg-primary
                        @elseif($order->status == 'delivered') text-bg-success
                        @elseif($order->status == 'cancelled') text-bg-danger @endif py-1 px-2">
                                                @if ($order->status == 'pending')
                                                    معلق
                                                @elseif($order->status == 'processing')
                                                    قيد المعالجة
                                                @elseif($order->status == 'shipped')
                                                    تم الشحن
                                                @elseif($order->status == 'delivered')
                                                    تم التسليم
                                                @elseif($order->status == 'cancelled')
                                                    ملغي
                                                @endif
                                            </span>
                                        </span>
                                    </div>

                                    {{-- تاريخ الإنشاء --}}
                                    <div class="detail-item">
                                        <span class="detail-label">تاريخ الإنشاء:</span>
                                        <span
                                            class="detail-value">{{ \Carbon\Carbon::parse($order->created_at)->format('Y/m/d H:i') }}</span>
                                    </div>

                                    {{-- آخر تحديث --}}
                                    <div class="detail-item">
                                        <span class="detail-label">آخر تحديث:</span>
                                        <span
                                            class="detail-value">{{ \Carbon\Carbon::parse($order->updated_at)->format('Y/m/d H:i') }}</span>
                                    </div>

                                    {{-- حالة الدفع --}}
                                    <div class="detail-item">
                                        <span class="detail-label">حالة الدفع:</span>
                                        <span class="detail-value">
                                            <span
                                                class="badge 
                        @if ($order->payment_status == 'pending') text-bg-warning
                        @elseif($order->payment_status == 'paid') text-bg-success
                        @elseif($order->payment_status == 'failed') text-bg-danger
                        @elseif($order->payment_status == 'refunded') text-bg-secondary @endif py-1 px-2">
                                                @if ($order->payment_status == 'pending')
                                                    معلق
                                                @elseif($order->payment_status == 'paid')
                                                    تم الدفع
                                                @elseif($order->payment_status == 'failed')
                                                    فشل الدفع
                                                @elseif($order->payment_status == 'refunded')
                                                    مسترجع
                                                @endif
                                            </span>
                                        </span>
                                    </div>

                                    {{-- ملاحظات الطلب --}}
                                    @if ($order->notes)
                                        <div class="detail-item">
                                            <span class="detail-label">ملاحظات الطلب:</span>
                                            <span class="detail-value">{{ $order->notes }}</span>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>


                        {{-- صندوق معلومات العميل --}}
                        <div class="order-card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <i class="la la-user me-2"></i>معلومات العميل
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="customer-info">
                                    <div class="customer-avatar text-center mb-3">
                                        @if ($order->customer->user->profile_photo)
                                            <img src="{{ asset($order->customer->user->profile_photo) }}"
                                                class="img-fluid rounded-circle shadow-sm" style="max-height: 100px;">
                                        @else
                                            <img src="{{ asset('static/images/avatar.jpeg') }}"
                                                class="img-fluid rounded-circle shadow-sm" style="max-height: 100px;">
                                        @endif
                                    </div>

                                    <div class="customer-details">
                                        <div class="detail-item">
                                            <span class="detail-label">الاسم:</span>
                                            <span class="detail-value">{{ $order->customer->user->name }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">البريد الإلكتروني:</span>
                                            <span class="detail-value">{{ $order->customer->user->email }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">رقم الهاتف:</span>
                                            <span class="detail-value">{{ $order->customer->user->phone }}</span>
                                        </div>

                                        <div class="detail-item">
                                            <span class="detail-label">الحالة:</span>
                                            <span class="detail-value">
                                                @if ($order->customer->user->active)
                                                    نشط
                                                @else
                                                    غير نشط
                                                @endif
                                            </span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">تاريخ التسجيل:</span>
                                            <span
                                                class="detail-value">{{ $order->customer->user->created_at->format('Y/m/d') }}</span>
                                        </div>

                                        <div class="detail-item">
                                            <span class="detail-label">آخر دخول:</span>
                                            <span class="detail-value">
                                                @if ($order->customer->user->last_login_at)
                                                    {{ \Carbon\Carbon::parse($order->customer->user->last_login_at)->format('Y/m/d H:i') }}
                                                @else
                                                    غير متوفر
                                                @endif
                                            </span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- صندوق معلومات الشحن --}}
                        <div class="order-card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <i class="la la-truck me-2"></i>معلومات الشحن
                                </h4>
                            </div>
                            <div class="card-body">
                                @if ($order->shipping)
                                    <div class="shipping-details">
                                        <div class="detail-item">
                                            <span class="detail-label">شركة الشحن:</span>
                                            <span
                                                class="detail-value">{{ $order->shipping->carrier ?? 'غير محدد' }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">رقم التتبع:</span>
                                            <span class="detail-value">
                                                @if ($order->shipping->tracking_number)
                                                    {{ $order->shipping->tracking_number }}
                                                @else
                                                    غير متوفر
                                                @endif
                                            </span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">حالة الشحن:</span>
                                            <span class="detail-value">
                                                <span
                                                    class="badge 
                                            @if ($order->shipping->status == 'pending') text-bg-warning
                                            @elseif($order->shipping->status == 'shipped') text-bg-primary
                                            @elseif($order->shipping->status == 'in_transit') text-bg-info
                                            @elseif($order->shipping->status == 'delivered') text-bg-success @endif py-1 px-2">
                                                    @if ($order->shipping->status == 'pending')
                                                        قيد الانتظار
                                                    @elseif($order->shipping->status == 'shipped')
                                                        تم الشحن
                                                    @elseif($order->shipping->status == 'in_transit')
                                                        قيد التوصيل
                                                    @elseif($order->shipping->status == 'delivered')
                                                        تم التسليم
                                                    @else
                                                        غير محدد
                                                    @endif
                                                </span>
                                            </span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">تكلفة الشحن:</span>
                                            <span class="detail-value">{{ $order->shipping->shipping_cost ?? 0 }}
                                                ر.س</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">موعد التسليم المتوقع:</span>
                                            <span class="detail-value">
                                                @if ($order->shipping->estimated_delivery)
                                                    {{ \Carbon\Carbon::parse($order->shipping->estimated_delivery)->format('Y/m/d') }}
                                                @else
                                                    غير محدد
                                                @endif
                                            </span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">تاريخ التسليم الفعلي:</span>
                                            <span class="detail-value">
                                                @if ($order->shipping->actual_delivery)
                                                    {{ \Carbon\Carbon::parse($order->shipping->actual_delivery)->format('Y/m/d H:i') }}
                                                @else
                                                    لم يتم التسليم بعد
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-info text-center">لا توجد معلومات شحن متاحة</div>
                                @endif
                            </div>
                        </div>

                        {{-- صندوق عنوان الشحن --}}
                        <div class="order-card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <i class="la la-map-marker me-2"></i>عنوان الشحن
                                </h4>
                            </div>
                            <div class="card-body">
                                @php
                                    $shippingAddress = $order->addresses->where('address_type', 'shipping')->first();
                                @endphp

                                @if ($shippingAddress)
                                    <div class="address-details">
                                        <div class="detail-item">
                                            <span class="detail-label">الاسم:</span>
                                            <span class="detail-value">{{ $shippingAddress->first_name }}
                                                {{ $shippingAddress->last_name }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">البريد الإلكتروني:</span>
                                            <span class="detail-value">{{ $shippingAddress->email }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">رقم الهاتف:</span>
                                            <span class="detail-value">{{ $shippingAddress->phone }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">البلد:</span>
                                            <span class="detail-value">{{ $shippingAddress->country }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">المدينة:</span>
                                            <span class="detail-value">{{ $shippingAddress->city }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">الشارع:</span>
                                            <span class="detail-value">{{ $shippingAddress->street }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">الرمز البريدي:</span>
                                            <span class="detail-value">{{ $shippingAddress->zip_code }}</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-info text-center">لا يوجد عنوان شحن متاح</div>
                                @endif
                            </div>
                        </div>

                        {{-- صندوق المنتجات --}}
                        <div class="order-card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <i class="la la-shopping-bag me-2"></i>المنتجات في الطلب
                                </h4>
                            </div>
                            <div class="card-body">
                                @php
                                    $storeId = auth()->user()->seller->store_id ?? null;
                                    $storeItems = $order->items->filter(
                                        fn($item) => $item->product && $item->product->store_id == $storeId,
                                    );
                                    $storeTotal = $storeItems->sum('total_price');
                                @endphp

                                @if ($storeItems->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>المنتج</th>
                                                    <th>SKU</th>
                                                    <th>الكمية</th>
                                                    <th>السعر</th>
                                                    <th>الإجمالي</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($storeItems as $item)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                @if ($item->product->images->count() > 0)
                                                                    <img src="{{ asset($item->product->images->first()->image_path) }}"
                                                                        class="img-thumbnail me-2"
                                                                        style="width: 50px; height: 50px; object-fit: cover;">
                                                                @endif
                                                                <div>
                                                                    <div>{{ $item->product->title }}</div>
                                                                    @if ($item->variant)
                                                                        <small class="text-muted">النوع:
                                                                            {{ $item->variant->name }}</small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ $item->product->sku }}</td>
                                                        <td>{{ $item->quantity }}</td>
                                                        <td>{{ $item->unit_price }} ر.س</td>
                                                        <td>{{ $item->total_price }} ر.س</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="table-active">
                                                    <th colspan="4" class="text-start">المجموع</th>
                                                    <th>{{ $storeTotal }} ر.س</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info text-center">لا توجد منتجات خاصة بمتجرك في هذا الطلب</div>
                                @endif
                            </div>
                        </div>

                        {{-- صندوق تفاصيل الدفع --}}
                        <div class="order-card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <i class="la la-credit-card me-2"></i>تفاصيل الدفع
                                </h4>
                            </div>
                            <div class="card-body">
                                @php
                                    // أخذ أول دفعة مرتبطة بالطلب
                                    $payment = $order->payments->first();
                                @endphp

                                @if ($payment)
                                    <div class="payment-details">
                                        {{-- طريقة الدفع --}}
                                        <div class="detail-item">
                                            <span class="detail-label">طريقة الدفع:</span>
                                            <span class="detail-value">
                                                {{ $payment->storePaymentMethod?->paymentOption?->method_name ?? ($payment->method ?? 'غير محدد') }}
                                            </span>
                                        </div>

                                        {{-- المبلغ المدفوع --}}
                                        <div class="detail-item">
                                            <span class="detail-label">المبلغ المدفوع:</span>
                                            <span class="detail-value">{{ $payment->amount ?? 0 }} ر.س</span>
                                        </div>

                                        {{-- المتبقي --}}
                                        <div class="detail-item">
                                            <span class="detail-label">المتبقي:</span>
                                            <span
                                                class="detail-value">{{ $order->total_amount - ($payment->amount ?? 0) }}
                                                ر.س</span>
                                        </div>

                                        {{-- معرف المعاملة --}}
                                        <div class="detail-item">
                                            <span class="detail-label">معرف المعاملة:</span>
                                            <span
                                                class="detail-value">{{ $payment->transaction_id ?? 'غير متوفر' }}</span>
                                        </div>

                                        {{-- نوع الدفع --}}
                                        <div class="detail-item">
                                            <span class="detail-label">نوع الدفع:</span>
                                            <span class="detail-value">
                                                @if ($payment->type == 'online')
                                                    إلكتروني
                                                @elseif ($payment->type == 'cash')
                                                    نقدي
                                                @else
                                                    غير محدد
                                                @endif
                                            </span>
                                        </div>

                                        {{-- تاريخ الدفع --}}
                                        <div class="detail-item">
                                            <span class="detail-label">تاريخ الدفع:</span>
                                            <span class="detail-value">
                                                {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('Y/m/d H:i') : 'غير محدد' }}
                                            </span>
                                        </div>

                                        {{-- حالة الدفع --}}
                                        <div class="detail-item">
                                            <span class="detail-label">حالة الدفع:</span>
                                            <span class="detail-value">
                                                <span
                                                    class="badge 
                            @if ($payment->status == 'pending') text-bg-warning
                            @elseif ($payment->status == 'completed') text-bg-success
                            @elseif ($payment->status == 'failed') text-bg-danger
                            @elseif ($payment->status == 'refunded') text-bg-secondary @endif py-1 px-2">
                                                    @if ($payment->status == 'pending')
                                                        قيد الانتظار
                                                    @elseif ($payment->status == 'completed')
                                                        مكتمل
                                                    @elseif ($payment->status == 'failed')
                                                        فشل
                                                    @elseif ($payment->status == 'refunded')
                                                        تم الاسترجاع
                                                    @else
                                                        غير محدد
                                                    @endif
                                                </span>
                                            </span>
                                        </div>

                                        {{-- ملاحظات الدفع --}}
                                        @if ($payment->note)
                                            <div class="detail-item">
                                                <span class="detail-label">ملاحظات الدفع:</span>
                                                <span class="detail-value">{{ $payment->note }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-muted">لا توجد دفعات مرتبطة بهذا الطلب بعد.</p>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .order-grid-layout {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .order-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
        }

        .card-title {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: #2d3e50;
        }

        .card-body {
            padding: 20px;
        }

        .order-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #f1f1f1;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #555;
        }

        .detail-value {
            color: #333;
        }

        .customer-info {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .customer-details {
            width: 100%;
            margin-top: 15px;
        }

        .payment-details,
        .shipping-details,
        .address-details {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .action-buttons {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #eee;
        }

        @media (max-width: 1200px) {
            .order-grid-layout {
                grid-template-columns: 1fr;
            }

            .order-details-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons .d-flex {
                flex-direction: column;
            }

            .action-buttons .btn {
                margin-bottom: 10px;
                width: 100%;
            }
        }
    </style>
@endsection

@section('script_sdebar')
    <script>
        setTimeout(function() {
            const flash = document.getElementById('flash-message');
            if (flash) {
                flash.style.transition = "opacity 0.5s ease";
                flash.style.opacity = '0';
                setTimeout(() => flash.remove(), 500);
            }
        }, 3000);

        // تأكيد قبل تغيير حالة الطلب
        document.addEventListener('DOMContentLoaded', function() {
            const statusForms = document.querySelectorAll('form');
            statusForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('هل أنت متأكد من تغيير الحالة؟')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
@endsection
