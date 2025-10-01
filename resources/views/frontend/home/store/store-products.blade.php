@extends('frontend.home.layouts.master')

@section('title', 'جميع منتجات ' . $store->store_name)
@section('meta_description', 'استعرض جميع منتجات متجر ' . $store->store_name)

@section('content')
    <!-- ================================
        START BREADCRUMB AREA
    ================================= -->
    <section class="breadcrumb-area bread-bg-9 py-0">
        <div class="breadcrumb-wrap">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('front.home') }}">الرئيسية</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('front.stores.show', $store->store_id) }}">{{ $store->store_name }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">جميع المنتجات</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ================================
        END BREADCRUMB AREA
    ================================= -->

    <!-- ================================
        START STORE PRODUCTS AREA
    ================================= -->
    <section class="store-products-area padding-top-60px padding-bottom-80px">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Store Header -->
                    <div class="store-header mb-5">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <div class="store-logo">
                                    <img src="{{ asset('static/images/stors/' . $store->logo) ?? '/images/store-default.jpg' }}" 
                                         alt="{{ $store->store_name }}" class="rounded-circle" width="80" height="80">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h1 class="store-title mb-2">{{ $store->store_name }}</h1>
                                <p class="store-description text-muted mb-0">
                                    {{ $store->description ?? 'متجر الكتروني متكامل' }}
                                </p>
                            </div>
                            <div class="col-md-2 text-end">
                                <a href="{{ route('front.stores.show', $store->store_id) }}" 
                                   class="theme-btn theme-btn-small theme-btn-transparent">
                                    <i class="la la-store me-1"></i>عودة للمتجر
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Products Filter and Count -->
                    <div class="products-filter-section mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="mb-0">عدد المنتجات: <span class="text-primary">{{ $products->total() }}</span></h5>
                            </div>
                            <div class="col-md-6 text-end">
                                <!-- يمكن إضافة فلاتر هنا -->
                            </div>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    @if($products->count() > 0)
                        <div class="row g-4">
                            @foreach($products as $product)
                                @include('frontend.home.partials.product-card', ['product' => $product])
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="pagination-wrapper mt-5">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="empty-products-icon mb-4">
                                <i class="la la-box-open la-3x text-muted opacity-25"></i>
                            </div>
                            <h4 class="text-muted mb-3">لا توجد منتجات</h4>
                            <p class="text-muted mb-4">لم يقم المتجر بإضافة أي منتجات بعد</p>
                            <a href="{{ route('front.stores.show', $store->store_id) }}" class="theme-btn">
                                <i class="la la-arrow-right me-2"></i>العودة لصفحة المتجر
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- ================================
        END STORE PRODUCTS AREA
    ================================= -->
@endsection

@section('styles')
<style>
/* نفس الـ CSS الموجود في store-detail */
.store-header {
    background: #fff;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    border: 1px solid #eaeaea;
}

.store-title {
    color: #2c3e50;
    font-weight: 700;
}

.products-filter-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
}

.empty-products-icon {
    opacity: 0.5;
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