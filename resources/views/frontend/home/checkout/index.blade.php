@extends('frontend.home.layouts.master')

@section('title', 'إتمام الشراء - جميع المتاجر')
@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('static/css/checkout.css') }}" />
@endsection

@section('content')
<section class="checkout-page">
    <div class="container">
        <h1 class="page-title">إتمام الشراء - جميع المتاجر</h1>
        
        <!-- عرض الرسائل -->
        @if(session('success'))
            <div class="alert alert-success fade-in">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger fade-in">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif
        
        <div class="checkout-container">
            <!-- تفاصيل الطلب -->
            <div class="order-summary">
                <h2 class="section-title">تفاصيل الطلب</h2>
                
                @foreach($cartItems as $storeId => $storeItems)
                    @php
                        $store = $storeItems->first()->store;
                        $storeTotal = $storeItems->sum(function($item) {
                            return $item->price * $item->quantity;
                        });
                    @endphp
                    
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
                            @foreach($storeItems as $item)
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
                        
                        <div class="store-total">
                            إجمالي المتجر: <strong>{{ number_format($storeTotal, 2) }} ر.ي</strong>
                        </div>
                    </div>
                @endforeach
                
                <div class="order-totals">
                    <div class="total-row">
                        <span>المجموع الفرعي:</span>
                        <span id="subtotalAmount">{{ number_format($grandTotal, 2) }} ر.ي</span>
                    </div>
                    
                    <!-- قسم الكوبون -->
                    <div class="coupon-section">
                        <div class="coupon-input-group">
                            <input type="text" id="couponCode" placeholder="أدخل كود الخصم" class="coupon-input">
                            <button type="button" id="applyCoupon" class="btn btn-outline">
                                تطبيق
                            </button>
                        </div>
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
                        <span id="grandTotalAmount">{{ number_format($grandTotal, 2) }} ر.ي</span>
                    </div>
                </div>
            </div>

            <!-- نموذج إتمام الشراء -->
            <div class="checkout-form">
                <h2 class="section-title">معلومات التوصيل والدفع</h2>
                
                <form action="{{ route('front.checkout.process') }}" method="POST" id="checkoutForm">
                    @csrf
                    <input type="hidden" name="discount_amount" id="hiddenDiscountAmount" value="0">
                    
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
                            <!-- الدفع الإلكتروني -->
                            <div class="payment-type-section">
                                <h4>الدفع الإلكتروني</h4>
                                <div class="payment-options-grid">
                                    @foreach($paymentOptions as $option)
                                    <div class="payment-option">
                                        <input type="radio" name="payment_type" value="online" 
                                               id="payment_online_{{ $option->option_id }}" 
                                               data-payment-type="online">
                                        <label for="payment_online_{{ $option->option_id }}" class="payment-card">
                                            @if($option->logo)
                                                <img src="{{ asset('storage/' . $option->logo) }}" alt="{{ $option->method_name }}" class="payment-logo">
                                            @else
                                                <i class="fas fa-credit-card"></i>
                                            @endif
                                            <div class="payment-info">
                                                <strong>{{ $option->method_name }}</strong>
                                                <span>الدفع الإلكتروني الآمن</span>
                                            </div>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>

                                <!-- اختيار طريقة الدفع التفصيلية -->
                                <div class="detailed-payment-methods" id="detailedPaymentMethods" style="display: none;">
                                    <h5>اختر طريقة الدفع:</h5>
                                    <div class="store-payment-methods-list">
                                        @php
                                            $allMethods = [];
                                            foreach($storePaymentMethods as $methods) {
                                                $allMethods = array_merge($allMethods, $methods->toArray());
                                            }
                                        @endphp
                                        @foreach($allMethods as $method)
                                        <div class="store-payment-option">
                                            <input type="radio" name="store_payment_method_id" 
                                                   value="{{ $method['spm_id'] }}" 
                                                   id="method_{{ $method['spm_id'] }}">
                                            <label for="method_{{ $method['spm_id'] }}" class="store-payment-card">
                                                <div class="method-details">
                                                    <strong>{{ $method['payment_option']['method_name'] }}</strong>
                                                    @if($method['account_name'])
                                                    <span>اسم الحساب: {{ $method['account_name'] }}</span>
                                                    @endif
                                                    @if($method['account_number'])
                                                    <span>رقم الحساب: {{ $method['account_number'] }}</span>
                                                    @endif
                                                    @if($method['iban'])
                                                    <span>IBAN: {{ $method['iban'] }}</span>
                                                    @endif
                                                    @if($method['description'])
                                                    <span class="method-description">{{ $method['description'] }}</span>
                                                    @endif
                                                </div>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- الدفع عند الاستلام -->
                            <div class="payment-type-section">
                                <h4>الدفع عند الاستلام</h4>
                                <div class="payment-option">
                                    <input type="radio" name="payment_type" value="cash" 
                                           id="payment_cash" checked>
                                    <label for="payment_cash" class="payment-card">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <div class="payment-info">
                                            <strong>الدفع عند الاستلام</strong>
                                            <span>ادفع نقداً عند توصيل الطلب</span>
                                        </div>
                                    </label>
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

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.getElementById('checkoutForm');
    const couponCodeInput = document.getElementById('couponCode');
    const applyCouponBtn = document.getElementById('applyCoupon');
    const couponMessage = document.getElementById('couponMessage');
    const discountRow = document.getElementById('discountRow');
    const discountAmount = document.getElementById('discountAmount');
    const grandTotalAmount = document.getElementById('grandTotalAmount');
    const subtotalAmount = document.getElementById('subtotalAmount');
    const hiddenDiscountAmount = document.getElementById('hiddenDiscountAmount');
    const detailedPaymentMethods = document.getElementById('detailedPaymentMethods');
    const submitOrderBtn = document.getElementById('submitOrderBtn');

    let currentDiscount = 0;
    const originalTotal = {{ $grandTotal }};

    // تطبيق الكوبون
    applyCouponBtn.addEventListener('click', function() {
        const couponCode = couponCodeInput.value.trim();
        
        if (!couponCode) {
            showCouponMessage('يرجى إدخال كود الخصم', 'error');
            return;
        }

        fetch('{{ route("front.checkout.validateCoupon") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                coupon_code: couponCode,
                subtotal: originalTotal
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.valid) {
                currentDiscount = data.discount_amount;
                updateTotals();
                showCouponMessage(data.message, 'success');
                hiddenDiscountAmount.value = currentDiscount;
            } else {
                currentDiscount = 0;
                updateTotals();
                showCouponMessage(data.message, 'error');
                hiddenDiscountAmount.value = 0;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showCouponMessage('حدث خطأ في التحقق من الكوبون', 'error');
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
    }

    // التحكم في عرض طرق الدفع التفصيلية
    document.querySelectorAll('input[name="payment_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'online') {
                detailedPaymentMethods.style.display = 'block';
            } else {
                detailedPaymentMethods.style.display = 'none';
                // إلغاء اختيار أي طريقة دفع تفصيلية عند التبديل للدفع النقدي
                document.querySelectorAll('input[name="store_payment_method_id"]').forEach(method => {
                    method.checked = false;
                });
            }
        });
    });

    // التحقق من النموذج قبل الإرسال
    checkoutForm.addEventListener('submit', function(e) {
        const addressSelected = document.querySelector('input[name="address_id"]:checked');
        const paymentTypeSelected = document.querySelector('input[name="payment_type"]:checked');
        
        if (!addressSelected) {
            e.preventDefault();
            alert('يرجى اختيار عنوان التوصيل');
            return false;
        }
        
        if (!paymentTypeSelected) {
            e.preventDefault();
            alert('يرجى اختيار طريقة الدفع');
            return false;
        }

        // إذا كان الدفع إلكتروني، التحقق من اختيار طريقة دفع تفصيلية
        if (paymentTypeSelected.value === 'online') {
            const paymentMethodSelected = document.querySelector('input[name="store_payment_method_id"]:checked');
            if (!paymentMethodSelected) {
                e.preventDefault();
                alert('يرجى اختيار طريقة الدفع الإلكتروني');
                return false;
            }
        }
        
        // إظهار رسالة تحميل
        submitOrderBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري معالجة الطلب...';
        submitOrderBtn.disabled = true;
        
        return true;
    });

    // إضافة CSS للتحسينات
    const style = document.createElement('style');
    style.textContent = `
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin: 10px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    `;
    document.head.appendChild(style);
});
</script>
@endsection