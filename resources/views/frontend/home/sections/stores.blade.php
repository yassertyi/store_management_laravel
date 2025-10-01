<!-- ================================
    START STORES SECTION
================================= -->
<section class="stores-section section-padding bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading text-center">
                    <h2 class="sec__title line-height-55">المتاجر المميزة</h2>
                    <p class="sec__desc">اكتشف أفضل المتاجر لدينا واستمتع بتجربة تسوق فريدة</p>
                </div>
            </div>
        </div>

        <div class="row padding-top-50px">
            @foreach ($featuredStores as $store)
                <div class="col-lg-4 col-md-6">
                    <div class="store-card">
                        <!-- شريط الحالة -->
                        <div class="store-status-badge">
                            <span class="status online">🟢 متصل الآن</span>
                            <span class="products-count">{{ $store->products_count }} منتج</span>
                        </div>

                        <!-- الهيدر -->
                        <div class="store-header">
                            <div class="store-logo">
                                <img src="{{ $store->logo ? asset('static/images/stors/' . $store->logo) : '/images/store-default.jpg' }}"
                                    alt="{{ $store->store_name }}" class="store-img">
                                <div class="verified-badge" title="متجر موثوق">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                            <div class="store-info">
                                <h3 class="store-name">
                                    <a
                                        href="{{ route('front.stores.show', $store->store_id) }}">{{ $store->store_name }}</a>
                                </h3>
                                <div class="store-meta">
                                    <div class="store-rating">
                                        <div class="ratings">
                                            @php
                                                $storeRating = $store->products->avg('reviews.rating') ?? 0;
                                                $fullStars = floor($storeRating);
                                                $hasHalfStar = $storeRating - $fullStars >= 0.5;
                                            @endphp
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $fullStars)
                                                    <i class="fas fa-star text-warning"></i>
                                                @elseif($i == $fullStars + 1 && $hasHalfStar)
                                                    <i class="fas fa-star-half-alt text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-light"></i>
                                                @endif
                                            @endfor
                                            <span class="rating-text">{{ number_format($storeRating, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="store-location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ $store->addresses->first()->city ?? 'غير محدد' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- المحتوى -->
                        <div class="store-body">
                            <p class="store-description">
                                {{ Str::limit($store->description ?? 'متجر يقدم أفضل المنتجات بجودة عالية وخدمة مميزة', 100) }}
                            </p>

                            <!-- إحصائيات المتجر -->
                            <div class="store-stats">
                                <div class="stat-item">
                                    <i class="fas fa-shopping-bag"></i>
                                    <div class="stat-info">
                                        <span class="stat-number">{{ $store->products_count }}</span>
                                        <span class="stat-label">منتج</span>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-star"></i>
                                    <div class="stat-info">
                                        <span class="stat-number">{{ number_format($storeRating, 1) }}</span>
                                        <span class="stat-label">تقييم</span>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-clock"></i>
                                    <div class="stat-info">
                                        <span class="stat-number">{{ $store->created_at->diffForHumans() }}</span>
                                        <span class="stat-label">منضم</span>
                                    </div>
                                </div>
                            </div>

                            <!-- منتجات مميزة من المتجر -->
                            <div class="featured-products">
                                <h5>منتجات مميزة</h5>
                                <div class="products-grid">
                                    @foreach ($store->products->take(3) as $product)
                                        <div class="product-mini">
                                            <img src="{{ $product->images->first()->image_path ?? '/images/product-default.jpg' }}"
                                                alt="{{ $product->title }}">
                                            <div class="product-info">
                                                <span
                                                    class="product-title">{{ Str::limit($product->title, 20) }}</span>
                                                <span class="product-price">{{ number_format($product->price, 2) }}
                                                    ر.س</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- الفوتر -->
                        <div class="store-footer">
                            <div class="store-actions">
                                <a href="{{ route('front.stores.show', $store->store_id) }}"
                                    class="theme-btn theme-btn-small w-100 text-center">
                                    <i class="fas fa-store me-2"></i>زيارة المتجر
                                </a>
                                <div class="additional-actions">
                                    <button class="action-btn follow-store" data-store-id="{{ $store->store_id }}"
                                        title="متابعة المتجر">
                                        <i class="far fa-heart"></i>
                                    </button>
                                    <button class="action-btn share-store" data-store-id="{{ $store->store_id }}"
                                        title="مشاركة المتجر">
                                        <i class="fas fa-share-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($featuredStores->count() > 0)
            <div class="row padding-top-40px">
                <div class="col-lg-12">
                    <div class="btn-box text-center">
                        <a href="{{ route('front.stores.all') }}" class="theme-btn theme-btn-transparent">
                            <i class="fas fa-list me-2"></i>عرض جميع المتاجر
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="row padding-top-40px">
                <div class="col-lg-12">
                    <div class="empty-state text-center py-5">
                        <div class="empty-icon">
                            <i class="fas fa-store fa-4x text-muted"></i>
                        </div>
                        <h4 class="empty-title mt-3">لا توجد متاجر حالياً</h4>
                        <p class="empty-desc text-muted">سيتم إضافة المتاجر المميزة قريباً</p>
                        <a href="#" class="theme-btn mt-3">
                            <i class="fas fa-sync-alt me-2"></i>تحديث الصفحة
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
<!-- ================================
    END STORES SECTION
================================= -->
@section('script')
    <script>
        // في ملف JavaScript الخاص بك
        document.addEventListener('DOMContentLoaded', function() {
            // متابعة المتجر
            document.querySelectorAll('.follow-store').forEach(button => {
                button.addEventListener('click', function() {
                    const storeId = this.getAttribute('data-store-id');
                    const icon = this.querySelector('i');

                    if (icon.classList.contains('far')) {
                        // متابعة
                        followStore(storeId, this);
                    } else {
                        // إلغاء المتابعة
                        unfollowStore(storeId, this);
                    }
                });
            });

            // مشاركة المتجر
            document.querySelectorAll('.share-store').forEach(button => {
                button.addEventListener('click', function() {
                    const storeId = this.getAttribute('data-store-id');
                    shareStore(storeId);
                });
            });
        });

        function followStore(storeId, button) {
            // محاكاة API call
            const icon = button.querySelector('i');

            // تغيير الأيقونة
            icon.classList.remove('far');
            icon.classList.add('fas');
            button.style.color = '#e74c3c';

            // إضافة أنيميشن
            button.classList.add('pulse');
            setTimeout(() => {
                button.classList.remove('pulse');
            }, 600);

            showNotification('تمت متابعة المتجر بنجاح', 'success');
        }

        function unfollowStore(storeId, button) {
            // محاكاة API call
            const icon = button.querySelector('i');

            // تغيير الأيقونة
            icon.classList.remove('fas');
            icon.classList.add('far');
            button.style.color = '';

            showNotification('تم إلغاء متابعة المتجر', 'info');
        }

        function shareStore(storeId) {
            const storeUrl = `${window.location.origin}/stores/${storeId}`;

            if (navigator.share) {
                navigator.share({
                        title: 'متجر مميز',
                        text: 'اكتشف هذا المتجر الرائع',
                        url: storeUrl,
                    })
                    .then(() => showNotification('تمت المشاركة بنجاح', 'success'))
                    .catch(() => fallbackShare(storeUrl));
            } else {
                fallbackShare(storeUrl);
            }
        }

        function fallbackShare(url) {
            // نسخ الرابط إلى الحافظة
            navigator.clipboard.writeText(url).then(() => {
                showNotification('تم نسخ رابط المتجر', 'success');
            }).catch(() => {
                // طريقة بديلة
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
            // كود الإشعارات السابق الذي لديك
            const notification = document.createElement('div');
            notification.className = `custom-notification ${type}`;
            notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check' : 'info'}-circle"></i>
            <span>${message}</span>
        `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
        } <
        script >
        @endsection
