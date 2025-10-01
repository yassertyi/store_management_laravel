@extends('frontend.home.layouts.master')

@section('title', $store->store_name . ' - متجرنا')
@section('meta_description', Str::limit($store->description, 160))

@section('content')

    <!-- ================================
        START STORE DETAIL AREA
    ================================= -->
    <section class="store-detail-area padding-top-80px padding-bottom-80px">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <!-- Store Info Sidebar -->
                    <div class="sidebar store-sidebar">
                        <div class="sidebar-widget">
                            <div class="store-info-card text-center">
                                <div class="store-logo mb-3">
                                    <img src="{{ asset('static/images/stors/' . $store->logo) ?? '/images/store-default.jpg' }}" 
                                         alt="{{ $store->store_name }}" class="rounded-circle" width="120" height="120">
                                </div>
                                <h3 class="store-name mb-2">{{ $store->store_name }}</h3>
                                <div class="store-rating mb-3">
                                    <div class="ratings d-flex align-items-center justify-content-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="la la-star {{ $i <= floor($storeRating) ? 'text-warning' : 'text-light' }}"></i>
                                        @endfor
                                        <span class="ms-2">{{ number_format($storeRating, 1) }}</span>
                                    </div>
                                </div>
                                <p class="store-description text-muted mb-4">
                                    {{ $store->description ?? 'لا يوجد وصف للمتجر' }}
                                </p>
                                
                                <div class="store-stats mb-4">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="stat-item">
                                                <h4 class="stat-number text-primary">{{ $totalProducts }}</h4>
                                                <p class="stat-label">المنتجات</p>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="stat-item">
                                                <h4 class="stat-number text-success">{{ $store->status === 'active' ? 'نشط' : 'غير نشط' }}</h4>
                                                <p class="stat-label">الحالة</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Store Contact Info -->
                                <div class="store-contact-info mb-4">
                                    <h5 class="mb-3">معلومات الاتصال</h5>
                                    @if($store->phones->count() > 0)
                                        @foreach($store->phones as $phone)
                                            <div class="contact-item d-flex align-items-center mb-2">
                                                <i class="la la-phone me-2 text-primary"></i>
                                                <span>{{ $phone->phone }}</span>
                                                @if($phone->is_primary)
                                                    <span class="badge bg-primary ms-2">رئيسي</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted">لا توجد أرقام هاتف</p>
                                    @endif
                                </div>

                                <!-- Store Address -->
                                @if($store->addresses->count() > 0)
                                <div class="store-address-info">
                                    <h5 class="mb-3">العنوان</h5>
                                    @foreach($store->addresses as $address)
                                        <div class="address-item mb-2">
                                            <div class="d-flex align-items-start">
                                                <i class="la la-map-marker me-2 text-primary mt-1"></i>
                                                <div>
                                                    <p class="mb-1">{{ $address->street }}, {{ $address->city }}</p>
                                                    <p class="text-muted mb-0">{{ $address->country }}</p>
                                                    @if($address->zip_code)
                                                        <p class="text-muted mb-0">الرمز البريدي: {{ $address->zip_code }}</p>
                                                    @endif
                                                    @if($address->is_primary)
                                                        <span class="badge bg-success">العنوان الرئيسي</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <!-- Store Content -->
                    <div class="store-content">
                        <!-- Store Banner -->
                        @if($store->banner)
                        <div class="store-banner mb-4">
                            <img src="{{ asset('static/images/stors/' . $store->banner) }}" 
                                 alt="{{ $store->store_name }}" class="rounded w-100" style="max-height: 300px; object-fit: cover;">
                        </div>
                        @endif

                        <!-- Store Navigation -->
                        <div class="store-navigation mb-4">
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <button class="nav-link active" id="nav-featured-tab" data-bs-toggle="tab" 
                                            data-bs-target="#nav-featured" type="button" role="tab">
                                        المنتجات المميزة
                                    </button>
                                    <button class="nav-link" id="nav-latest-tab" data-bs-toggle="tab" 
                                            data-bs-target="#nav-latest" type="button" role="tab">
                                        أحدث المنتجات
                                    </button>
                                    <button class="nav-link" id="nav-all-tab" data-bs-toggle="tab" 
                                            data-bs-target="#nav-all" type="button" role="tab">
                                        جميع المنتجات
                                    </button>
                                </div>
                            </nav>
                        </div>

                        <!-- Store Tabs Content -->
                        <div class="tab-content" id="nav-tabContent">
                            <!-- Featured Products Tab -->
                            <div class="tab-pane fade show active" id="nav-featured" role="tabpanel">
                                @if($featuredProducts->count() > 0)
                                    <div class="row g-4">
                                        @foreach($featuredProducts as $product)
                                            @include('frontend.home.partials.product-card', ['product' => $product])
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="la la-box la-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">لا توجد منتجات مميزة</h5>
                                        <p class="text-muted">لم يقم المتجر بإضافة أي منتجات مميزة بعد</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Latest Products Tab -->
                            <div class="tab-pane fade" id="nav-latest" role="tabpanel">
                                @if($latestProducts->count() > 0)
                                    <div class="row g-4">
                                        @foreach($latestProducts as $product)
                                            @include('frontend.home.partials.product-card', ['product' => $product])
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="la la-box la-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">لا توجد منتجات حديثة</h5>
                                        <p class="text-muted">لم يقم المتجر بإضافة أي منتجات بعد</p>
                                    </div>
                                @endif
                            </div>

                            <!-- All Products Tab -->
                            <div class="tab-pane fade" id="nav-all" role="tabpanel">
                                <div class="text-center">
                                    <a href="{{ route('front.stores.products', $store->store_id) }}" 
                                       class="theme-btn theme-btn-transparent">
                                        <i class="la la-list me-2"></i>عرض جميع منتجات المتجر
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ================================
        END STORE DETAIL AREA
    ================================= -->
@endsection

@section('styles')
<style>
.store-info-card {
    background: #fff;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    border: 1px solid #eaeaea;
}

.store-logo img {
    border: 3px solid #f8f9fa;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.store-name {
    color: #2c3e50;
    font-weight: 700;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.stat-label {
    color: #6c757d;
    font-size: 0.875rem;
    margin-bottom: 0;
}

.contact-item, .address-item {
    padding: 8px 0;
    border-bottom: 1px solid #f8f9fa;
}

.contact-item:last-child, .address-item:last-child {
    border-bottom: none;
}

.store-banner img {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.nav-tabs .nav-link {
    color: #6c757d;
    font-weight: 500;
    border: none;
    padding: 12px 20px;
}

.nav-tabs .nav-link.active {
    color: #3a77ff;
    border-bottom: 3px solid #3a77ff;
    background: transparent;
}

/* تصميم بطاقات المنتجات */
.product-card {
    border: 1px solid #e8e8e8;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    background: #fff;
    margin-bottom: 0;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    border-color: #3a77ff;
}

.card-img {
    position: relative;
    overflow: hidden;
}

.product-image {
    width: 100%;
    height: 220px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

/* البادجات */
.badge {
    position: absolute;
    top: 12px;
    left: 12px;
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 600;
    border-radius: 6px;
    z-index: 2;
}

.discount-badge {
    background: linear-gradient(45deg, #ff6b6b, #ee5a24);
    color: white;
}

.new-badge {
    background: linear-gradient(45deg, #4ecdc4, #44a08d);
    color: white;
}

.featured-badge {
    background: linear-gradient(45deg, #ffd93d, #ff9a3d);
    color: #333;
}

.rating-badge {
    background: linear-gradient(45deg, #ff9a3d, #ff6b6b);
}

/* زر المفضلة */
.wishlist-btn {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
    transition: all 0.3s ease;
    z-index: 2;
}

.wishlist-btn:hover {
    background: #ff6b6b;
    color: white;
    transform: scale(1.1);
}

.wishlist-btn.active {
    background: #ff6b6b;
    color: white;
}

/* محتوى البطاقة */
.card-body {
    padding: 20px;
}

.card-title {
    margin-bottom: 8px;
}

.card-title a {
    color: #2c3e50;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.3s ease;
}

.card-title a:hover {
    color: #3a77ff;
}

.card-meta {
    color: #6c757d;
    font-size: 13px;
    margin-bottom: 6px;
}

.card-rating {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 15px;
}

.rating-badge {
    padding: 4px 8px;
    font-size: 11px;
}

.review__text {
    color: #6c757d;
    font-size: 12px;
}

/* السعر */
.card-price {
    border-top: 1px solid #f8f9fa;
    padding-top: 15px;
    margin-top: 15px;
}

.price__from {
    display: block;
    color: #6c757d;
    font-size: 12px;
    margin-bottom: 4px;
}

.price__num {
    color: #2c3e50;
    font-weight: 700;
    font-size: 18px;
}

.before-price {
    color: #6c757d;
    font-size: 14px;
    text-decoration: line-through;
    margin-right: 8px;
    font-weight: normal;
}

/* زر إضافة إلى السلة */
.add-to-cart-btn {
    background: #3a77ff;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
}

.add-to-cart-btn:hover {
    background: #2c5fd1;
    color: white;
    transform: translateY(-2px);
}

.store-sidebar {
    position: sticky;
    top: 100px;
}

/* تباعد الصفوف */
.g-4 {
    gap: 1.5rem !important;
}
</style>
@endsection

@section('scripts')
<script>
// إضافة إلى المفضلة
document.querySelectorAll('.wishlist-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const productId = this.dataset.productId;
        const isInWishlist = this.dataset.inWishlist === 'true';
        
        // تبديل حالة المفضلة
        this.classList.toggle('active');
        const icon = this.querySelector('i');
        icon.classList.toggle('la-heart');
        icon.classList.toggle('la-heart-o');
        
        // إرسال طلب AJAX
        toggleWishlist(productId, isInWishlist);
    });
});

// إضافة إلى السلة
document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const productId = this.dataset.productId;
        const storeId = this.dataset.storeId;
        const productTitle = this.dataset.productTitle;
        
        // إضافة مؤشر تحميل
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="la la-spinner la-spin"></i> جاري الإضافة...';
        this.disabled = true;
        
        // إرسال طلب AJAX
        addToCart(productId, 1, null, () => {
            // استعادة الحالة الأصلية بعد النجاح
            this.innerHTML = originalText;
            this.disabled = false;
        });
    });
});

// دالة إضافة إلى المفضلة
function toggleWishlist(productId, isInWishlist) {
    const url = isInWishlist ? '{{ route("front.wishlist.remove") }}' : '{{ route("front.wishlist.add") }}';
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
        } else {
            if (data.login_required) {
                showAlert('info', 'يجب تسجيل الدخول أولاً');
                setTimeout(() => {
                    window.location.href = '{{ route("login") }}';
                }, 2000);
            } else {
                showAlert('error', data.message);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'حدث خطأ أثناء التحديث');
    });
}

// دالة إضافة إلى السلة
function addToCart(productId, quantity, variantId = null, callback = null) {
    const data = {
        product_id: productId,
        quantity: quantity,
        _token: '{{ csrf_token() }}'
    };

    if (variantId) {
        data.variant_id = variantId;
    }

    fetch('{{ route("front.cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: new URLSearchParams(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            updateCartCount();
        } else {
            showAlert('error', data.message);
        }
        if (callback) callback();
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'حدث خطأ أثناء إضافة المنتج إلى السلة');
        if (callback) callback();
    });
}

// وظائف مساعدة
function showAlert(type, message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: type,
            title: message,
            showConfirmButton: false,
            timer: 3000
        });
    } else {
        alert(message);
    }
}

function updateCartCount() {
    // تحديث عداد السلة
    fetch('{{ route("front.cart.count") }}')
        .then(response => response.json())
        .then(data => {
            const cartCounts = document.querySelectorAll('.cart-count, .cart-count-badge');
            cartCounts.forEach(count => {
                count.textContent = data;
            });
        });
}
</script>
@endsection