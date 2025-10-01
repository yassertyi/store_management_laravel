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
            @foreach ($allProducts as $product)
                <div class="col-lg-3 col-md-6 product-item" data-product-id="{{ $product->product_id }}"
                    data-store-id="{{ $product->store_id }}"
                    data-is-new="{{ $product->created_at->diffInDays(now()) < 30 ? 'true' : 'false' }}"
                    data-on-sale="{{ $product->compare_price ? 'true' : 'false' }}"
                    data-rating="{{ $product->reviews->avg('rating') ?? 0 }}"
                    data-category="{{ $product->category->name }}" data-description="{{ $product->description }}">
                    <div class="card-item product-card">
                        <div class="card-img">
                            <a href="{{ route('front.products.show', $product->product_id) }}" class="d-block">
                                <img src="{{ $product->images->first() ? asset($product->images->first()->image_path) : 'https://via.placeholder.com/500x300' }}"
                                    alt="{{ $product->title }}" />
                            </a>
                            @if ($product->compare_price)
                                @php
                                    $discountPercent = round(
                                        (($product->compare_price - $product->price) / $product->compare_price) * 100,
                                    );
                                @endphp
                                <span class="badge">-{{ $discountPercent }}%</span>
                            @elseif($product->created_at->diffInDays(now()) < 30)
                                <span class="badge new">جديد</span>
                            @endif
                            <div class="card-actions">
                                <button class="action-btn wishlist-btn" data-product-id="{{ $product->product_id }}"
                                    title="إضافة إلى المفضلة" data-in-wishlist="">
                                    <i class="fa-heart"></i>
                                </button>
                                <button class="action-btn compare-btn" title="مقارنة المنتج">
                                    <i class="fas fa-exchange-alt"></i>
                                </button>
                            </div>
                            <div class="quick-view">
                                <button class="quick-view-btn" data-product-id="{{ $product->product_id }}">معاينة
                                    سريعة</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">
                                <a href="#">{{ $product->title }}</a>
                            </h3>
                            <div class="card-meta">
                                <i class="fas fa-tag"></i> {{ $product->category->name }}
                                <br>
                                <i class="fas fa-store"></i> {{ $product->store->name }}
                            </div>
                            <div class="card-rating">
                                <div class="rating-stars">
                                    @php
                                        $rating = $product->reviews->avg('rating') ?? 0;
                                        $fullStars = floor($rating);
                                        $hasHalfStar = $rating - $fullStars >= 0.5;
                                    @endphp
                                    @for ($i = 0; $i < $fullStars; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                    @if ($hasHalfStar)
                                        <i class="fas fa-star-half-alt"></i>
                                    @endif
                                    @for ($i = 0; $i < 5 - $fullStars - ($hasHalfStar ? 1 : 0); $i++)
                                        <i class="far fa-star"></i>
                                    @endfor
                                </div>
                                <span class="rating-count">({{ $product->reviews->count() }} تقييم)</span>
                            </div>
                            <div class="card-price">
                                <div class="price-container">
                                    <span class="price__num">{{ number_format($product->price, 2) }} ريال</span>
                                    @if ($product->compare_price)
                                        <span class="before-price">{{ number_format($product->compare_price, 2) }}
                                            ريال</span>
                                    @endif
                                </div>
                                <span class="stock-status {{ $product->stock > 0 ? 'in-stock' : 'out-stock' }}">
                                    {{ $product->stock > 0 ? 'متوفر' : 'غير متوفر' }}
                                </span>
                            </div>
                            <div class="card-action">
                                <!-- زر إضافة إلى السلة باستخدام AJAX -->
                                <button class="btn-text add-to-cart-btn" data-product-id="{{ $product->product_id }}"
                                    data-store-id="{{ $product->store_id }}"
                                    data-product-title="{{ $product->title }}" data-quantity="1">
                                    <i class="fas fa-shopping-cart"></i> أضف إلى السلة
                                </button>

                                <!-- زر شراء الآن باستخدام AJAX -->
                                <button class="theme-btn buy-now-btn" data-product-id="{{ $product->product_id }}"
                                    data-store-id="{{ $product->store_id }}"
                                    data-product-title="{{ $product->title }}" data-quantity="1"
                                    onclick="buyNow({{ $product->product_id }}, {{ $product->store_id }}, 1, null, '{{ $product->title }}', this)">
                                    شراء الآن
                                </button>
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
                <a href="{{ route('front.products.index') }}" class="theme-btn theme-btn-secondary"
                    style="margin-right: 10px;">
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
                        <input type="number" class="quantity-input" id="modalQuantity" value="1"
                            min="1" max="10">
                        <button class="quantity-btn plus">+</button>
                    </div>
                    <!-- زر إضافة إلى السلة في المعاينة السريعة -->
                    <button class="btn-text" id="modalAddToCart">
                        <i class="fas fa-shopping-cart"></i> أضف إلى السلة
                    </button>
                    <div class="section-block"></div>
                    <!-- زر شراء الآن في المعاينة السريعة -->
                    <button class="theme-btn buy-now-btn" data-product-id="{{ $product->product_id }}"
                        data-store-id="{{ $product->store_id }}" data-product-title="{{ $product->title }}"
                        data-quantity="1"
                        onclick="buyNow({{ $product->product_id }}, {{ $product->store_id }}, 1, null, '{{ $product->title }}', this)">
                        شراء الآن
                    </button>
                    

                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script>
        // دالة الشراء المباشر باستخدام AJAX - محدثة
        function buyNow(productId, storeId, quantity = 1, variantId = null, productTitle = '', button = null) {
            // أولاً نضيف المنتج إلى السلة
            fetch('{{ route('front.cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity,
                        variant_id: variantId
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('خطأ في الشبكة: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // تحديث عداد السلة في الهيدر
                        updateCartCount(data.cart_count, data.stores_count);

                        // إضافة أنيميشن للزر
                        if (button) {
                            button.classList.add('added');
                            setTimeout(() => {
                                button.classList.remove('added');
                            }, 600);
                        }

                        // الانتقال إلى صفحة اتمام الشراء لهذا المتجر
                        setTimeout(() => {
                            window.location.href = `/checkout/store/${storeId}`;
                        }, 800);

                    } else {
                        if (data.login_required) {
                            showLoginModal();
                        } else {
                            showNotification(data.message, 'error');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('حدث خطأ في الاتصال بالخادم', 'error');
                });
        }
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
            let displayedProducts = 8;
            let currentProductId = null;
            let currentStoreId = null;
            let currentQuantity = 1;

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
                        productsContainer.className =
                            `row padding-top-50px products-container ${currentView}-view`;
                        applyViewMode();
                    });
                });

                // زر تحميل المزيد
                loadMoreButton.addEventListener('click', function() {
                    displayedProducts += 4;
                    showProducts();

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

                // أحداث النقر العامة
                document.addEventListener('click', function(e) {
                    // أزرار الكمية
                    if (e.target.classList.contains('quantity-btn')) {
                        handleQuantityButton(e.target);
                    }

                    // معاينة سريعة
                    if (e.target.classList.contains('quick-view-btn')) {
                        const productId = parseInt(e.target.getAttribute('data-product-id'));
                        openQuickView(productId);
                    }

                    // إضافة إلى المفضلة
                    if (e.target.closest('.wishlist-btn')) {
                        const productId = parseInt(e.target.closest('.wishlist-btn').getAttribute(
                            'data-product-id'));
                        toggleWishlist(productId);
                    }

                    // إضافة إلى السلة
                    if (e.target.closest('.add-to-cart-btn')) {
                        const button = e.target.closest('.add-to-cart-btn');
                        const productId = parseInt(button.getAttribute('data-product-id'));
                        const storeId = parseInt(button.getAttribute('data-store-id'));
                        const productTitle = button.getAttribute('data-product-title');
                        const quantity = parseInt(button.getAttribute('data-quantity'));
                        addToCart(productId, storeId, quantity, null, productTitle, button);
                    }

                    // شراء الآن
                    if (e.target.closest('.buy-now-btn')) {
                        const button = e.target.closest('.buy-now-btn');
                        const productId = parseInt(button.getAttribute('data-product-id'));
                        const storeId = parseInt(button.getAttribute('data-store-id'));
                        const productTitle = button.getAttribute('data-product-title');
                        const quantity = parseInt(button.getAttribute('data-quantity'));
                        buyNow(productId, storeId, quantity, null, productTitle, button);
                    }
                });

                // أحداث النافذة المنبثقة
                document.getElementById('modalAddToCart').addEventListener('click', function() {
                    if (currentProductId && currentStoreId) {
                        const productTitle = document.getElementById('modalProductTitle').textContent;
                        addToCart(currentProductId, currentStoreId, currentQuantity, null, productTitle,
                            this);
                        closeModal();
                    }
                });

                document.getElementById('modalBuyNow').addEventListener('click', function() {
                    if (currentProductId && currentStoreId) {
                        const productTitle = document.getElementById('modalProductTitle').textContent;
                        buyNow(currentProductId, currentStoreId, currentQuantity, null, productTitle, this);
                        closeModal();
                    }
                });

                // أزرار الكمية في النافذة المنبثقة
                document.querySelector('.modal .quantity-btn.minus').addEventListener('click', function() {
                    const input = document.getElementById('modalQuantity');
                    let value = parseInt(input.value);
                    if (value > 1) {
                        input.value = value - 1;
                        currentQuantity = value - 1;
                    }
                });

                document.querySelector('.modal .quantity-btn.plus').addEventListener('click', function() {
                    const input = document.getElementById('modalQuantity');
                    let value = parseInt(input.value);
                    if (value < 10) {
                        input.value = value + 1;
                        currentQuantity = value + 1;
                    }
                });
            }

            // معالجة أزرار الكمية
            function handleQuantityButton(button) {
                const input = button.parentElement.querySelector('.quantity-input');
                let value = parseInt(input.value);

                if (button.classList.contains('plus')) {
                    input.value = value + 1;
                } else if (button.classList.contains('minus') && value > 1) {
                    input.value = value - 1;
                }
            }

            // تصفية المنتجات
            function filterProducts() {
                const allProducts = document.querySelectorAll('.product-item');

                allProducts.forEach(product => {
                    product.style.display = 'none';

                    const isNew = product.getAttribute('data-is-new') === 'true';
                    const onSale = product.getAttribute('data-on-sale') === 'true';
                    const rating = parseFloat(product.getAttribute('data-rating'));

                    let shouldShow = false;

                    switch (currentFilter) {
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

                displayedProducts = 8;
                showProducts();
            }

            // تطبيق نمط العرض
            function applyViewMode() {
                const products = document.querySelectorAll('.product-item');

                products.forEach(product => {
                    if (currentView === 'grid') {
                        product.className = 'col-lg-3 col-md-6 product-item';
                    } else {
                        product.className = 'col-lg-12 product-item';

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

            // عرض المنتجات
            function showProducts() {
                const visibleProducts = getVisibleProducts();

                visibleProducts.forEach(product => {
                    product.style.display = 'none';
                });

                const productsToShow = visibleProducts.slice(0, displayedProducts);
                productsToShow.forEach(product => {
                    product.style.display = 'block';
                });

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

                const productImage = productElement.querySelector('.card-img img').src;
                const productTitle = productElement.querySelector('.card-title a').textContent;
                const productPrice = productElement.querySelector('.price__num').textContent;
                const oldPriceElement = productElement.querySelector('.before-price');
                const productOldPrice = oldPriceElement ? oldPriceElement.textContent : '';
                const productDescription = productElement.getAttribute('data-description') ||
                    'وصف المنتج غير متوفر';
                const stockStatus = productElement.querySelector('.stock-status').textContent;
                const ratingStars = productElement.querySelector('.rating-stars').innerHTML;
                const ratingCount = productElement.querySelector('.rating-count').textContent;

                currentProductId = productId;
                currentStoreId = productElement.getAttribute('data-store-id');
                currentQuantity = 1;

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
                document.getElementById('modalStockStatus').className =
                    `stock-status ${stockStatus === 'متوفر' ? 'in-stock' : 'out-stock'}`;
                document.getElementById('modalRatingStars').innerHTML = ratingStars;
                document.getElementById('modalRatingCount').textContent = ratingCount;

                document.getElementById('modalQuantity').value = 1;

                quickViewModal.style.display = 'flex';
            }

            // إغلاق النافذة المنبثقة
            function closeModal() {
                quickViewModal.style.display = 'none';
                currentProductId = null;
                currentStoreId = null;
                currentQuantity = 1;
            }

            // تبديل حالة المفضلة
            function toggleWishlist(productId) {
                const wishlistBtn = document.querySelector(`.wishlist-btn[data-product-id="${productId}"]`);
                const icon = wishlistBtn.querySelector('i');
                const isInWishlist = wishlistBtn.getAttribute('data-in-wishlist') === 'true';

                if (!isInWishlist) {
                    addToWishlist(productId, wishlistBtn, icon);
                } else {
                    removeFromWishlist(productId, wishlistBtn, icon);
                }
            }

            // إضافة إلى المفضلة (AJAX)
            function addToWishlist(productId, wishlistBtn, icon) {
                fetch('/wishlist/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            product_id: productId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            icon.classList.remove('far');
                            icon.classList.add('fas');
                            wishlistBtn.style.color = '#e74c3c';
                            wishlistBtn.setAttribute('data-in-wishlist', 'true');
                            wishlistBtn.title = 'إزالة من المفضلة';

                            wishlistBtn.classList.add('added');
                            setTimeout(() => {
                                wishlistBtn.classList.remove('added');
                            }, 500);

                            showNotification(data.message, 'success');
                        } else {
                            if (data.login_required) {
                                showLoginModal();
                            } else {
                                showNotification(data.message, 'error');
                                // التراجع عن التغيير البصري
                                icon.classList.remove('fas');
                                icon.classList.add('far');
                                wishlistBtn.style.color = '';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('حدث خطأ في الاتصال', 'error');
                    });
            }

            // إزالة من المفضلة (AJAX)
            function removeFromWishlist(productId, wishlistBtn, icon) {
                fetch('/wishlist/remove', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            product_id: productId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            icon.classList.remove('fas');
                            icon.classList.add('far');
                            wishlistBtn.style.color = '';
                            wishlistBtn.setAttribute('data-in-wishlist', 'false');
                            wishlistBtn.title = 'إضافة إلى المفضلة';

                            wishlistBtn.classList.add('added');
                            setTimeout(() => {
                                wishlistBtn.classList.remove('added');
                            }, 500);

                            showNotification(data.message, 'success');
                        } else {
                            showNotification(data.message, 'error');
                            // التراجع عن التغيير البصري
                            icon.classList.remove('far');
                            icon.classList.add('fas');
                            wishlistBtn.style.color = '#e74c3c';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('حدث خطأ في الاتصال', 'error');
                    });
            }

            // دالة إضافة إلى السلة باستخدام AJAX
            function addToCart(productId, storeId, quantity = 1, variantId = null, productTitle = '', button =
                null) {
                fetch('{{ route('front.cart.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: quantity,
                            variant_id: variantId
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('خطأ في الشبكة: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // تحديث عداد السلة في الهيدر
                            updateCartCount(data.cart_count, data.stores_count);

                            // عرض إشعار النجاح
                            showNotification(`تم إضافة "${productTitle}" إلى السلة`, 'success');

                            // إضافة أنيميشن للزر
                            if (button) {
                                button.classList.add('added');
                                setTimeout(() => {
                                    button.classList.remove('added');
                                }, 600);
                            }
                        } else {
                            if (data.login_required) {
                                showLoginModal();
                            } else {
                                showNotification(data.message, 'error');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('حدث خطأ في الاتصال بالخادم', 'error');
                    });
            }

            // دالة الشراء المباشر باستخدام AJAX
            function buyNow(productId, storeId, quantity = 1, variantId = null, productTitle = '', button = null) {
                // أولاً نضيف المنتج إلى السلة
                fetch('{{ route('front.cart.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: quantity,
                            variant_id: variantId
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('خطأ في الشبكة: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // تحديث عداد السلة في الهيدر
                            updateCartCount(data.cart_count, data.stores_count);

                            // إضافة أنيميشن للزر
                            if (button) {
                                button.classList.add('added');
                                setTimeout(() => {
                                    button.classList.remove('added');
                                }, 600);
                            }

                            // الانتقال إلى صفحة السلة بعد إضافة المنتج
                            setTimeout(() => {
                                window.location.href = '{{ route('front.cart.index') }}';
                            }, 800);
                        } else {
                            if (data.login_required) {
                                showLoginModal();
                            } else {
                                showNotification(data.message, 'error');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('حدث خطأ في الاتصال بالخادم', 'error');
                    });
            }

            // تحديث عداد السلة في الهيدر
            function updateCartCount(count, storesCount) {
                const cartCountElements = document.querySelectorAll('.cart-count');
                const storesCountElements = document.querySelectorAll('.stores-count');

                cartCountElements.forEach(element => {
                    element.textContent = count;
                    element.style.display = count > 0 ? 'flex' : 'none';
                });

                storesCountElements.forEach(element => {
                    element.textContent = storesCount;
                    element.style.display = storesCount > 0 ? 'flex' : 'none';
                });

                // تشغيل أنيميشن الأيقونة
                animateCartIcon();
            }

            // أنيميشن أيقونة السلة
            function animateCartIcon() {
                const navCart = document.querySelector('.nav-cart');
                if (navCart) {
                    navCart.classList.add('cart-add-animation');
                    setTimeout(() => {
                        navCart.classList.remove('cart-add-animation');
                    }, 600);
                }
            }

            // عرض نموذج تسجيل الدخول
            function showLoginModal() {
                if (confirm('يجب تسجيل الدخول أولاً. هل تريد الانتقال إلى صفحة تسجيل الدخول؟')) {
                    window.location.href = '{{ route('login') }}';
                }
            }

            // عرض إشعار
            function showNotification(message, type = 'success') {
                // إزالة أي إشعارات سابقة
                const existingNotifications = document.querySelectorAll('.custom-notification');
                existingNotifications.forEach(notification => notification.remove());

                const notification = document.createElement('div');
                const bgColor = type === 'success' ? '#27ae60' : '#e74c3c';
                const icon = type === 'success' ? '✓' : '⚠';

                notification.className = 'custom-notification';
                notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${bgColor};
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                z-index: 10000;
                animation: slideInRight 0.3s ease;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                display: flex;
                align-items: center;
                gap: 10px;
                max-width: 400px;
                font-family: 'Tajawal', sans-serif;
            `;

                notification.innerHTML = `
                <span style="font-weight: bold; font-size: 1.2em;">${icon}</span>
                <span>${message}</span>
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

            // تحميل حالة المفضلة عند فتح الصفحة
            function loadWishlistStatus() {
                const wishlistButtons = document.querySelectorAll('.wishlist-btn');

                wishlistButtons.forEach(btn => {
                    const productId = btn.getAttribute('data-product-id');

                    fetch(`/wishlist/check/${productId}`)
                        .then(response => response.json())
                        .then(data => {
                            const icon = btn.querySelector('i');
                            if (data.in_wishlist) {
                                icon.classList.remove('far');
                                icon.classList.add('fas');
                                btn.style.color = '#e74c3c';
                                btn.setAttribute('data-in-wishlist', 'true');
                                btn.title = 'إزالة من المفضلة';
                            } else {
                                icon.classList.remove('fas');
                                icon.classList.add('far');
                                btn.style.color = '';
                                btn.setAttribute('data-in-wishlist', 'false');
                                btn.title = 'إضافة إلى المفضلة';
                            }
                        })
                        .catch(error => {
                            console.error('Error checking wishlist status:', error);
                        });
                });
            }

            // تحميل عدد عناصر السلة عند فتح الصفحة
            function loadCartCount() {
                fetch('{{ route('front.cart.summary') }}')
                    .then(response => response.json())
                    .then(data => {
                        updateCartCount(data.cart_count, data.stores_count);
                    })
                    .catch(error => {
                        console.error('Error loading cart count:', error);
                    });
            }

            // إضافة أنيميشن
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
            
            .wishlist-btn.added {
                animation: heartBeat 0.5s;
            }
            
            .add-to-cart-btn.added {
                animation: cartBounce 0.6s ease;
                background-color: #27ae60 !important;
                color: white !important;
            }

            .buy-now-btn.added {
                animation: cartBounce 0.6s ease;
                background-color: #e74c3c !important;
            }
            
            @keyframes heartBeat {
                0% { transform: scale(1); }
                25% { transform: scale(1.2); }
                50% { transform: scale(1); }
                75% { transform: scale(1.1); }
                100% { transform: scale(1); }
            }
            
            @keyframes cartBounce {
                0% { transform: scale(1); }
                25% { transform: scale(1.1); }
                50% { transform: scale(0.95); }
                75% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }

            @keyframes bounce {
                0%, 20%, 60%, 100% {
                    transform: translateY(0);
                }
                40% {
                    transform: translateY(-5px);
                }
                80% {
                    transform: translateY(-2px);
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

            .cart-add-animation {
                animation: cartIconBounce 0.6s ease;
            }
            
            @keyframes cartIconBounce {
                0% { transform: scale(1); }
                25% { transform: scale(1.2); }
                50% { transform: scale(0.9); }
                75% { transform: scale(1.1); }
                100% { transform: scale(1); }
            }
        `;
            document.head.appendChild(style);

            // تهيئة الصفحة
            setupEventListeners();
            applyViewMode();
            showProducts();

            // تحميل حالة المفضلة وعداد السلة إذا كان المستخدم مسجلاً دخوله
            @if (Auth::check())
                loadWishlistStatus();
                loadCartCount();
            @endif
        });
    </script>
@endsection
