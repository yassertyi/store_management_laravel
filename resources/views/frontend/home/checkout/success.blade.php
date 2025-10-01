@extends('frontend.home.layouts.master')

@section('title', 'تم إنشاء الطلب بنجاح')
@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
<style>
.checkout-success-page {
    padding: 4rem 0;
    background: #f8f9fa;
    min-height: 100vh;
}

.success-container {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    padding: 3rem;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    text-align: center;
}

.success-icon {
    font-size: 4rem;
    color: #27ae60;
    margin-bottom: 1.5rem;
}

.success-title {
    color: #27ae60;
    margin-bottom: 2rem;
    font-weight: 700;
}

.order-details {
    text-align: right;
    margin: 2rem 0;
}

.detail-card {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #e9ecef;
}

.detail-item:last-child {
    border-bottom: none;
}

.label {
    font-weight: 600;
    color: #2c3e50;
}

.value {
    color: #7f8c8d;
}

.status-pending { color: #f39c12; }
.status-processing { color: #3498db; }
.status-shipped { color: #9b59b6; }
.status-delivered { color: #27ae60; }
.status-cancelled { color: #e74c3c; }

.payment-pending { color: #f39c12; }
.payment-paid { color: #27ae60; }
.payment-failed { color: #e74c3c; }
.payment-refunded { color: #95a5a6; }

.payment-details, .order-items-summary {
    margin-top: 2rem;
}

.payment-details h3, .order-items-summary h3 {
    color: #2c3e50;
    margin-bottom: 1rem;
    font-weight: 600;
    text-align: right;
}

.items-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.order-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    text-align: right;
}

.order-item .item-image {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    overflow: hidden;
}

.order-item .item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.order-item .item-details {
    flex: 1;
    text-align: right;
}

.order-item .item-details h4 {
    margin: 0 0 0.5rem 0;
    color: #2c3e50;
    font-weight: 500;
}

.order-item .item-info {
    display: flex;
    gap: 1rem;
    color: #6c757d;
    font-size: 0.9rem;
}

.order-item .item-total {
    font-weight: 600;
    color: #2c3e50;
}

.success-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin: 2rem 0;
    flex-wrap: wrap;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: 'Tajawal', sans-serif;
    text-align: center;
}

.btn-primary {
    background: #3498db;
    color: white;
}

.btn-primary:hover {
    background: #2980b9;
    transform: translateY(-2px);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
}

.btn-outline {
    background: transparent;
    color: #3498db;
    border: 2px solid #3498db;
}

.btn-outline:hover {
    background: #3498db;
    color: white;
}

.success-message {
    color: #6c757d;
    line-height: 1.6;
    margin-top: 2rem;
    text-align: center;
}

.success-message a {
    color: #3498db;
    text-decoration: none;
}

.success-message a:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .success-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
    }
    
    .order-item {
        flex-direction: column;
        text-align: center;
    }
}
</style>
@endsection

@section('content')
<section class="checkout-success-page">
    <div class="container">
        <div class="success-container">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <h1 class="success-title">تم إنشاء طلبك بنجاح!</h1>
            
            <div class="order-details">
                <div class="detail-card">
                    <div class="detail-item">
                        <span class="label">رقم الطلب:</span>
                        <span class="value">#{{ $order->order_number }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">تاريخ الطلب:</span>
                        <span class="value">{{ $order->created_at->format('Y/m/d h:i A') }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">المجموع الكلي:</span>
                        <span class="value">{{ number_format($order->total_amount, 2) }} ر.ي</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">حالة الطلب:</span>
                        <span class="value status-{{ $order->status }}">
                            @if($order->status == 'pending') قيد الانتظار
                            @elseif($order->status == 'processing') قيد المعالجة
                            @elseif($order->status == 'shipped') تم الشحن
                            @elseif($order->status == 'delivered') تم التوصيل
                            @elseif($order->status == 'cancelled') ملغي
                            @endif
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="label">حالة الدفع:</span>
                        <span class="value payment-{{ $order->payment_status }}">
                            @if($order->payment_status == 'pending') قيد الانتظار
                            @elseif($order->payment_status == 'paid') مدفوع
                            @elseif($order->payment_status == 'failed') فشل
                            @elseif($order->payment_status == 'refunded') تم الاسترداد
                            @endif
                        </span>
                    </div>
                </div>

                <!-- تفاصيل الدفع -->
                @if($order->payment)
                <div class="payment-details">
                    <h3>تفاصيل الدفع</h3>
                    <div class="detail-card">
                        <div class="detail-item">
                            <span class="label">طريقة الدفع:</span>
                            <span class="value">{{ $order->payment->method }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">نوع الدفع:</span>
                            <span class="value">{{ $order->payment->type === 'online' ? 'إلكتروني' : 'نقدي' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">المبلغ المدفوع:</span>
                            <span class="value">{{ number_format($order->payment->total_amount, 2) }} ر.ي</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">حالة الدفع:</span>
                            <span class="value payment-{{ $order->payment->status }}">
                                @if($order->payment->status == 'pending') قيد الانتظار
                                @elseif($order->payment->status == 'completed') مكتمل
                                @elseif($order->payment->status == 'failed') فشل
                                @elseif($order->payment->status == 'refunded') تم الاسترداد
                                @endif
                            </span>
                        </div>
                        @if($order->payment->storePaymentMethod)
                        <div class="detail-item">
                            <span class="label">معلومات الدفع:</span>
                            <span class="value">
                                {{ $order->payment->storePaymentMethod->paymentOption->method_name }}
                                @if($order->payment->storePaymentMethod->account_name)
                                - {{ $order->payment->storePaymentMethod->account_name }}
                                @endif
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- عناصر الطلب -->
                <div class="order-items-summary">
                    <h3>المنتجات المطلوبة</h3>
                    <div class="items-list">
                        @foreach($order->orderItems as $item)
                        <div class="order-item">
                            <div class="item-image">
                                <img src="{{ asset($item->product->images->first()->image_path ?? 'static/images/placeholder.jpg') }}" 
                                     alt="{{ $item->product->title }}">
                            </div>
                            <div class="item-details">
                                <h4>{{ $item->product->title }}</h4>
                                <div class="item-info">
                                    <span class="quantity">{{ $item->quantity }} ×</span>
                                    <span class="price">{{ number_format($item->unit_price, 2) }} ر.ي</span>
                                </div>
                            </div>
                            <div class="item-total">
                                {{ number_format($item->total_price, 2) }} ر.ي
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="success-actions">
                <a href="{{ route('customer.orders.show', $order->order_id) }}" class="btn btn-primary">
                    <i class="fas fa-eye"></i>
                    عرض تفاصيل الطلب
                </a>
                
                <a href="{{ route('front.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-shopping-bag"></i>
                    مواصلة التسوق
                </a>
                
                <a href="{{ route('customer.dashboard') }}" class="btn btn-outline">
                    <i class="fas fa-tachometer-alt"></i>
                    لوحة التحكم
                </a>
            </div>
            
            <div class="success-message">
                <p>سيتم إرسال رسالة تأكيد إلى بريدك الإلكتروني مع تفاصيل الطلب.</p>
                <p>لأي استفسار، يمكنك <a href="{{ route('customer.support.tickets.create') }}">الاتصال بالدعم</a>.</p>
            </div>
        </div>
    </div>
</section>
@endsection