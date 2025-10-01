@extends('frontend.home.layouts.master')

@section('title', 'جميع المنتجات')
@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('static/css/home.css') }}" />
    <link rel="stylesheet" href="{{ asset('static/css/prodect.css') }}" />

@endsection

@section('content')
    <!-- قسم الفلترة السريعة -->
    <section class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="filter-tabs">
                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'all']) }}"
                            class="filter-tab {{ !request('filter') || request('filter') == 'all' ? 'active' : '' }}">
                            الكل
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'new']) }}"
                            class="filter-tab {{ request('filter') == 'new' ? 'active' : '' }}">
                            جديد
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'sale']) }}"
                            class="filter-tab {{ request('filter') == 'sale' ? 'active' : '' }}">
                            عروض
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'bestseller']) }}"
                            class="filter-tab {{ request('filter') == 'bestseller' ? 'active' : '' }}">
                            الأكثر مبيعاً
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- قسم المنتجات -->
    <section class="featured-products-area section-padding">
        <div class="container">
            <form action="{{ route('front.products.index') }}" method="GET" class="row align-items-center">
                <div class="col-lg-6 pe-0">
                    <div class="input-box">
                        <label class="label-text">ماذا تبحث عنه؟</label>
                        <div class="form-group">
                            <span class="la la-search form-icon"></span>
                            <input class="form-control" type="text" name="search"
                                placeholder="ابحث عن المنتجات والعلامات التجارية..." value="{{ request('search') }}" />
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <button type="submit" class="theme-btn w-100 text-center margin-top-20px">بحث</button>
                </div>
            </form>
            <div class="row">
                <!-- الشريط الجانبي للفلترة -->
                <div class="col-lg-3">
                    <div class="filter-sidebar">
                        <!-- فلترة بالفئة -->
                        <div class="filter-group">
                            <h4>الفئات</h4>
                            <ul class="filter-options">
                                <li>
                                    <a href="{{ request()->fullUrlWithQuery(['category_id' => 0]) }}"
                                        class="{{ !request('category_id') || request('category_id') == 0 ? 'active' : '' }}">
                                        جميع الفئات
                                    </a>
                                </li>
                                @foreach ($categories as $category)
                                    <li>
                                        <a href="{{ request()->fullUrlWithQuery(['category_id' => $category->category_id]) }}"
                                            class="{{ request('category_id') == $category->category_id ? 'active' : '' }}">
                                            {{ $category->name }}
                                            <span
                                                class="badge bg-secondary float-left">{{ $category->products_count ?? 0 }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- فلترة بالمتجر -->
                        <div class="filter-group">
                            <h4>المتاجر</h4>
                            <div class="store-filter">
                                <a href="{{ request()->fullUrlWithQuery(['store_id' => 0]) }}"
                                    class="filter-options {{ !request('store_id') || request('store_id') == 0 ? 'active' : '' }}">
                                    جميع المتاجر
                                </a>
                            </div>
                            @foreach ($stores as $store)
                                <div class="store-item">
                                    <a href="{{ request()->fullUrlWithQuery(['store_id' => $store->store_id]) }}"
                                        class="store-info {{ request('store_id') == $store->store_id ? 'active' : '' }}">
                                        @if ($store->logo)
                                            <img src="{{ asset('static/images/stors/' . $store->logo) }}"
                                                alt="{{ $store->store_name }}" class="store-logo">
                                        @else
                                            <div
                                                class="store-logo bg-secondary text-white d-flex align-items-center justify-content-center">
                                                <i class="fas fa-store"></i>
                                            </div>
                                        @endif
                                        <span class="store-name">{{ $store->store_name }}</span>
                                    </a>
                                    <span class="product-count">{{ $store->products_count }}</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- فلترة بالسعر -->
                        <div class="filter-group">
                            <h4>نطاق السعر</h4>
                            <div class="price-filter">
                                <input type="range" class="form-range" id="priceRange" min="0" max="10000"
                                    step="100" value="{{ request('max_price', 10000) }}"
                                    onchange="filterByPrice(this.value)">
                                <div class="price-values d-flex justify-content-between mt-2">
                                    <span id="minPrice">0 ريال</span>
                                    <span id="maxPrice">{{ number_format(request('max_price', 10000)) }} ريال</span>
                                </div>
                            </div>
                        </div>

                        <!-- فلترة بالتقييم -->
                        <div class="filter-group">
                            <h4>التقييم</h4>
                            <ul class="filter-options">
                                <li>
                                    <a href="{{ request()->fullUrlWithQuery(['min_rating' => 5]) }}"
                                        class="{{ request('min_rating') == 5 ? 'active' : '' }}">
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        فأكثر
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ request()->fullUrlWithQuery(['min_rating' => 4]) }}"
                                        class="{{ request('min_rating') == 4 ? 'active' : '' }}">
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="far fa-star text-warning"></i>
                                        فأكثر
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ request()->fullUrlWithQuery(['min_rating' => 3]) }}"
                                        class="{{ request('min_rating') == 3 ? 'active' : '' }}">
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="far fa-star text-warning"></i>
                                        <i class="far fa-star text-warning"></i>
                                        فأكثر
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- قائمة المنتجات -->
                <div class="col-lg-9">
                    <!-- رأس الصفحة -->
                    <div class="products-header">
                        <div class="products-count">
                            عرض {{ $products->firstItem() }} - {{ $products->lastItem() }} من أصل
                            {{ $products->total() }} منتج

                            @if (request('store_id'))
                                @php
                                    $selectedStore = $stores->firstWhere('store_id', request('store_id'));
                                @endphp
                                @if ($selectedStore)
                                    <span class="store-badge">المتجر: {{ $selectedStore->name }}</span>
                                @endif
                            @endif

                            @if (request('category_id'))
                                @php
                                    $selectedCategory = $categories->firstWhere('category_id', request('category_id'));
                                @endphp
                                @if ($selectedCategory)
                                    <span class="store-badge">الفئة: {{ $selectedCategory->name }}</span>
                                @endif
                            @endif
                        </div>

                        <div class="sort-options">
                            <label for="sortSelect">ترتيب حسب:</label>
                            <select class="sort-select" id="sortSelect" onchange="window.location.href = this.value">
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}"
                                    {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>الأحدث</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}"
                                    {{ request('sort') == 'price_low' ? 'selected' : '' }}>السعر: من الأقل للأعلى</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}"
                                    {{ request('sort') == 'price_high' ? 'selected' : '' }}>السعر: من الأعلى للأقل</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'rating']) }}"
                                    {{ request('sort') == 'rating' ? 'selected' : '' }}>الأعلى تقييماً</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'name']) }}"
                                    {{ request('sort') == 'name' ? 'selected' : '' }}>الاسم: أ-ي</option>
                            </select>

                            <div class="view-options">
                                <div class="view-btn active" data-view="grid" title="عرض شبكة">
                                    <i class="fas fa-th"></i>
                                </div>
                                <div class="view-btn" data-view="list" title="عرض قائمة">
                                    <i class="fas fa-list"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- المنتجات -->
                    <div class="row products-container grid-view">
                        @forelse ($products as $product)
                            <div class="col-lg-4 col-md-6 product-item" data-product-id="{{ $product->product_id }}"
                                data-is-new="{{ $product->created_at->diffInDays(now()) < 30 ? 'true' : 'false' }}"
                                data-on-sale="{{ $product->compare_price ? 'true' : 'false' }}"
                                data-rating="{{ $product->reviews->avg('rating') ?? 0 }}"
                                data-price="{{ $product->price }}" data-category="{{ $product->category->name }}"
                                data-store="{{ $product->store->name }}" data-description="{{ $product->description }}">

                                <div class="card-item product-card">
                                    <div class="card-img">
                                        <a href="{{ route('front.products.show', $product->product_id) }}" class="d-block">
                                            <img src="{{ $product->images->first() ? asset($product->images->first()->image_path) : 'https://via.placeholder.com/500x300' }}"
                                                alt="{{ $product->title }}" />
                                        </a>
                                        @if ($product->compare_price)
                                            @php
                                                $discountPercent = round(
                                                    (($product->compare_price - $product->price) /
                                                        $product->compare_price) *
                                                        100,
                                                );
                                            @endphp
                                            <span class="badge">-{{ $discountPercent }}%</span>
                                        @elseif($product->created_at->diffInDays(now()) < 30)
                                            <span class="badge new">جديد</span>
                                        @endif

                                        <div class="card-actions">
                                            <button class="action-btn wishlist-btn"
                                                data-product-id="{{ $product->product_id }}" title="إضافة إلى المفضلة"
                                                data-in-wishlist="{{ in_array($product->product_id, $wishlistProductIds) ? 'true' : 'false' }}">
                                                <i
                                                    class="{{ in_array($product->product_id, $wishlistProductIds) ? 'fas' : 'far' }} fa-heart"></i>
                                            </button>
                                            <button class="action-btn compare-btn" title="مقارنة المنتج">
                                                <i class="fas fa-exchange-alt"></i>
                                            </button>
                                        </div>

                                        <div class="quick-view">
                                            <button class="quick-view-btn"
                                                data-product-id="{{ $product->product_id }}">معاينة سريعة</button>
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
                                                <span class="price__num">{{ number_format($product->price, 2) }}
                                                    ريال</span>
                                                @if ($product->compare_price)
                                                    <span
                                                        class="before-price">{{ number_format($product->compare_price, 2) }}
                                                        ريال</span>
                                                @endif
                                            </div>
                                            <span
                                                class="stock-status {{ $product->stock > 0 ? 'in-stock' : 'out-stock' }}">
                                                {{ $product->stock > 0 ? 'متوفر' : 'غير متوفر' }}
                                            </span>
                                        </div>

                                        <div class="card-action">
                                            <!-- زر إضافة إلى السلة باستخدام AJAX -->
                                            <button class="btn-text add-to-cart-btn"
                                                data-product-id="{{ $product->product_id }}"
                                                data-store-id="{{ $product->store_id }}"
                                                data-product-title="{{ $product->title }}"
                                                data-quantity="1">
                                                <i class="fas fa-shopping-cart"></i> أضف إلى السلة
                                            </button>

                                            <!-- زر شراء الآن باستخدام AJAX -->
                                            <button class="theme-btn buy-now-btn"
                                                data-product-id="{{ $product->product_id }}"
                                                data-store-id="{{ $product->store_id }}"
                                                data-product-title="{{ $product->title }}"
                                                data-quantity="1">
                                                شراء الآن
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="no-products">
                                    <i class="fas fa-search"></i>
                                    <h3>لا توجد منتجات</h3>
                                    <p>لم نتمكن من العثور على أي منتجات تطابق معايير البحث الخاصة بك.</p>
                                    <a href="{{ route('front.products.index') }}" class="theme-btn">عرض جميع المنتجات</a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- التقسيم (Pagination) -->
                    @if ($products->hasPages())
                        <div class="pagination-container">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    @endif
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
                            <input type="number" class="quantity-input" id="modalQuantity" value="1" min="1" max="10">
                            <button class="quantity-btn plus">+</button>
                        </div>
                        <!-- زر إضافة إلى السلة في المعاينة السريعة -->
                        <button class="btn-text" id="modalAddToCart">
                            <i class="fas fa-shopping-cart"></i> أضف إلى السلة
                        </button>
                        <!-- زر شراء الآن في المعاينة السريعة -->
                        <button class="theme-btn" id="modalBuyNow">
                            شراء الآن
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // عناصر DOM
            const productsContainer = document.querySelector('.products-container');
            const viewButtons = document.querySelectorAll('.view-btn');
            const quickViewModal = document.getElementById('quickViewModal');
            const closeModalButton = document.querySelector('.close-modal');
            let currentProductId = null;
            let currentStoreId = null;
            let currentQuantity = 1;

            let currentView = 'grid';

            // تطبيق نمط العرض (grid/list)
            function applyViewMode() {
                const products = document.querySelectorAll('.product-item');

                products.forEach(product => {
                    if (currentView === 'grid') {
                        product.className = 'col-lg-4 col-md-6 product-item';
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

            // أزرار طريقة العرض
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    viewButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    currentView = this.getAttribute('data-view');

                    if (currentView === 'grid') {
                        productsContainer.className = 'row products-container grid-view';
                    } else {
                        productsContainer.className = 'row products-container list-view';
                    }

                    applyViewMode();
                });
            });

            // إغلاق النافذة المنبثقة
            closeModalButton.addEventListener('click', closeModal);
            quickViewModal.addEventListener('click', function(e) {
                if (e.target === quickViewModal) {
                    closeModal();
                }
            });

            // معاينة سريعة
            document.addEventListener('click', function(e) {
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
                const productDescription = productElement.getAttribute('data-description') ||
                    'وصف المنتج غير متوفر';
                const stockStatus = productElement.querySelector('.stock-status').textContent;
                const ratingStars = productElement.querySelector('.rating-stars').innerHTML;
                const ratingCount = productElement.querySelector('.rating-count').textContent;

                // حفظ معرف المنتج والمتجر للنافذة المنبثقة
                currentProductId = productId;
                currentStoreId = productElement.querySelector('.add-to-cart-btn').getAttribute('data-store-id');

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
                document.getElementById('modalStockStatus').className =
                    `stock-status ${stockStatus === 'متوفر' ? 'in-stock' : 'out-stock'}`;
                document.getElementById('modalRatingStars').innerHTML = ratingStars;
                document.getElementById('modalRatingCount').textContent = ratingCount;

                // إعادة تعيين الكمية
                document.getElementById('modalQuantity').value = 1;
                currentQuantity = 1;

                quickViewModal.style.display = 'flex';
            }

            // إغلاق نافذة المعاينة السريعة
            function closeModal() {
                quickViewModal.style.display = 'none';
                currentProductId = null;
                currentStoreId = null;
                currentQuantity = 1;
            }

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

            // أحداث النافذة المنبثقة
            document.getElementById('modalAddToCart').addEventListener('click', function() {
                if (currentProductId && currentStoreId) {
                    const productTitle = document.getElementById('modalProductTitle').textContent;
                    addToCart(currentProductId, currentStoreId, currentQuantity, null, productTitle, this);
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

            // تبديل حالة المفضلة
            function toggleWishlist(productId) {
                const wishlistBtn = document.querySelector(`.wishlist-btn[data-product-id="${productId}"]`);
                const icon = wishlistBtn.querySelector('i');
                const isInWishlist = wishlistBtn.getAttribute('data-in-wishlist') === 'true';

                if (!isInWishlist) {
                    // إضافة إلى المفضلة
                    addToWishlist(productId, wishlistBtn, icon);
                } else {
                    // إزالة من المفضلة
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
                            // تحديث الواجهة
                            icon.classList.remove('far');
                            icon.classList.add('fas');
                            wishlistBtn.style.color = '#e74c3c';
                            wishlistBtn.setAttribute('data-in-wishlist', 'true');
                            wishlistBtn.title = 'إزالة من المفضلة';

                            // إضافة أنيميشن
                            wishlistBtn.classList.add('added');
                            setTimeout(() => {
                                wishlistBtn.classList.remove('added');
                            }, 500);

                            showNotification(data.message, 'success');
                        } else {
                            if (data.login_required) {
                                // عرض نموذج تسجيل الدخول
                                showLoginModal();
                            } else {
                                showNotification(data.message, 'error');
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
                            // تحديث الواجهة
                            icon.classList.remove('fas');
                            icon.classList.add('far');
                            wishlistBtn.style.color = '';
                            wishlistBtn.setAttribute('data-in-wishlist', 'false');
                            wishlistBtn.title = 'إضافة إلى المفضلة';

                            // إضافة أنيميشن
                            wishlistBtn.classList.add('added');
                            setTimeout(() => {
                                wishlistBtn.classList.remove('added');
                            }, 500);

                            showNotification(data.message, 'success');
                        } else {
                            showNotification(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('حدث خطأ في الاتصال', 'error');
                    });
            }

            // دالة إضافة إلى السلة باستخدام AJAX
            function addToCart(productId, storeId, quantity = 1, variantId = null, productTitle = '', button = null) {
                fetch('{{ route("front.cart.add") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            store_id: storeId,
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
                fetch('{{ route("front.cart.add") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            store_id: storeId,
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
                                window.location.href = '{{ route("front.cart.index") }}';
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

            // عرض نموذج تسجيل الدخول
            function showLoginModal() {
                if (confirm('يجب تسجيل الدخول أولاً. هل تريد الانتقال إلى صفحة تسجيل الدخول؟')) {
                    window.location.href = '{{ route("login") }}';
                }
            }

            // فلترة بالسعر (AJAX)
            function filterByPrice(price) {
                const url = new URL(window.location.href);
                url.searchParams.set('max_price', price);
                window.location.href = url.toString();
            }

            // فلترة بالتقييم (AJAX)
            function filterByRating(rating) {
                const url = new URL(window.location.href);
                url.searchParams.set('min_rating', rating);
                window.location.href = url.toString();
            }

            // تحميل عدد عناصر السلة عند فتح الصفحة
            function loadCartCount() {
                fetch('{{ route("front.cart.summary") }}')
                    .then(response => response.json())
                    .then(data => {
                        updateCartCount(data.cart_count, data.stores_count);
                    })
                    .catch(error => {
                        console.error('Error loading cart count:', error);
                    });
            }

            // تهيئة الصفحة
            applyViewMode();

            // تحميل حالة المفضلة وعداد السلة إذا كان المستخدم مسجلاً دخوله
            @if (Auth::check())
                loadWishlistStatus();
                loadCartCount();
            @endif
        });
    </script>
@endsection