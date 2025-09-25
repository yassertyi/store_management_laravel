@extends('frontend.customers.dashboard.index')

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

            {{-- أزرار الإجراءات --}}
            @if (in_array($order->status, ['pending', 'processing']))
            <div class="action-buttons mb-4">
                <div class="row g-3">
                    {{-- زر إلغاء الطلب --}}
                    <div class="col-md-4">
                        <button type="button" class="btn btn-danger w-100" onclick="openModal('cancelOrderModal')">
                            <i class="la la-times-circle"></i> إلغاء الطلب
                        </button>
                    </div>
                    
                    {{-- زر تعديل العنوان --}}
                    <div class="col-md-4">
                        <button type="button" class="btn btn-warning w-100" onclick="openModal('editAddressModal')">
                            <i class="la la-edit"></i> تعديل العنوان
                        </button>
                    </div>
                    
                    {{-- زر إضافة ملاحظة --}}
                    <div class="col-md-4">
                        <button type="button" class="btn btn-info w-100" onclick="openModal('addNoteModal')">
                            <i class="la la-sticky-note"></i> إضافة ملاحظة
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <div class="form-box">
                <div class="form-title-wrap d-flex justify-content-between align-items-center mb-4">
                    <h3 class="title">تفاصيل الطلب #{{ $order->order_number }}</h3>
                    <div>
                        <a href="{{ route('customer.orders.index') }}" class="theme-btn theme-btn-small bg-secondary me-2">
                            <i class="la la-arrow-right"></i> رجوع للطلبات
                        </a>
                        <button onclick="printOrder()" class="theme-btn theme-btn-small bg-primary">
                            <i class="la la-print"></i> طباعة
                        </button>
                    </div>
                </div>

                <div class="form-content">
                    <div class="order-grid-layout">
                        
                        {{-- صندوق تفاصيل الطلب --}}
                        <div class="order-card full-width">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <i class="la la-shopping-cart me-2"></i>معلومات الطلب
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="order-details-grid">
                                    <div class="detail-item">
                                        <span class="detail-label">رقم الطلب:</span>
                                        <span class="detail-value">{{ $order->order_number }}</span>
                                    </div>

                                    <div class="detail-item">
                                        <span class="detail-label">المجموع:</span>
                                        <span class="detail-value">{{ number_format($order->total_amount, 2) }} ر.ي</span>
                                    </div>

                                    <div class="detail-item">
                                        <span class="detail-label">المجموع الفرعي:</span>
                                        <span class="detail-value">{{ number_format($order->subtotal, 2) }} ر.ي</span>
                                    </div>

                                    <div class="detail-item">
                                        <span class="detail-label">ضريبة القيمة المضافة:</span>
                                        <span class="detail-value">{{ number_format($order->tax_amount, 2) }} ر.ي</span>
                                    </div>

                                    <div class="detail-item">
                                        <span class="detail-label">تكلفة الشحن:</span>
                                        <span class="detail-value">{{ number_format($order->shipping_amount, 2) }} ر.ي</span>
                                    </div>

                                    <div class="detail-item">
                                        <span class="detail-label">مبلغ الخصم:</span>
                                        <span class="detail-value">{{ number_format($order->discount_amount, 2) }} ر.ي</span>
                                    </div>

                                    <div class="detail-item">
                                        <span class="detail-label">الحالة:</span>
                                        <span class="detail-value">
                                            <span class="badge 
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

                                    <div class="detail-item">
                                        <span class="detail-label">تاريخ الإنشاء:</span>
                                        <span class="detail-value">{{ $order->created_at->format('Y/m/d H:i') }}</span>
                                    </div>

                                    <div class="detail-item">
                                        <span class="detail-label">آخر تحديث:</span>
                                        <span class="detail-value">{{ $order->updated_at->format('Y/m/d H:i') }}</span>
                                    </div>

                                    <div class="detail-item">
                                        <span class="detail-label">حالة الدفع:</span>
                                        <span class="detail-value">
                                            <span class="badge 
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

                                    @if ($order->notes)
                                        <div class="detail-item">
                                            <span class="detail-label">ملاحظات الطلب:</span>
                                            <span class="detail-value">{{ $order->notes }}</span>
                                        </div>
                                    @endif
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
                                            <span class="detail-value">{{ $order->shipping->carrier ?? 'غير محدد' }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">رقم التتبع:</span>
                                            <span class="detail-value">
                                                @if ($order->shipping->tracking_number)
                                                    {{ $order->shipping->tracking_number }}
                                                    @if($order->shipping->tracking_url)
                                                        <a href="{{ $order->shipping->tracking_url }}" target="_blank" 
                                                           class="ms-2 text-primary" title="تتبع الشحنة">
                                                            <i class="la la-external-link"></i>
                                                        </a>
                                                    @endif
                                                @else
                                                    غير متوفر
                                                @endif
                                            </span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">حالة الشحن:</span>
                                            <span class="detail-value">
                                                <span class="badge 
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
                                            <span class="detail-value">{{ number_format($order->shipping->shipping_cost ?? 0, 2) }} ر.ي</span>
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

                        {{-- صندوق تفاصيل الدفع --}}
                        <div class="order-card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <i class="la la-credit-card me-2"></i>تفاصيل الدفع
                                </h4>
                            </div>
                            <div class="card-body">
                                @php
                                    $payment = $order->payments->first();
                                @endphp

                                @if ($payment)
                                    <div class="payment-details">
                                        <div class="detail-item">
                                            <span class="detail-label">طريقة الدفع:</span>
                                            <span class="detail-value">
                                                {{ $payment->storePaymentMethod?->paymentOption?->method_name ?? ($payment->method ?? 'غير محدد') }}
                                            </span>
                                        </div>

                                        <div class="detail-item">
                                            <span class="detail-label">المبلغ المدفوع:</span>
                                            <span class="detail-value">{{ number_format($payment->amount ?? 0, 2) }} ر.ي</span>
                                        </div>

                                        <div class="detail-item">
                                            <span class="detail-label">معرف المعاملة:</span>
                                            <span class="detail-value">{{ $payment->transaction_id ?? 'غير متوفر' }}</span>
                                        </div>

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

                                        <div class="detail-item">
                                            <span class="detail-label">تاريخ الدفع:</span>
                                            <span class="detail-value">
                                                {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('Y/m/d H:i') : 'غير محدد' }}
                                            </span>
                                        </div>

                                        <div class="detail-item">
                                            <span class="detail-label">حالة الدفع:</span>
                                            <span class="detail-value">
                                                <span class="badge 
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

                        {{-- صندوق المنتجات --}}
                        <div class="order-card full-width">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <i class="la la-shopping-bag me-2"></i>المنتجات في الطلب
                                </h4>
                            </div>
                            <div class="card-body">
                                @if ($order->items->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>المنتج</th>
                                                    <th>المتجر</th>
                                                    <th>SKU</th>
                                                    <th>الكمية</th>
                                                    <th>السعر</th>
                                                    <th>الإجمالي</th>
                                                    <th>الإجراءات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($order->items as $index => $item)
                                                    @if ($item->product)
                                                        @php
                                                            $hasReview = isset($reviews[$item->product_id]);
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    @if ($item->product->images->count() > 0)
                                                                        <img src="{{ asset($item->product->images->first()->image_path) }}"
                                                                            class="img-thumbnail me-2"
                                                                            style="width: 50px; height: 50px; object-fit: cover;"
                                                                            alt="{{ $item->product->title }}">
                                                                    @else
                                                                        <div class="img-thumbnail me-2 d-flex align-items-center justify-content-center"
                                                                            style="width: 50px; height: 50px; background: #f8f9fa;">
                                                                            <i class="la la-image text-muted"></i>
                                                                        </div>
                                                                    @endif
                                                                    <div>
                                                                        <div class="fw-medium">{{ $item->product->title }}</div>
                                                                        @if ($item->variant)
                                                                            <small class="text-muted">النوع: {{ $item->variant->name }}</small>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-light text-dark">
                                                                    {{ $item->product->store->store_name ?? 'غير محدد' }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <code>{{ $item->product->sku }}</code>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-primary">{{ $item->quantity }}</span>
                                                            </td>
                                                            <td class="fw-bold">{{ number_format($item->unit_price, 2) }} ر.ي</td>
                                                            <td class="fw-bold text-success">{{ number_format($item->total_price, 2) }} ي</td>
                                                            <td>
                                                                @if ($order->status == 'delivered')
                                                                    @if ($hasReview)
                                                                        <span class="badge bg-success">
                                                                            <i class="la la-check"></i> تم التقييم
                                                                        </span>
                                                                    @else
                                                                        <a href=""
                                                                           class="btn btn-sm btn-primary" title="إضافة تقييم">
                                                                            <i class="la la-star"></i> تقييم
                                                                        </a>
                                                                    @endif
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                            <tfoot class="table-active">
                                                <tr>
                                                    <th colspan="5" class="text-start">المجموع الإجمالي</th>
                                                    <th colspan="3" class="text-end fw-bold fs-6">
                                                        {{ number_format($order->total_amount, 2) }} ر.ي
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info text-center py-4">
                                        <i class="la la-shopping-bag la-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">لا توجد منتجات في هذا الطلب</h5>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- صندوق عناوين الشحن والفواتير --}}
                        <div class="order-card full-width">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <i class="la la-map-marker me-2"></i>عناوين الشحن والفواتير
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @php
                                        $billingAddress = $order->addresses->where('address_type', 'billing')->first();
                                        $shippingAddress = $order->addresses->where('address_type', 'shipping')->first();
                                    @endphp
                                    
                                    <div class="col-md-6">
                                        <div class="address-section">
                                            <h5 class="mb-3 text-primary">
                                                <i class="la la-file-invoice"></i> عنوان الفاتورة
                                            </h5>
                                            @if($billingAddress)
                                                <div class="address-details">
                                                    <div class="detail-item">
                                                        <strong>الاسم:</strong> 
                                                        {{ $billingAddress->first_name }} {{ $billingAddress->last_name }}
                                                    </div>
                                                    <div class="detail-item">
                                                        <strong>الهاتف:</strong> 
                                                        <a href="tel:{{ $billingAddress->phone }}" class="text-decoration-none">
                                                            {{ $billingAddress->phone }}
                                                        </a>
                                                    </div>
                                                    <div class="detail-item">
                                                        <strong>العنوان:</strong> 
                                                        <div class="mt-1">
                                                            {{ $billingAddress->street }}, 
                                                            {{ $billingAddress->city }}, 
                                                            {{ $billingAddress->country }}
                                                        </div>
                                                    </div>
                                                    <div class="detail-item">
                                                        <strong>الرمز البريدي:</strong> {{ $billingAddress->zip_code }}
                                                    </div>
                                                </div>
                                            @else
                                                <div class="alert alert-warning text-center py-2">
                                                    <i class="la la-exclamation-circle"></i> لا يوجد عنوان فاتورة
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="address-section">
                                            <h5 class="mb-3 text-primary">
                                                <i class="la la-truck"></i> عنوان الشحن
                                            </h5>
                                            @if($shippingAddress)
                                                <div class="address-details">
                                                    <div class="detail-item">
                                                        <strong>الاسم:</strong> 
                                                        {{ $shippingAddress->first_name }} {{ $shippingAddress->last_name }}
                                                    </div>
                                                    <div class="detail-item">
                                                        <strong>الهاتف:</strong> 
                                                        <a href="tel:{{ $shippingAddress->phone }}" class="text-decoration-none">
                                                            {{ $shippingAddress->phone }}
                                                        </a>
                                                    </div>
                                                    <div class="detail-item">
                                                        <strong>العنوان:</strong> 
                                                        <div class="mt-1">
                                                            {{ $shippingAddress->street }}, 
                                                            {{ $shippingAddress->city }}, 
                                                            {{ $shippingAddress->country }}
                                                        </div>
                                                    </div>
                                                    <div class="detail-item">
                                                        <strong>الرمز البريدي:</strong> {{ $shippingAddress->zip_code }}
                                                    </div>
                                                </div>
                                            @else
                                                <div class="alert alert-warning text-center py-2">
                                                    <i class="la la-exclamation-circle"></i> لا يوجد عنوان شحن
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- المودالات - الإصلاح النهائي --}}
    
    {{-- مودال إلغاء الطلب --}}
    <div id="cancelOrderModal" class="modal-container">
        <div class="modal-overlay" onclick="closeModal('cancelOrderModal')"></div>
        <div class="modal-content-wrapper">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="la la-times-circle me-2"></i>إلغاء الطلب #{{ $order->order_number }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" onclick="closeModal('cancelOrderModal')" aria-label="Close"></button>
                </div>
                <form action="{{ route('customer.orders.cancel', $order->order_id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="la la-exclamation-triangle"></i>
                            <strong>تنبيه:</strong> لا يمكن التراجع عن إلغاء الطلب بعد التأكيد
                        </div>
                        
                        <div class="mb-3">
                            <label for="cancellation_reason" class="form-label">سبب الإلغاء <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" 
                                      rows="4" placeholder="يرجى كتابة سبب إلغاء الطلب..." required></textarea>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="confirm_cancellation" required>
                            <label class="form-check-label" for="confirm_cancellation">
                                أنا أقر بأنني أريد إلغاء هذا الطلب وأتفهم أن هذه العملية لا يمكن التراجع عنها
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeModal('cancelOrderModal')">تراجع</button>
                        <button type="submit" class="btn btn-danger" id="submitCancelBtn" disabled>تأكيد الإلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- مودال تعديل العنوان --}}
    <div id="editAddressModal" class="modal-container">
        <div class="modal-overlay" onclick="closeModal('editAddressModal')"></div>
        <div class="modal-content-wrapper">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="la la-edit me-2"></i>تعديل عنوان الشحن
                    </h5>
                    <button type="button" class="btn-close" onclick="closeModal('editAddressModal')" aria-label="Close"></button>
                </div>
                <form action="{{ route('customer.orders.update-address', $order->order_id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        @php
                            $shippingAddress = $order->addresses->where('address_type', 'shipping')->first();
                        @endphp
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="street" class="form-label">الشارع <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="street" name="street" 
                                       value="{{ $shippingAddress->street ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="city" class="form-label">المدينة <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="city" name="city" 
                                       value="{{ $shippingAddress->city ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="country" class="form-label">الدولة <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="country" name="country" 
                                       value="{{ $shippingAddress->country ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="zip_code" class="form-label">الرمز البريدي <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="zip_code" name="zip_code" 
                                       value="{{ $shippingAddress->zip_code ?? '' }}" required>
                            </div>
                            <div class="col-md-12">
                                <label for="phone" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="phone" name="phone" 
                                       value="{{ $shippingAddress->phone ?? '' }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeModal('editAddressModal')">إلغاء</button>
                        <button type="submit" class="btn btn-warning">حفظ التعديلات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- مودال إضافة ملاحظة --}}
    <div id="addNoteModal" class="modal-container">
        <div class="modal-overlay" onclick="closeModal('addNoteModal')"></div>
        <div class="modal-content-wrapper">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="la la-sticky-note me-2"></i>إضافة ملاحظة للطلب
                    </h5>
                    <button type="button" class="btn-close btn-close-white" onclick="closeModal('addNoteModal')" aria-label="Close"></button>
                </div>
                <form action="{{ route('customer.orders.add-note', $order->order_id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="note" class="form-label">الملاحظة <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="note" name="note" rows="4" 
                                      placeholder="اكتب ملاحظتك للبائع أو المسؤول..." required></textarea>
                            <div class="form-text">سيتم إرسال هذه الملاحظة للبائع ومسؤول النظام</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeModal('addNoteModal')">إلغاء</button>
                        <button type="submit" class="btn btn-info text-white">إضافة الملاحظة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('script_sdebar')
<script>
// حل بسيط وفعال للمودالات بدون Bootstrap
document.addEventListener('DOMContentLoaded', function() {
    // التحكم في زر تأكيد الإلغاء
    const confirmCheckbox = document.getElementById('confirm_cancellation');
    const submitCancelBtn = document.getElementById('submitCancelBtn');
    
    if (confirmCheckbox && submitCancelBtn) {
        confirmCheckbox.addEventListener('change', function() {
            submitCancelBtn.disabled = !this.checked;
        });
    }
    
    // إخفاء رسائل التنبيه بعد 5 ثواني
    setTimeout(function() {
        const flashMessages = document.querySelectorAll('#flash-message');
        flashMessages.forEach(flash => {
            if (flash) {
                flash.style.transition = "opacity 0.5s ease";
                flash.style.opacity = '0';
                setTimeout(() => flash.remove(), 500);
            }
        });
    }, 5000);
});

// وظائف المودال البسيطة
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.classList.add('modal-open');
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.classList.remove('modal-open');
    }
}

// إغلاق المودال بالضغط على ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const activeModals = document.querySelectorAll('.modal-container.active');
        activeModals.forEach(modal => {
            modal.classList.remove('active');
        });
        document.body.classList.remove('modal-open');
    }
});

// إغلاق المودال بالضغط خارج المحتوى
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        const modal = e.target.closest('.modal-container');
        if (modal) {
            closeModal(modal.id);
        }
    }
});

// وظيفة طباعة الطلب
function printOrder() {
    window.print();
}

// حل احتياطي: إعادة تحميل الصفحة إذا كانت هناك مشاكل
function reloadIfNeeded() {
    const modals = document.querySelectorAll('.modal-container');
    let hasIssue = false;
    
    modals.forEach(modal => {
        if (modal.style.display === 'block' && !modal.classList.contains('active')) {
            hasIssue = true;
        }
    });
    
    if (hasIssue) {
        setTimeout(() => {
            location.reload();
        }, 2000);
    }
}

// تشغيل التحقق كل 5 ثواني
setInterval(reloadIfNeeded, 5000);
</script>
@endsection
@section('css_sdebar')
    <style>
        /* الإصلاح النهائي للمودالات */
        .custom-modal {
            z-index: 99999 !important;
        }
        
        .custom-modal.show {
            display: block !important;
            background-color: rgba(0, 0, 0, 0.5) !important;
        }
        
        .custom-modal .modal-dialog {
            z-index: 100000 !important;
        }
        
        .custom-modal .modal-content {
            position: relative;
            z-index: 100001 !important;
        }
        
        /* إصلاح backdrop */
        .modal-backdrop {
            z-index: 99998 !important;
            background-color: rgba(0, 0, 0, 0.5) !important;
        }
        
        .modal-backdrop.show {
            opacity: 1 !important;
        }
        
        /* منع التمرير عند فتح المودال */
        body.modal-open {
            overflow: hidden !important;
            padding-right: 0 !important;
        }
        
        /* إصلاحات إضافية */
        .modal {
            backdrop-filter: blur(5px);
        }
        
        .modal-content {
            border: none;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease-out;
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .modal-header {
            border-bottom: 2px solid #f0f0f0;
            border-radius: 15px 15px 0 0;
            padding: 1.5rem;
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .modal-footer {
            border-top: 2px solid #f0f0f0;
            border-radius: 0 0 15px 15px;
            padding: 1rem 1.5rem;
        }
        
        /* تحسين مظهر الأزرار */
        .action-buttons {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #dee2e6;
            margin-bottom: 20px;
        }
        
        .action-buttons .btn {
            padding: 12px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            border-radius: 8px;
        }
        
        .action-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        /* باقي الـ CSS الأصلي */
        .order-grid-layout {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .order-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }
        
        .order-card.full-width {
            grid-column: 1 / -1;
        }
        
        .order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.12);
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 18px 24px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .card-title {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: white;
            display: flex;
            align-items: center;
        }
        
        .card-title i {
            margin-right: 8px;
        }
        
        .card-body {
            padding: 24px;
        }
        
        .order-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px dashed #e9ecef;
        }
        
        .detail-item:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: #495057;
            font-size: 14px;
        }
        
        .detail-value {
            color: #212529;
            font-weight: 500;
            text-align: left;
        }
        
        @media (max-width: 1200px) {
            .order-grid-layout {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .order-details-grid {
                grid-template-columns: 1fr;
            }
            
            .card-body {
                padding: 16px;
            }
            
            .card-header {
                padding: 14px 16px;
            }
            
            .form-title-wrap {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .form-title-wrap .title {
                font-size: 20px;
            }
            
            .action-buttons .col-md-4 {
                margin-bottom: 10px;
            }
            
            .modal-dialog {
                margin: 10px;
            }
        }
        /* أنماط المودال المخصصة - بسيطة وفعالة */
        .modal-container {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            animation: fadeIn 0.3s ease;
        }
        
        .modal-container.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }
        
        .modal-content-wrapper {
            position: relative;
            z-index: 10000;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideIn 0.3s ease;
        }
        
        .modal-content {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .modal-header {
            border-bottom: 2px solid #f0f0f0;
            border-radius: 15px 15px 0 0;
            padding: 1.5rem;
        }
        
        .modal-body {
            padding: 1.5rem;
            max-height: 60vh;
            overflow-y: auto;
        }
        
        .modal-footer {
            border-top: 2px solid #f0f0f0;
            border-radius: 0 0 15px 15px;
            padding: 1rem 1.5rem;
        }
        
        /* أنيميشن */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideIn {
            from { 
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }
            to { 
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        /* منع التمرير عند فتح المودال */
        body.modal-open {
            overflow: hidden !important;
        }
        
        /* تحسين مظهر الأزرار */
        .action-buttons {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #dee2e6;
            margin-bottom: 20px;
        }
        
        .action-buttons .btn {
            padding: 12px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            border-radius: 8px;
        }
        
        .action-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        /* تصميم متجاوب */
        @media (max-width: 768px) {
            .modal-content-wrapper {
                width: 95%;
                margin: 10px;
            }
            
            .modal-header,
            .modal-body,
            .modal-footer {
                padding: 1rem;
            }
            
            .action-buttons .col-md-4 {
                margin-bottom: 10px;
            }
        }
    </style>
@endsection