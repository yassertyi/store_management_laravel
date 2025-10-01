@extends('frontend.home.layouts.master')

@section('title', 'جميع المتاجر')
@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('static/css/home.css') }}" />
    <link rel="stylesheet" href="{{ asset('static/css/stores.css') }}" />
@endsection

@section('content')
<!-- ================================
    START ALL STORES SECTION
================================= -->
<section class="stores-section section-padding">
    <div class="container">
        <!-- رأس الصفحة -->
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading text-center">
                    <h2 class="sec__title line-height-55">جميع المتاجر</h2>
                    <p class="sec__desc">اكتشف مجموعة متنوعة من المتاجر المميزة</p>
                </div>
            </div>
        </div>

        <!-- شريط البحث والفلترة -->
        <div class="row">
            <div class="col-lg-12">
                <div class="stores-filter-bar">
                    <form action="{{ route('front.stores.all') }}" method="GET" class="filter-form">
                        <div class="row align-items-center">
                            <div class="col-lg-4">
                                <div class="search-box">
                                    <i class="fas fa-search"></i>
                                    <input type="text" name="search" placeholder="ابحث عن متجر..." 
                                           value="{{ request('search') }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="filter-select">
                                    <select name="city" class="form-select">
                                        <option value="all">جميع المدن</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                                {{ $city }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="filter-select">
                                    <select name="sort" class="form-select" onchange="this.form.submit()">
                                        <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>الأحدث</option>
                                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>الاسم من أ إلى ي</option>
                                        <option value="products" {{ request('sort') == 'products' ? 'selected' : '' }}>الأكثر منتجات</option>
                                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>الأعلى تقييماً</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <button type="submit" class="theme-btn w-100">
                                    <i class="fas fa-filter"></i> تطبيق
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- قائمة المتاجر -->
        <div class="row stores-grid">
            @forelse($stores as $store)
            <div class="col-lg-4 col-md-6">
                <div class="store-card">
                    <!-- شارة المتجر المميز -->
                    @if($store->products_count > 50)
                    <div class="store-badge featured">
                        <i class="fas fa-crown"></i> مميز
                    </div>
                    @endif

                    <!-- صورة المتجر -->
                    <div class="store-cover">
                        @if($store->banner)
                            <img src="{{ asset('static/images/stors/' . $store->banner) }}" alt="{{ $store->store_name }}" class="cover-img">
                        @else
                            <div class="cover-placeholder">
                                <i class="fas fa-store"></i>
                            </div>
                        @endif
                        <div class="store-logo-overlay">
                            <img src="{{ $store->logo ? asset('static/images/stors/' . $store->logo) : '/images/store-default.jpg' }}" 
                                 alt="{{ $store->store_name }}" class="store-logo">
                        </div>
                    </div>

                    <!-- معلومات المتجر -->
                    <div class="store-info">
                        <h3 class="store-name">
                            <a href="{{ route('front.stores.show', $store->store_id) }}">{{ $store->store_name }}</a>
                        </h3>
                        
                        <div class="store-description">
                            <p>{{ Str::limit($store->description ?? 'متجر يقدم منتجات متنوعة بجودة عالية', 80) }}</p>
                        </div>

                        <!-- التقييم والإحصائيات -->
                        <div class="store-stats">
                            <div class="rating-section">
                                @php
                                    $storeRating = $store->products->avg('reviews.rating') ?? 0;
                                    $fullStars = floor($storeRating);
                                    $hasHalfStar = $storeRating - $fullStars >= 0.5;
                                @endphp
                                <div class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $fullStars)
                                            <i class="fas fa-star"></i>
                                        @elseif($i == $fullStars + 1 && $hasHalfStar)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="rating-text">({{ number_format($storeRating, 1) }})</span>
                            </div>

                            <div class="store-meta">
                                <div class="meta-item">
                                    <i class="fas fa-shopping-bag"></i>
                                    <span>{{ $store->products_count }} منتج</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $store->addresses->first()->city ?? 'غير محدد' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- المنتجات الشائعة -->
                        <div class="popular-products">
                            <h6>منتجات شائعة</h6>
                            <div class="products-scroll">
                                @foreach($store->products->take(2) as $product)
                                <div class="popular-product">
                                    <img src="{{ $product->images->first()->image_path ?? '/images/product-default.jpg' }}" 
                                         alt="{{ $product->title }}">
                                    <div class="product-details">
                                        <span class="product-name">{{ Str::limit($product->title, 15) }}</span>
                                        <span class="product-price">{{ number_format($product->price, 2) }} ر.س</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- أزرار الإجراءات -->
                    <div class="store-actions">
                        <a href="{{ route('front.stores.show', $store->store_id) }}" class="visit-store-btn">
                            <i class="fas fa-store"></i>
                            زيارة المتجر
                        </a>
                        <div class="action-buttons">
                            <button class="action-btn follow-btn" data-store-id="{{ $store->store_id }}" title="متابعة">
                                <i class="far fa-heart"></i>
                            </button>
                            <button class="action-btn share-btn" data-store-id="{{ $store->store_id }}" title="مشاركة">
                                <i class="fas fa-share-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <!-- حالة عدم وجود متاجر -->
            <div class="col-lg-12">
                <div class="empty-state text-center py-5">
                    <div class="empty-icon">
                        <i class="fas fa-store fa-4x text-muted"></i>
                    </div>
                    <h4 class="empty-title mt-3">لا توجد متاجر</h4>
                    <p class="empty-desc text-muted">
                        @if(request()->has('search') && request('search') != '')
                            لم نتمكن من العثور على متاجر تطابق "{{ request('search') }}"
                        @else
                            لا توجد متاجر متاحة حالياً
                        @endif
                    </p>
                    <a href="#" class="theme-btn mt-3">
                        <i class="fas fa-sync-alt me-2"></i>تحديث الصفحة
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- التقسيم (Pagination) -->
        @if($stores->hasPages())
        <div class="row padding-top-40px">
            <div class="col-lg-12">
                <div class="pagination-wrapper">
                    {{ $stores->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
<!-- ================================
    END ALL STORES SECTION
================================= -->
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // إضافة تأثيرات تفاعلية للبطاقات
    const storeCards = document.querySelectorAll('.store-card');
    
    storeCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.zIndex = '10';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.zIndex = '1';
        });
    });

    // متابعة المتجر
    document.querySelectorAll('.follow-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const storeId = this.getAttribute('data-store-id');
            const icon = this.querySelector('i');
            
            if (icon.classList.contains('far')) {
                followStore(storeId, this);
            } else {
                unfollowStore(storeId, this);
            }
        });
    });

    // مشاركة المتجر
    document.querySelectorAll('.share-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const storeId = this.getAttribute('data-store-id');
            shareStore(storeId);
        });
    });

    // فلترة تلقائية
    document.querySelector('select[name="city"]').addEventListener('change', function() {
        this.form.submit();
    });

    document.querySelector('select[name="sort"]').addEventListener('change', function() {
        this.form.submit();
    });

    // بحث تلقائي مع تأخير
    let searchTimeout;
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 800);
        });
    }
});

function followStore(storeId, button) {
    // محاكاة API call
    fetch(`/stores/${storeId}/follow`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        const icon = button.querySelector('i');
        if (data.success) {
            icon.classList.remove('far');
            icon.classList.add('fas');
            button.style.color = '#e74c3c';
            button.classList.add('pulse');
            setTimeout(() => button.classList.remove('pulse'), 600);
            showNotification('تمت متابعة المتجر بنجاح', 'success');
        }
    })
    .catch(error => {
        showNotification('حدث خطأ أثناء المتابعة', 'error');
    });
}

function unfollowStore(storeId, button) {
    // محاكاة API call
    fetch(`/stores/${storeId}/unfollow`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        const icon = button.querySelector('i');
        if (data.success) {
            icon.classList.remove('fas');
            icon.classList.add('far');
            button.style.color = '';
            showNotification('تم إلغاء متابعة المتجر', 'info');
        }
    })
    .catch(error => {
        showNotification('حدث خطأ أثناء إلغاء المتابعة', 'error');
    });
}

function shareStore(storeId) {
    const storeUrl = `${window.location.origin}/stores/${storeId}`;
    
    if (navigator.share) {
        navigator.share({
            title: 'اكتشف هذا المتجر الرائع',
            text: 'توجد منتجات رائعة في هذا المتجر - تأكد من زيارته!',
            url: storeUrl,
        })
        .then(() => showNotification('تمت المشاركة بنجاح', 'success'))
        .catch(() => fallbackShare(storeUrl));
    } else {
        fallbackShare(storeUrl);
    }
}

function fallbackShare(url) {
    navigator.clipboard.writeText(url).then(() => {
        showNotification('تم نسخ رابط المتجر إلى الحافظة', 'success');
    }).catch(() => {
        const tempInput = document.createElement('input');
        tempInput.value = url;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        showNotification('تم نسخ رابط المتجر', 'success');
    });
}

function showNotification(message, type) {
    // إنشاء إشعار جميل
    const notification = document.createElement('div');
    notification.className = `custom-notification ${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        z-index: 10000;
        animation: slideInRight 0.3s ease;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        backdrop-filter: blur(10px);
        font-family: 'Tajawal', sans-serif;
    `;
    
    const bgColors = {
        success: 'linear-gradient(135deg, #00b894, #00a085)',
        error: 'linear-gradient(135deg, #ff7675, #e17055)',
        info: 'linear-gradient(135deg, #74b9ff, #0984e3)'
    };
    
    notification.style.background = bgColors[type] || bgColors.info;
    
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info'}-circle me-2"></i>
        ${message}
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// إضافة أنيميشن للإشعارات
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);
</script>
@endsection