<section class="featured-products-area section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading text-center">
                    <h2 class="sec__title line-height-55">منتجات </h2>
                    <p>اكتشف أفضل منتجاتنا المختارة بعناية</p>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="row">
            <div class="col-lg-12">
                <div class="filter-tabs">
                    <div class="filter-tab active" data-filter="all">الكل</div>
                    <div class="filter-tab" data-filter="new">جديد</div>
                    <div class="filter-tab" data-filter="sale">عروض</div>
                    <div class="filter-tab" data-filter="bestseller">الأكثر مبيعاً</div>
                </div>
            </div>
        </div>

        <!-- View Options -->
        <div class="row">
            <div class="col-lg-12">
                <div class="view-options">
                    <div class="view-btn active" data-view="grid">
                        <i class="fas fa-th"></i>
                    </div>
                    <div class="view-btn" data-view="list">
                        <i class="fas fa-list"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row padding-top-50px products-container">
            @foreach($allProducts as $product)
            <div class="col-lg-3 col-md-6 product-item" 
                 data-product-id="{{ $product->product_id }}"
                 data-is-new="{{ $product->created_at->diffInDays(now()) < 30 ? 'true' : 'false' }}"
                 data-on-sale="{{ $product->compare_price ? 'true' : 'false' }}"
                 data-rating="{{ $product->reviews->avg('rating') ?? 0 }}"
                 data-category="{{ $product->category->name }}"
                 data-description="{{ $product->description }}">
                <div class="card-item product-card">
                    <div class="card-img">
                        <a href="#" class="d-block">
                            <img src="{{ $product->images->first() ? asset($product->images->first()->image_path) : 'https://via.placeholder.com/500x300' }}" 
                                 alt="{{ $product->title }}" />
                        </a>
                        @if($product->compare_price)
                            @php
                                $discountPercent = round((($product->compare_price - $product->price) / $product->compare_price) * 100);
                            @endphp
                            <span class="badge">-{{ $discountPercent }}%</span>
                        @elseif($product->created_at->diffInDays(now()) < 30)
                            <span class="badge new">جديد</span>
                        @endif
                        <div class="card-actions">
                            <button class="action-btn wishlist-btn" data-product-id="{{ $product->product_id }}" title="إضافة إلى المفضلة">
                                <i class="far fa-heart"></i>
                            </button>
                            <button class="action-btn compare-btn" title="مقارنة المنتج">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                        </div>
                        <div class="quick-view">
                            <button class="quick-view-btn" data-product-id="{{ $product->product_id }}">معاينة سريعة</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">
                            <a href="#">{{ $product->title }}</a>
                        </h3>
                        <div class="card-meta">
                            <i class="fas fa-tag"></i> {{ $product->category->name }}
                        </div>
                        <div class="card-rating">
                            <div class="rating-stars">
                                @php
                                    $rating = $product->reviews->avg('rating') ?? 0;
                                    $fullStars = floor($rating);
                                    $hasHalfStar = $rating - $fullStars >= 0.5;
                                @endphp
                                @for($i = 0; $i < $fullStars; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                                @if($hasHalfStar)
                                    <i class="fas fa-star-half-alt"></i>
                                @endif
                                @for($i = 0; $i < (5 - $fullStars - ($hasHalfStar ? 1 : 0)); $i++)
                                    <i class="far fa-star"></i>
                                @endfor
                            </div>
                            <span class="rating-count">({{ $product->reviews->count() }} تقييم)</span>
                        </div>
                        <div class="card-price">
                            <div class="price-container">
                                <span class="price__num">{{ number_format($product->price, 2) }} ريال</span>
                                @if($product->compare_price)
                                    <span class="before-price">{{ number_format($product->compare_price, 2) }} ريال</span>
                                @endif
                            </div>
                            <span class="stock-status {{ $product->stock > 0 ? 'in-stock' : 'out-stock' }}">
                                {{ $product->stock > 0 ? 'متوفر' : 'غير متوفر' }}
                            </span>
                        </div>
                        <div class="card-action">
                            <a href="#" class="btn-text"><i class="fas fa-shopping-cart"></i> أضف إلى السلة</a>
                            <a href="#" class="theme-btn">شراء الآن</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Load More Button -->
        <div class="row">
    <div class="col-lg-12 text-center">
        <button id="load-more" class="theme-btn">تحميل المزيد</button>
        <a href="{{ route('front.products.index') }}" class="theme-btn theme-btn-secondary" style="margin-right: 10px;">
            <i class="fas fa-list"></i> عرض جميع المنتجات
        </a>
    </div>
</div>
    </div>
</section>

<!-- Quick View Modal -->
<div class="modal" id="quickViewModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">معاينة سريعة</h3>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="modal-image">
                <img id="modalProductImage" src="" alt="Product Image">
            </div>
            <div class="modal-details">
                <h2 id="modalProductTitle"></h2>
                <div class="card-rating">
                    <div class="rating-stars" id="modalRatingStars"></div>
                    <span class="rating-count" id="modalRatingCount"></span>
                </div>
                <div class="modal-price">
                    <span id="modalProductPrice"></span>
                    <span id="modalProductOldPrice" class="before-price"></span>
                </div>
                <p id="modalProductDescription" class="modal-description"></p>
                <div class="stock-status in-stock" id="modalStockStatus">متوفر في المخزون</div>
                <div class="modal-actions">
                    <div class="quantity-selector">
                        <button class="quantity-btn minus">-</button>
                        <input type="text" class="quantity-input" value="1" readonly>
                        <button class="quantity-btn plus">+</button>
                    </div>
                    <a href="#" class="theme-btn">أضف إلى السلة</a>
                </div>
            </div>
        </div>
    </div>
</div>
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // عناصر DOM
        const productsContainer = document.querySelector('.products-container');
        const filterTabs = document.querySelectorAll('.filter-tab');
        const viewButtons = document.querySelectorAll('.view-btn');
        const loadMoreButton = document.getElementById('load-more');
        const quickViewModal = document.getElementById('quickViewModal');
        const closeModalButton = document.querySelector('.close-modal');

        // حالة التطبيق
        let currentFilter = 'all';
        let currentView = 'grid';
        let displayedProducts = 8; // عرض أول 8 منتجات

        // إعداد مستمعي الأحداث
        function setupEventListeners() {
            // علامات التصفية
            filterTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    filterTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    currentFilter = this.getAttribute('data-filter');
                    filterProducts();
                });
            });

            // أزرار طريقة العرض
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    viewButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    currentView = this.getAttribute('data-view');
                    productsContainer.className = `row padding-top-50px products-container ${currentView}-view`;
                    applyViewMode();
                });
            });

            // زر تحميل المزيد
            loadMoreButton.addEventListener('click', function() {
                displayedProducts += 4;
                showProducts();
                
                // إخفاء الزر إذا تم عرض جميع المنتجات
                if (displayedProducts >= getVisibleProducts().length) {
                    loadMoreButton.style.display = 'none';
                }
            });

            // إغلاق النافذة المنبثقة
            closeModalButton.addEventListener('click', closeModal);
            quickViewModal.addEventListener('click', function(e) {
                if (e.target === quickViewModal) {
                    closeModal();
                }
            });

            // إضافة مستمعي الأحداث لأزرار الكمية
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('quantity-btn')) {
                    const input = e.target.parentElement.querySelector('.quantity-input');
                    let value = parseInt(input.value);
                    
                    if (e.target.classList.contains('plus')) {
                        input.value = value + 1;
                    } else if (e.target.classList.contains('minus') && value > 1) {
                        input.value = value - 1;
                    }
                }
                
                // معاينة سريعة
                if (e.target.classList.contains('quick-view-btn')) {
                    const productId = parseInt(e.target.getAttribute('data-product-id'));
                    openQuickView(productId);
                }
                
                // إضافة إلى المفضلة
                if (e.target.closest('.wishlist-btn')) {
                    const productId = parseInt(e.target.closest('.wishlist-btn').getAttribute('data-product-id'));
                    toggleWishlist(productId);
                }
            });
        }

        // تصفية المنتجات بناءً على التصنيف المحدد
        function filterProducts() {
            const allProducts = document.querySelectorAll('.product-item');
            
            allProducts.forEach(product => {
                product.style.display = 'none';
                
                const isNew = product.getAttribute('data-is-new') === 'true';
                const onSale = product.getAttribute('data-on-sale') === 'true';
                const rating = parseFloat(product.getAttribute('data-rating'));
                
                let shouldShow = false;
                
                switch(currentFilter) {
                    case 'all':
                        shouldShow = true;
                        break;
                    case 'new':
                        shouldShow = isNew;
                        break;
                    case 'sale':
                        shouldShow = onSale;
                        break;
                    case 'bestseller':
                        shouldShow = rating >= 4.5;
                        break;
                }
                
                if (shouldShow) {
                    product.style.display = 'block';
                }
            });
            
            showProducts();
        }

        // تطبيق نمط العرض (grid/list)
        function applyViewMode() {
            const products = document.querySelectorAll('.product-item');
            
            products.forEach(product => {
                if (currentView === 'grid') {
                    product.className = 'col-lg-3 col-md-6 product-item';
                } else {
                    product.className = 'col-lg-12 product-item';
                    
                    // تعديل التنسيق لعرض القائمة
                    const productCard = product.querySelector('.product-card');
                    productCard.style.display = 'flex';
                    productCard.style.gap = '20px';
                    
                    const cardImg = productCard.querySelector('.card-img');
                    cardImg.style.flex = '0 0 300px';
                    
                    const cardBody = productCard.querySelector('.card-body');
                    cardBody.style.flex = '1';
                }
            });
        }

        // الحصول على المنتجات المرئية
        function getVisibleProducts() {
            return Array.from(document.querySelectorAll('.product-item'))
                .filter(product => product.style.display !== 'none');
        }

        // عرض المنتجات مع التحكم في العدد
        function showProducts() {
            const visibleProducts = getVisibleProducts();
            
            // إخفاء جميع المنتجات أولاً
            visibleProducts.forEach(product => {
                product.style.display = 'none';
            });
            
            // إظهار العدد المطلوب من المنتجات
            const productsToShow = visibleProducts.slice(0, displayedProducts);
            productsToShow.forEach(product => {
                product.style.display = 'block';
            });
            
            // التحكم في زر "تحميل المزيد"
            if (displayedProducts >= visibleProducts.length) {
                loadMoreButton.style.display = 'none';
            } else {
                loadMoreButton.style.display = 'block';
            }
        }

        // فتح نافذة المعاينة السريعة
        function openQuickView(productId) {
            const productElement = document.querySelector(`.product-item[data-product-id="${productId}"]`);
            if (!productElement) return;
            
            // جمع بيانات المنتج من العنصر
            const productImage = productElement.querySelector('.card-img img').src;
            const productTitle = productElement.querySelector('.card-title a').textContent;
            const productPrice = productElement.querySelector('.price__num').textContent;
            const oldPriceElement = productElement.querySelector('.before-price');
            const productOldPrice = oldPriceElement ? oldPriceElement.textContent : '';
            const productDescription = productElement.getAttribute('data-description') || 'وصف المنتج غير متوفر';
            const stockStatus = productElement.querySelector('.stock-status').textContent;
            const ratingStars = productElement.querySelector('.rating-stars').innerHTML;
            const ratingCount = productElement.querySelector('.rating-count').textContent;
            
            // تعبئة البيانات في النافذة المنبثقة
            document.getElementById('modalProductImage').src = productImage;
            document.getElementById('modalProductTitle').textContent = productTitle;
            document.getElementById('modalProductPrice').textContent = productPrice;
            
            if (productOldPrice) {
                document.getElementById('modalProductOldPrice').textContent = productOldPrice;
            } else {
                document.getElementById('modalProductOldPrice').textContent = '';
            }
            
            document.getElementById('modalProductDescription').textContent = productDescription;
            document.getElementById('modalStockStatus').textContent = stockStatus;
            document.getElementById('modalStockStatus').className = `stock-status ${stockStatus === 'متوفر' ? 'in-stock' : 'out-stock'}`;
            document.getElementById('modalRatingStars').innerHTML = ratingStars;
            document.getElementById('modalRatingCount').textContent = ratingCount;
            
            quickViewModal.style.display = 'flex';
        }

        // إغلاق نافذة المعاينة السريعة
        function closeModal() {
            quickViewModal.style.display = 'none';
        }

        // تبديل حالة المفضلة
        function toggleWishlist(productId) {
            const wishlistBtn = document.querySelector(`.wishlist-btn[data-product-id="${productId}"]`);
            const icon = wishlistBtn.querySelector('i');
            
            if (icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                wishlistBtn.style.color = '#e74c3c';
                showNotification('تمت إضافة المنتج إلى المفضلة');
                
                // إرسال طلب AJAX لحفظ في قاعدة البيانات
                addToWishlist(productId);
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                wishlistBtn.style.color = '';
                showNotification('تمت إزالة المنتج من المفضلة');
                
                // إرسال طلب AJAX لإزالة من قاعدة البيانات
                removeFromWishlist(productId);
            }
        }

        // إضافة إلى المفضلة (AJAX)
        function addToWishlist(productId) {
            fetch('/wishlist/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    showNotification('حدث خطأ أثناء الإضافة إلى المفضلة');
                    // التراجع عن التغيير البصري
                    const wishlistBtn = document.querySelector(`.wishlist-btn[data-product-id="${productId}"]`);
                    const icon = wishlistBtn.querySelector('i');
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    wishlistBtn.style.color = '';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('حدث خطأ في الاتصال');
            });
        }

        // إزالة من المفضلة (AJAX)
        function removeFromWishlist(productId) {
            fetch('/wishlist/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    showNotification('حدث خطأ أثناء الإزالة من المفضلة');
                    // التراجع عن التغيير البصري
                    const wishlistBtn = document.querySelector(`.wishlist-btn[data-product-id="${productId}"]`);
                    const icon = wishlistBtn.querySelector('i');
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    wishlistBtn.style.color = '#e74c3c';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('حدث خطأ في الاتصال');
            });
        }

        // عرض إشعار
        function showNotification(message) {
            // إنشاء عنصر الإشعار
            const notification = document.createElement('div');
            notification.className = 'custom-notification';
            notification.innerHTML = `
                <div class="notification-content">
                    <span>${message}</span>
                </div>
            `;
            
            // إضافة الأنماط
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #27ae60;
                color: white;
                padding: 15px 20px;
                border-radius: 5px;
                z-index: 10000;
                animation: slideInRight 0.3s ease;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            `;
            
            document.body.appendChild(notification);
            
            // إزالة الإشعار بعد 3 ثواني
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
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
            
            .products-container.list-view .product-card {
                display: flex !important;
                flex-direction: row;
                gap: 20px;
            }
            
            .products-container.list-view .card-img {
                flex: 0 0 300px;
            }
            
            .products-container.list-view .card-body {
                flex: 1;
            }
        `;
        document.head.appendChild(style);

        // تهيئة الصفحة
        setupEventListeners();
        applyViewMode();
        showProducts();
    });
</script>
@endsection