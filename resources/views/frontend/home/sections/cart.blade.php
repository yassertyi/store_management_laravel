@extends('frontend.home.layouts.master')

@section('title', 'السلة')
@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('static/css/cart.css') }}" />
@endsection

@section('content')
<section class="cart-page">
    <div class="container">
        <h1 class="page-title">سلة التسوق</h1>
        
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
        
        <!-- إحصائيات السلة -->
        <div class="cart-stats">
            <div class="stat-badge">
                <i class="fas fa-shopping-cart"></i>
                <span>{{ $totalCartCount }} منتج</span>
            </div>
            <div class="stat-badge">
                <i class="fas fa-store"></i>
                <span>{{ count($cartItemsByStore) }} متجر</span>
            </div>
            <div class="stat-badge">
                <i class="fas fa-receipt"></i>
                <span>{{ number_format($grandTotal, 2) }} ر.ي</span>
            </div>
        </div>

        @if(count($cartItemsByStore) > 0)
        <div class="cart-container">
            <!-- عناصر السلة مجمعة حسب المتجر -->
            <div class="cart-items-container">
                @foreach($cartItemsByStore as $storeId => $storeItems)
                    @php
                        $store = $storeItems->first()->store;
                        $storeTotal = $storeItems->sum(function($item) {
                            return $item->price * $item->quantity;
                        });
                    @endphp
                    
                    <div class="store-section fade-in">
                        <!-- رأس المتجر -->
                        <div class="store-header">
                            <div class="store-info">
                                @if($store->logo)
                                    <img src="{{ asset('storage/' . $store->logo) }}" 
                                         alt="{{ $store->store_name }}" class="store-logo">
                                @else
                                    <div class="store-logo-placeholder">
                                        <i class="fas fa-store"></i>
                                    </div>
                                @endif
                                <div class="store-details">
                                    <h3>{{ $store->store_name }}</h3>
                                    <span class="store-items-count">{{ $storeItems->count() }} منتج</span>
                                </div>
                            </div>
                            <form action="{{ route('front.cart.clear.store', $storeId) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="clear-store-btn" onclick="return confirm('هل أنت متأكد من حذف جميع منتجات هذا المتجر من السلة؟')">
                                    <i class="fas fa-trash"></i> حذف المتجر
                                </button>
                            </form>
                        </div>

                        <!-- منتجات المتجر -->
                        <div class="cart-items">
                            @foreach($storeItems as $item)
                            <div class="cart-item fade-in">
                                <div class="item-image">
                                    <img src="{{ asset($item->image) }}" 
                                         alt="{{ $item->product_title }}">
                                </div>
                                <div class="item-details">
                                    <h3 class="item-title">{{ $item->product_title }}</h3>
                                    <p class="item-seller">من متجر: {{ $item->store_name }}</p>
                                    @if($item->variant)
                                    <span class="variant-info">{{ $item->variant->name }}</span>
                                    @endif
                                    <div class="item-price">{{ number_format($item->price, 2) }} ر.ي</div>
                                    <div class="item-actions">
                                        <!-- تحديث الكمية -->
                                        <form action="{{ route('front.cart.update') }}" method="POST" class="quantity-form">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="cart_item_id" value="{{ $item->cart_item_id }}">
                                            <div class="quantity-control">
                                                <button type="button" class="quantity-btn" onclick="changeQuantity(this, -1)">-</button>
                                                <input type="number" name="quantity" class="quantity-input" 
                                                       value="{{ $item->quantity }}" min="1">
                                                <button type="button" class="quantity-btn" onclick="changeQuantity(this, 1)">+</button>
                                            </div>
                                            <button type="submit" class="update-btn" style="display:none">تحديث</button>
                                        </form>

                                        <!-- حذف المنتج -->
                                        <form action="{{ route('front.cart.remove') }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="cart_item_id" value="{{ $item->cart_item_id }}">
                                            <button type="submit" class="item-remove" onclick="return confirm('هل أنت متأكد من إزالة هذا المنتج من السلة؟')">
                                                <i class="fas fa-trash"></i> حذف
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- تذييل المتجر -->
                        <div class="store-footer">
                            <div class="store-total">
                                <span>إجمالي المتجر: <strong>{{ number_format($storeTotal, 2) }} ر.ي</strong></span>
                                <a href="{{ route('front.checkout.store', $storeId) }}" class="checkout-store-btn">
                                    <i class="fas fa-cash-register"></i> اتمام الشراء من هذا المتجر
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- ملخص الطلب العام -->
            <div class="order-summary fade-in">
                <h2 class="summary-title">ملخص الطلب العام</h2>
                
                <div class="summary-row">
                    <span>عدد المتاجر</span>
                    <span>{{ count($cartItemsByStore) }}</span>
                </div>
                
                <div class="summary-row">
                    <span>عدد المنتجات</span>
                    <span>{{ $totalCartCount }}</span>
                </div>
                
                <div class="summary-row">
                    <span>المجموع الكلي</span>
                    <span>{{ number_format($grandTotal, 2) }} ر.ي</span>
                </div>
                
                <div class="info-note">
                    <i class="fas fa-info-circle"></i>
                    <span>يمكنك اتمام الشراء من كل متجر على حدة</span>
                </div>
                
                <!-- تفريغ السلة بالكامل -->
                <form action="{{ route('front.cart.clear') }}" method="POST" class="d-inline w-100">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="clear-all-btn w-100" onclick="return confirm('هل أنت متأكد من أنك تريد تفريغ السلة بالكامل؟')">
                        <i class="fas fa-trash"></i> تفريغ السلة بالكامل
                    </button>
                </form>
                
                <a href="{{ route('front.products.index') }}" class="continue-shopping">
                    <i class="fas fa-arrow-right"></i> مواصلة التسوق
                </a>
            </div>
        </div>
        @else
        <!-- السلة فارغة -->
        <div class="empty-cart fade-in">
            <i class="fas fa-shopping-cart"></i>
            <h3>سلة التسوق فارغة</h3>
            <p>لم تقم بإضافة أي منتجات إلى سلة التسوق بعد</p>
            <a href="{{ route('front.products.index') }}" class="checkout-all-btn">تصفح المنتجات</a>
        </div>
        @endif
    </div>
</section>
@endsection

@section('script')
<script>
    // دالة لتغيير الكمية مع تحديث تلقائي
    function changeQuantity(button, change) {
        const form = button.closest('.quantity-form');
        const input = form.querySelector('.quantity-input');
        let newQuantity = parseInt(input.value) + change;
        
        if (newQuantity < 1) newQuantity = 1;
        
        input.value = newQuantity;
        
        // إرسال النموذج تلقائياً بعد التحديث
        setTimeout(() => {
            form.submit();
        }, 500);
    }

    // تحديث تلقائي عند تغيير القيمة يدوياً
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInputs = document.querySelectorAll('.quantity-input');
        quantityInputs.forEach(input => {
            input.addEventListener('change', function() {
                if (this.value < 1) this.value = 1;
                this.closest('.quantity-form').submit();
            });
        });

        // إخفاء الرسائل بعد 5 ثواني
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    });
</script>
@endsection