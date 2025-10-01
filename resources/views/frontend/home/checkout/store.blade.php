@extends('frontend.home.layouts.master')

@section('title', 'إتمام الشراء - ' . $store->store_name)
@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('static/css/checkout.css') }}" />
<style>
/* الأنماط الجديدة لطرق الدفع */
.main-payment-options {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.main-payment-card {
    text-align: center;
    padding: 1.5rem;
    flex-direction: column;
    gap: 1rem;
    cursor: pointer;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    transition: all 0.3s ease;
    background: white;
}

.main-payment-card i {
    font-size: 2.5rem;
    color: #3498db;
}

.main-payment-card .payment-info {
    text-align: center;
}

.main-payment-card .payment-info strong {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    display: block;
}

.main-payment-card .payment-info span {
    font-size: 0.9rem;
    color: #7f8c8d;
}

/* طرق الدفع التفصيلية */
.detailed-payment-methods {
    margin-top: 1.5rem;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.detailed-methods-title {
    color: #2c3e50;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #3498db;
    font-weight: 600;
    font-size: 1.2rem;
}

.store-payment-methods-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.store-payment-card {
    padding: 1.5rem;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    transition: all 0.3s ease;
    background: white;
    cursor: pointer;
}

.store-payment-option input[type="radio"]:checked + .store-payment-card {
    border-color: #3498db;
    background: linear-gradient(135deg, #f8fbfd 0%, #e3f2fd 100%);
    box-shadow: 0 5px 15px rgba(52, 152, 219, 0.2);
    transform: translateY(-2px);
}

.store-payment-card:hover {
    border-color: #3498db;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.method-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e9ecef;
}

.method-logo {
    width: 50px;
    height: 50px;
    object-fit: contain;
    border-radius: 8px;
}

.method-icon {
    font-size: 2.5rem;
    color: #3498db;
    width: 50px;
    text-align: center;
}

.method-title {
    flex: 1;
}

.method-title strong {
    display: block;
    color: #2c3e50;
    font-size: 1.2rem;
    margin-bottom: 0.25rem;
}

.method-status {
    background: #27ae60;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
}

.method-details {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.method-detail {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #3498db;
}

.detail-label {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.9rem;
    min-width: 120px;
}

.detail-value {
    color: #2c3e50;
    font-size: 0.9rem;
    text-align: left;
    font-family: 'Courier New', monospace;
    background: white;
    padding: 0.5rem 0.75rem;
    border-radius: 5px;
    border: 1px solid #e9ecef;
    flex: 1;
    margin-right: 1rem;
}

.method-description {
    background: #fff3cd;
    padding: 1rem;
    border-radius: 8px;
    border-right: 4px solid #ffc107;
    margin-top: 0.5rem;
}

.method-description .detail-label {
    display: block;
    margin-bottom: 0.5rem;
    color: #856404;
    font-size: 0.9rem;
}

.method-description .detail-value {
    color: #856404;
    line-height: 1.5;
    font-family: 'Tajawal', sans-serif;
    background: transparent;
    border: none;
    padding: 0;
    margin: 0;
}

.no-payment-methods {
    text-align: center;
    padding: 2rem;
    background: #f8f9fa;
    border-radius: 10px;
    border: 2px dashed #dee2e6;
}

.no-payment-methods i {
    font-size: 3rem;
    color: #6c757d;
    margin-bottom: 1rem;
}

.no-payment-methods p {
    color: #6c757d;
    margin-bottom: 0.5rem;
}

/* تأثيرات للخيارات الرئيسية */
.payment-option input[type="radio"]:checked + .main-payment-card {
    border-color: #3498db;
    background: #f8fbfd;
    box-shadow: 0 5px 15px rgba(52, 152, 219, 0.2);
}

/* التجاوب مع الشاشات الصغيرة */
@media (max-width: 768px) {
    .main-payment-options {
        grid-template-columns: 1fr;
    }
    
    .method-header {
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
    
    .method-detail {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .detail-value {
        margin-right: 0;
        text-align: right;
        width: 100%;
    }
    
    .method-title {
        text-align: center;
    }
}

/* رسائل الخطأ */
.submit-error-message {
    margin-bottom: 1rem;
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

/* إخفاء الـ radio buttons الافتراضية */
.payment-option input[type="radio"],
.store-payment-option input[type="radio"] {
    display: none;
}

/* تحسين مظهر البطاقات عند التحديد */
.payment-option input[type="radio"]:checked + .main-payment-card,
.store-payment-option input[type="radio"]:checked + .store-payment-card {
    border-color: #3498db;
    background: linear-gradient(135deg, #f8fbfd 0%, #e3f2fd 100%);
    box-shadow: 0 5px 15px rgba(52, 152, 219, 0.2);
    transform: translateY(-2px);
}
</style>
@endsection

@section('content')
<section class="checkout-page">
    <div class="container">
        <h1 class="page-title">إتمام الشراء - {{ $store->store_name }}</h1>
        
        <!-- عرض الرسائل -->
        @if(session('success'))
            <div class="alert alert-success fade-in">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error fade-in">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-error fade-in">
                <i class="fas fa-exclamation-circle"></i>
                <ul style="margin: 0; padding-right: 1rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="checkout-container">
            <!-- تفاصيل الطلب -->
            <div class="order-summary">
                <h2 class="section-title">تفاصيل الطلب</h2>
                
                <div class="store-section">
                    <div class="store-header">
                        @if($store->logo)
                            <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->store_name }}" class="store-logo">
                        @else
                            <div class="store-logo-placeholder">
                                <i class="fas fa-store"></i>
                            </div>
                        @endif
                        <h3>{{ $store->store_name }}</h3>
                    </div>
                    
                    <div class="store-items">
                        @foreach($cartItems as $item)
                        <div class="order-item">
                            <div class="item-image">
                                <img src="{{ asset($item->product->images->first()->image_path ?? 'static/images/placeholder.jpg') }}" 
                                     alt="{{ $item->product->title }}">
                            </div>
                            <div class="item-details">
                                <h4>{{ $item->product->title }}</h4>
                                @if($item->variant)
                                <p class="variant">النوع: {{ $item->variant->name }}</p>
                                @endif
                                <div class="item-price-quantity">
                                    <span class="quantity">{{ $item->quantity }} ×</span>
                                    <span class="price">{{ number_format($item->price, 2) }} ر.ي</span>
                                </div>
                            </div>
                            <div class="item-total">
                                {{ number_format($item->price * $item->quantity, 2) }} ر.ي
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="order-totals">
                        <div class="total-row">
                            <span>المجموع الفرعي:</span>
                            <span>{{ number_format($storeTotal, 2) }} ر.ي</span>
                        </div>
                        
                        <!-- قسم الكوبون -->
                        <div class="coupon-section">
                            <form id="couponForm" method="POST" action="{{ route('front.checkout.validateCoupon') }}">
                                @csrf
                                <input type="hidden" name="subtotal" value="{{ $storeTotal }}">
                                <div class="coupon-input-group">
                                    <input type="text" name="coupon_code" placeholder="أدخل كود الخصم" class="coupon-input" 
                                           value="{{ old('coupon_code') }}">
                                    <button type="button" id="applyCoupon" class="btn btn-outline">
                                        تطبيق
                                    </button>
                                </div>
                            </form>
                            <div id="couponMessage" class="coupon-message"></div>
                        </div>

                        <div class="total-row discount-row" id="discountRow" style="display: none;">
                            <span>الخصم:</span>
                            <span id="discountAmount">0.00 ر.ي</span>
                        </div>
                        
                        <div class="total-row">
                            <span>تكلفة الشحن:</span>
                            <span>0.00 ر.ي</span>
                        </div>
                        
                        <div class="total-row">
                            <span>الضريبة:</span>
                            <span>0.00 ر.ي</span>
                        </div>
                        
                        <div class="total-row grand-total">
                            <span>المجموع الكلي:</span>
                            <span id="grandTotalAmount">{{ number_format($storeTotal, 2) }} ر.ي</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- نموذج إتمام الشراء -->
            <div class="checkout-form">
                <h2 class="section-title">معلومات التوصيل والدفع</h2>
                
                <form action="{{ route('front.checkout.process') }}" method="POST" id="checkoutForm">
                    @csrf
                    <input type="hidden" name="store_id" value="{{ $store->store_id }}">
                    <input type="hidden" name="discount_amount" id="hiddenDiscountAmount" value="0">
                    <input type="hidden" name="coupon_code" id="hiddenCouponCode" value="">
                    
                    <!-- اختيار العنوان -->
                    <div class="form-section">
                        <h3>اختر عنوان التوصيل</h3>
                        
                        @if($addresses->count() > 0)
                            <div class="addresses-list">
                                @foreach($addresses as $address)
                                <div class="address-option">
                                    <input type="radio" name="address_id" value="{{ $address->address_id }}" 
                                           id="address_{{ $address->address_id }}" 
                                           {{ $address->is_default ? 'checked' : '' }} required>
                                    <label for="address_{{ $address->address_id }}" class="address-card">
                                        <div class="address-header">
                                            <strong>{{ $address->title }}</strong>
                                            @if($address->is_default)
                                            <span class="default-badge">افتراضي</span>
                                            @endif
                                        </div>
                                        <div class="address-details">
                                            <p>{{ $address->first_name }} {{ $address->last_name }}</p>
                                            <p>{{ $address->street }}, {{ $address->city }}, {{ $address->country }}</p>
                                            <p>هاتف: {{ $address->phone }}</p>
                                            @if($address->apartment)
                                            <p>الشقة: {{ $address->apartment }}</p>
                                            @endif
                                            @if($address->zip_code)
                                            <p>الرمز البريدي: {{ $address->zip_code }}</p>
                                            @endif
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                لا توجد عناوين مسجلة. 
                                <a href="{{ route('customer.addresses.create') }}" class="alert-link">أضف عنوان جديد</a>
                            </div>
                        @endif
                    </div>

                    <!-- طريقة الدفع -->
                    <div class="form-section">
                        <h3>طريقة الدفع</h3>
                        <div class="payment-methods">
                            <!-- الخياران الرئيسيان -->
                            <div class="main-payment-options">
                                <div class="payment-option">
                                    <input type="radio" name="payment_type" value="cash" 
                                           id="payment_cash" 
                                           {{ old('payment_type', 'cash') == 'cash' ? 'checked' : '' }}>
                                    <label for="payment_cash" class="payment-card main-payment-card">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <div class="payment-info">
                                            <strong>الدفع عند الاستلام</strong>
                                            <span>ادفع نقداً عند توصيل الطلب</span>
                                        </div>
                                    </label>
                                </div>

                                <div class="payment-option">
                                    <input type="radio" name="payment_type" value="online" 
                                           id="payment_online" 
                                           {{ old('payment_type') == 'online' ? 'checked' : '' }}>
                                    <label for="payment_online" class="payment-card main-payment-card">
                                        <i class="fas fa-credit-card"></i>
                                        <div class="payment-info">
                                            <strong>الدفع الإلكتروني</strong>
                                            <span>الدفع الآمن عبر البطاقات الإلكترونية</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- اختيار طريقة الدفع التفصيلية (تظهر فقط عند اختيار الدفع الإلكتروني) -->
                            <div class="detailed-payment-methods" id="detailedPaymentMethods" style="display: {{ old('payment_type') == 'online' ? 'block' : 'none' }};">
                                <h4 class="detailed-methods-title">اختر طريقة الدفع الإلكتروني:</h4>
                                <div class="store-payment-methods-list">
                                    @if($storePaymentMethods->count() > 0)
                                        @foreach($storePaymentMethods as $method)
                                        <div class="store-payment-option">
                                            <input type="radio" name="store_payment_method_id" 
                                                   value="{{ $method->spm_id }}" 
                                                   id="method_{{ $method->spm_id }}"
                                                   {{ old('store_payment_method_id') == $method->spm_id ? 'checked' : '' }}
                                                   {{ old('payment_type') == 'online' ? 'required' : '' }}>
                                            <label for="method_{{ $method->spm_id }}" class="store-payment-card">
                                                <div class="method-header">
                                                    @if($method->paymentOption->logo)
                                                        <img src="{{ asset('storage/' . $method->paymentOption->logo) }}" 
                                                             alt="{{ $method->paymentOption->method_name }}" 
                                                             class="method-logo">
                                                    @else
                                                        <i class="fas fa-credit-card method-icon"></i>
                                                    @endif
                                                    <div class="method-title">
                                                        <strong>{{ $method->paymentOption->method_name }}</strong>
                                                        <span class="method-status">نشط</span>
                                                    </div>
                                                </div>
                                                <div class="method-details">
                                                    @if($method->account_name)
                                                    <div class="method-detail">
                                                        <span class="detail-label">اسم الحساب:</span>
                                                        <span class="detail-value">{{ $method->account_name }}</span>
                                                    </div>
                                                    @endif
                                                    @if($method->account_number)
                                                    <div class="method-detail">
                                                        <span class="detail-label">رقم الحساب:</span>
                                                        <span class="detail-value">{{ $method->account_number }}</span>
                                                    </div>
                                                    @endif
                                                    @if($method->iban)
                                                    <div class="method-detail">
                                                        <span class="detail-label">IBAN:</span>
                                                        <span class="detail-value">{{ $method->iban }}</span>
                                                    </div>
                                                    @endif
                                                    @if($method->description)
                                                    <div class="method-description">
                                                        <span class="detail-label">تعليمات الدفع:</span>
                                                        <span class="detail-value">{{ $method->description }}</span>
                                                    </div>
                                                    @endif
                                                </div>
                                            </label>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="no-payment-methods">
                                            <i class="fas fa-info-circle"></i>
                                            <p>لا توجد طرق دفع إلكتروني متاحة لهذا المتجر حالياً.</p>
                                            <p>يرجى اختيار طريقة الدفع عند الاستلام.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ملاحظات -->
                    <div class="form-section">
                        <h3>ملاحظات إضافية</h3>
                        <textarea name="notes" rows="4" placeholder="أي ملاحظات إضافية حول الطلب..." 
                                  class="form-textarea">{{ old('notes') }}</textarea>
                    </div>

                    <!-- أزرار التنفيذ -->
                    <div class="checkout-actions">
                        <button type="submit" class="btn btn-primary btn-large" id="submitOrderBtn">
                            <i class="fas fa-shopping-bag"></i>
                            تأكيد الطلب والدفع
                        </button>
                        
                        <a href="{{ route('front.cart.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right"></i>
                            العودة إلى السلة
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ صفحة الدفع محملة - جاري إعداد JavaScript');
    
    const checkoutForm = document.getElementById('checkoutForm');
    const couponForm = document.getElementById('couponForm');
    const applyCouponBtn = document.getElementById('applyCoupon');
    const couponMessage = document.getElementById('couponMessage');
    const discountRow = document.getElementById('discountRow');
    const discountAmount = document.getElementById('discountAmount');
    const grandTotalAmount = document.getElementById('grandTotalAmount');
    const hiddenDiscountAmount = document.getElementById('hiddenDiscountAmount');
    const hiddenCouponCode = document.getElementById('hiddenCouponCode');
    const detailedPaymentMethods = document.getElementById('detailedPaymentMethods');
    const submitOrderBtn = document.getElementById('submitOrderBtn');

    let currentDiscount = 0;
    const originalTotal = {{ $storeTotal }};

    // تطبيق الكوبون - محدث
    applyCouponBtn.addEventListener('click', function() {
        console.log('جاري تطبيق الكوبون...');
        const couponCode = document.querySelector('input[name="coupon_code"]').value.trim();
        
        if (!couponCode) {
            showCouponMessage('يرجى إدخال كود الخصم', 'error');
            return;
        }

        // إظهار حالة التحميل
        applyCouponBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحقق...';
        applyCouponBtn.disabled = true;
        
        fetch('{{ route("front.checkout.validateCoupon") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                coupon_code: couponCode,
                subtotal: {{ $storeTotal }}
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            console.log('استجابة الكوبون:', data);
            if (data.valid) {
                currentDiscount = parseFloat(data.discount_amount);
                updateTotals();
                showCouponMessage(data.message, 'success');
                hiddenDiscountAmount.value = currentDiscount;
                hiddenCouponCode.value = couponCode;
            } else {
                currentDiscount = 0;
                updateTotals();
                showCouponMessage(data.message, 'error');
                hiddenDiscountAmount.value = 0;
                hiddenCouponCode.value = '';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showCouponMessage('حدث خطأ في التحقق من الكوبون: ' + error.message, 'error');
        })
        .finally(() => {
            // إعادة تعيين الزر
            applyCouponBtn.innerHTML = 'تطبيق';
            applyCouponBtn.disabled = false;
        });
    });

    // تحديث الإجماليات
    function updateTotals() {
        const newTotal = originalTotal - currentDiscount;
        
        discountAmount.textContent = currentDiscount.toFixed(2) + ' ر.ي';
        grandTotalAmount.textContent = newTotal.toFixed(2) + ' ر.ي';
        
        if (currentDiscount > 0) {
            discountRow.style.display = 'flex';
        } else {
            discountRow.style.display = 'none';
        }
    }

    // عرض رسالة الكوبون
    function showCouponMessage(message, type) {
        couponMessage.textContent = message;
        couponMessage.className = 'coupon-message ' + type;
        couponMessage.style.display = 'block';
        
        // إخفاء الرسالة بعد 5 ثواني
        setTimeout(() => {
            couponMessage.style.display = 'none';
        }, 5000);
    }

    // التحكم في عرض طرق الدفع التفصيلية
    function setupPaymentMethods() {
        console.log('جاري إعداد مستمعي أحداث طرق الدفع...');
        
        const paymentTypeRadios = document.querySelectorAll('input[name="payment_type"]');
        const paymentMethods = document.querySelectorAll('input[name="store_payment_method_id"]');
        
        paymentTypeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                console.log('تم تغيير طريقة الدفع إلى:', this.value);
                
                if (this.value === 'online') {
                    detailedPaymentMethods.style.display = 'block';
                    console.log('تم عرض طرق الدفع الإلكتروني');
                    
                    // جعل اختيار طريقة الدفع إلزامي
                    paymentMethods.forEach(method => {
                        method.required = true;
                    });
                    
                    // إذا لم توجد طرق دفع متاحة، إرجاع اختيار الدفع النقدي
                    if (paymentMethods.length === 0) {
                        document.getElementById('payment_cash').checked = true;
                        detailedPaymentMethods.style.display = 'none';
                        showCouponMessage('لا توجد طرق دفع إلكتروني متاحة. تم اختيار الدفع عند الاستلام تلقائياً.', 'warning');
                    }
                } else {
                    detailedPaymentMethods.style.display = 'none';
                    console.log('تم إخفاء طرق الدفع الإلكتروني');
                    
                    // إلغاء الإلزام وإلغاء التحديد
                    paymentMethods.forEach(method => {
                        method.required = false;
                        method.checked = false;
                    });
                }
            });
        });
        
        // تهيئة الحالة الأولية
        const selectedPaymentType = document.querySelector('input[name="payment_type"]:checked');
        if (selectedPaymentType && selectedPaymentType.value === 'online') {
            detailedPaymentMethods.style.display = 'block';
            paymentMethods.forEach(method => {
                method.required = true;
            });
        } else {
            detailedPaymentMethods.style.display = 'none';
            paymentMethods.forEach(method => {
                method.required = false;
            });
        }
    }

    // التحقق من النموذج قبل الإرسال
    checkoutForm.addEventListener('submit', function(e) {
        console.log('جاري التحقق من النموذج...');
        
        const addressSelected = document.querySelector('input[name="address_id"]:checked');
        const paymentTypeSelected = document.querySelector('input[name="payment_type"]:checked');
        
        if (!addressSelected) {
            e.preventDefault();
            showErrorMessage('يرجى اختيار عنوان التوصيل');
            return false;
        }
        
        if (!paymentTypeSelected) {
            e.preventDefault();
            showErrorMessage('يرجى اختيار طريقة الدفع');
            return false;
        }

        // إذا كان الدفع إلكتروني، التحقق من اختيار طريقة دفع تفصيلية
        if (paymentTypeSelected.value === 'online') {
            const paymentMethodSelected = document.querySelector('input[name="store_payment_method_id"]:checked');
            if (!paymentMethodSelected) {
                e.preventDefault();
                showErrorMessage('يرجى اختيار طريقة الدفع الإلكتروني');
                return false;
            }
        }
        
        // إظهار رسالة تحميل
        submitOrderBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري معالجة الطلب...';
        submitOrderBtn.disabled = true;
        
        console.log('النموذج صالح للإرسال');
        return true;
    });

    // وظيفة لعرض رسائل الخطأ
    function showErrorMessage(message) {
        console.log('عرض رسالة الخطأ:', message);
        
        // إزالة أي رسائل خطأ سابقة
        const existingError = document.querySelector('.submit-error-message');
        if (existingError) {
            existingError.remove();
        }
        
        // إنشاء رسالة الخطأ
        const errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-error fade-in submit-error-message';
        errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
        
        // إدراج الرسالة قبل النموذج
        const checkoutForm = document.querySelector('.checkout-form');
        checkoutForm.insertBefore(errorDiv, checkoutForm.firstChild);
        
        // التمرير إلى الأعلى
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    // إعداد مستمعي الأحداث عند التحميل
    function initializePage() {
        console.log('جاري تهيئة الصفحة...');
        setupPaymentMethods();
        console.log('✅ تم تهيئة الصفحة بنجاح');
    }

    // تهيئة الصفحة عند التحميل
    initializePage();
});
</script>
@endsection