@extends('frontend.customers.dashboard.index')

@section('title', 'تفاصيل الدفع')
@section('page_title', 'فاتورة الدفع')

@section('contects')
<div class="container mt-4">
    <div class="invoice-container">
        <!-- رأس الفاتورة -->
        <div class="invoice-header">
            <div class="row">
                <div class="col-md-6">
                    <div class="logo-section">
                        @if($store && $store->logo)
                            <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->store_name }}" class="store-logo mb-3" style="max-height: 80px;">
                        @endif
                        <h2 class="company-name">
                            @if($store)
                                {{ $store->store_name }}
                            @else
                                المتجر
                            @endif
                        </h2>
                        <p class="company-address">
                            @if($store && $store->addresses->where('is_primary', true)->first())
                                @php $primaryAddress = $store->addresses->where('is_primary', true)->first(); @endphp
                                العنوان: {{ $primaryAddress->street }}, {{ $primaryAddress->city }}, {{ $primaryAddress->country }}<br>
                            @endif
                            @if($store && $store->phones->where('is_primary', true)->first())
                                @php $primaryPhone = $store->phones->where('is_primary', true)->first(); @endphp
                                الهاتف: {{ $primaryPhone->phone }}<br>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="invoice-info">
                        <h1 class="invoice-title">فاتورة دفع</h1>
                        <p class="invoice-number">رقم الفاتورة: <strong>{{ $payment->payment_id }}</strong></p>
                        <p class="invoice-date">تاريخ الفاتورة: {{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y') }}</p>
                        <p class="invoice-time">وقت الفاتورة: {{ \Carbon\Carbon::parse($payment->created_at)->format('h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <hr class="invoice-divider">

        <!-- معلومات الدفع -->
        <div class="row">
            <div class="col-md-6">
                <div class="info-card">
                    <h5><i class="fas fa-money-bill-wave me-2"></i>معلومات الدفع</h5>
                    <p><strong>رقم الدفع:</strong> {{ $payment->payment_id }}</p>
                    <p><strong>رقم الطلب:</strong> {{ $payment->order->order_number ?? 'غير محدد' }}</p>
                    <p><strong>طريقة الدفع:</strong> 
                        @if($payment->storePaymentMethod)
                            {{ $payment->storePaymentMethod->paymentOption->method_name ?? 'غير محدد' }}
                        @else
                            {{ $payment->method ?? 'غير محدد' }}
                        @endif
                    </p>
                    <p><strong>معرف المعاملة:</strong> {{ $payment->transaction_id ?? 'غير متوفر' }}</p>
                    <p><strong>نوع الدفع:</strong> 
                        <span class="badge bg-{{ $payment->type == 'online' ? 'primary' : 'success' }}">
                            {{ $payment->type == 'online' ? 'إلكتروني' : 'نقدي' }}
                        </span>
                    </p>
                    <p><strong>حالة الدفع:</strong> 
                        <span class="status-badge status-{{ $payment->status }}">
                            @if($payment->status == 'completed')
                                مكتمل
                            @elseif($payment->status == 'pending')
                                معلق
                            @elseif($payment->status == 'failed')
                                فاشل
                            @elseif($payment->status == 'refunded')
                                تم الاسترداد
                            @else
                                {{ $payment->status }}
                            @endif
                        </span>
                    </p>
                    <p><strong>تاريخ الدفع:</strong> {{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-card">
                    <h5><i class="fas fa-user me-2"></i>معلوماتي</h5>
                    @if(Auth::user()->customer)
                        <p><strong>الاسم:</strong> {{ Auth::user()->name }}</p>
                        <p><strong>البريد الإلكتروني:</strong> {{ Auth::user()->email }}</p>
                        <p><strong>الهاتف:</strong> {{ Auth::user()->phone ?? 'غير متوفر' }}</p>
                        
                        @if($payment->order && $payment->order->orderAddresses->where('address_type', 'shipping')->first())
                            @php $shippingAddress = $payment->order->orderAddresses->where('address_type', 'shipping')->first(); @endphp
                            <p><strong>عنوان الشحن:</strong><br>
                                {{ $shippingAddress->street }}, {{ $shippingAddress->city }}, {{ $shippingAddress->country }}
                            </p>
                        @endif
                    @else
                        <p class="text-muted">معلومات العميل غير متوفرة</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- تفاصيل الطلب -->
        @if($payment->order && $payment->order->orderItems)
        <div class="order-details mt-4">
            <h5><i class="fas fa-shopping-bag me-2"></i>تفاصيل الطلب</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-head">
                        <tr>
                            <th>#</th>
                            <th>المنتج</th>
                            <th>المتجر</th>
                            <th>الكمية</th>
                            <th>سعر الوحدة</th>
                            <th>الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payment->order->orderItems as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($item->product && $item->product->images->where('is_primary', true)->first())
                                        <img src="{{ asset($item->product->images->where('is_primary', true)->first()->image_path) }}" 
                                             alt="{{ $item->product->title }}" 
                                             class="product-thumb me-3" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @endif
                                    <div>
                                        <strong>{{ $item->product->title ?? 'منتج محذوف' }}</strong>
                                        @if($item->variant_id)
                                        <br><small class="text-muted">النوع: {{ $item->variant->name ?? 'غير محدد' }}</small>
                                        @endif
                                        <br><small class="text-muted">SKU: {{ $item->product->sku ?? 'غير محدد' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($item->product && $item->product->store)
                                    {{ $item->product->store->store_name }}
                                @else
                                    غير محدد
                                @endif
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->unit_price, 2) }} {{ $payment->currency }}</td>
                            <td>{{ number_format($item->total_price, 2) }} {{ $payment->currency }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-end"><strong>المجموع:</strong></td>
                            <td><strong>{{ number_format($payment->order->orderItems->sum('total_price'), 2) }} {{ $payment->currency }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endif

        <!-- تفاصيل المبلغ -->
        <div class="amount-details mt-4">
            <h5><i class="fas fa-calculator me-2"></i>تفاصيل المبلغ</h5>
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <table class="table amount-table">
                        <tr>
                            <td>المبلغ الأساسي:</td>
                            <td class="text-left">{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</td>
                        </tr>
                        <tr>
                            <td>الخصم:</td>
                            <td class="text-left">{{ number_format($payment->discount ?? 0, 2) }} {{ $payment->currency }}</td>
                        </tr>
                        <tr>
                            <td>الضريبة:</td>
                            <td class="text-left">{{ number_format($payment->order->tax_amount ?? 0, 2) }} {{ $payment->currency }}</td>
                        </tr>
                        <tr>
                            <td>رسوم الشحن:</td>
                            <td class="text-left">{{ number_format($payment->order->shipping_amount ?? 0, 2) }} {{ $payment->currency }}</td>
                        </tr>
                        <tr class="total-row">
                            <td><strong>المبلغ الإجمالي:</strong></td>
                            <td class="text-left"><strong>{{ number_format($payment->total_amount, 2) }} {{ $payment->currency }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- ملاحظات -->
        @if($payment->note)
        <div class="notes-section mt-4">
            <h5><i class="fas fa-sticky-note me-2"></i>ملاحظات</h5>
            <div class="notes-content">
                <p>{{ $payment->note }}</p>
            </div>
        </div>
        @endif

        <!-- تذييل الفاتورة -->
        <div class="invoice-footer mt-5">
            <div class="row">
                <div class="col-md-4">
                    <div class="footer-section">
                        <h6><i class="fas fa-file-invoice-dollar me-2"></i>شروط الدفع</h6>
                        <p>يجب سداد الفاتورة خلال 30 يوم من تاريخ الاصدار</p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="footer-section">
                        <h6><i class="fas fa-heart me-2"></i>شكراً لتعاملكم معنا</h6>
                        <p>نأمل أن تكون تجربة التسوق لدينا مرضية</p>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="footer-section">
                        <h6><i class="fas fa-headset me-2"></i>تواصل معنا</h6>
                        @if($store)
                            @if($store->phones->where('is_primary', true)->first())
                                <p>الهاتف: {{ $store->phones->where('is_primary', true)->first()->phone }}</p>
                            @endif
                            <p>البريد: {{ $store->user->email ?? 'غير محدد' }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <hr>
            <p class="text-center copyright">
                © {{ date('Y') }} 
                @if($store)
                    {{ $store->store_name }}
                @else
                    المتجر
                @endif
                . جميع الحقوق محفوظة.
            </p>
        </div>

        <!-- أزرار الإجراءات -->
        <div class="action-buttons mt-4">
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print"></i> طباعة الفاتورة
            </button>
            <a href="{{ route('customer.payments.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right"></i> العودة إلى المدفوعات
            </a>
        </div>
    </div>
</div>

<style>
/* نفس الـ CSS السابق */
.invoice-container {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    padding: 30px;
    margin-bottom: 30px;
}

.invoice-header {
    margin-bottom: 20px;
}

.store-logo {
    max-height: 80px;
    border-radius: 8px;
}

.invoice-title {
    color: #2c3e50;
    font-weight: 700;
    margin-bottom: 10px;
}

.invoice-number {
    font-size: 16px;
    margin-bottom: 5px;
}

.invoice-date, .invoice-time {
    color: #7f8c8d;
    margin-bottom: 3px;
}

.invoice-divider {
    border-top: 2px solid #ecf0f1;
    margin: 25px 0;
}

.info-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    height: 100%;
    border-left: 4px solid #2c3e50;
}

.info-card h5 {
    color: #2c3e50;
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 10px;
    margin-bottom: 15px;
}

.amount-table {
    width: 100%;
}

.amount-table td {
    padding: 8px 15px;
    border: none;
}

.total-row {
    border-top: 2px solid #dee2e6 !important;
    font-size: 16px;
    background-color: #f8f9fa;
}

.table-head {
    background-color: #2c3e50;
    color: white;
}

.product-thumb {
    border-radius: 5px;
    border: 1px solid #dee2e6;
}

.status-badge {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
}

.status-completed {
    background-color: #d4edda;
    color: #155724;
}

.status-pending {
    background-color: #fff3cd;
    color: #856404;
}

.status-failed {
    background-color: #f8d7da;
    color: #721c24;
}

.status-refunded {
    background-color: #e2e3e5;
    color: #383d41;
}

.notes-content {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    border-right: 4px solid #2c3e50;
}

.invoice-footer {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
}

.footer-section h6 {
    color: #2c3e50;
    margin-bottom: 10px;
}

.copyright {
    color: #7f8c8d;
    font-size: 14px;
    margin-top: 15px;
}

.action-buttons {
    display: flex;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
}

.badge {
    font-size: 12px;
    padding: 5px 10px;
}

@media print {
    .action-buttons {
        display: none;
    }
    
    .invoice-container {
        box-shadow: none;
        padding: 0;
    }
    
    body {
        background: white !important;
    }
}

@media (max-width: 768px) {
    .invoice-header .text-md-end {
        text-align: right !important;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .action-buttons .btn {
        margin-bottom: 10px;
        width: 100%;
    }
    
    .info-card {
        margin-bottom: 15px;
    }
}
</style>
@endsection