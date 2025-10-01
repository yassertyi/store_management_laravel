<div class="col-lg-4 col-md-6">
    <div class="card-item product-card">
        <div class="card-img">
            <a href="{{ route('front.products.show', $product->product_id) }}" class="d-block">
                <img src="{{ $product->images->first() ? asset($product->images->first()->image_path) : '/images/placeholder.jpg' }}"
                    alt="{{ $product->title }}" class="product-image" />
            </a>

            @if ($product->compare_price && $product->compare_price > $product->price)
                <span class="badge discount-badge">
                    -{{ round((($product->compare_price - $product->price) / $product->compare_price) * 100) }}%
                </span>
            @elseif($product->created_at->gt(now()->subDays(7)))
                <span class="badge new-badge">جديد</span>
            @elseif($product->is_featured)
                <span class="badge featured-badge">متميز</span>
            @endif

            <button class="action-btn wishlist-btn" 
                    data-product-id="{{ $product->product_id }}" 
                    title="إضافة إلى المفضلة"
                    data-in-wishlist="{{ $product->isInWishlist ?? 'false' }}">
                <i class="la la-heart{{ $product->isInWishlist ? '' : '-o' }}"></i>
            </button>
        </div>

        <div class="card-body">
            <h3 class="card-title">
                <a href="{{ route('front.products.show', $product->product_id) }}">{{ Str::limit($product->title, 40) }}</a>
            </h3>
            <p class="card-meta">
                {{ $product->category->name ?? 'غير مصنف' }}
            </p>
            <p class="card-meta">
                <small class="text-muted">متجر: {{ $product->store->store_name ?? 'غير محدد' }}</small>
            </p>

            <div class="card-rating">
                <span class="badge text-white rating-badge">
                    {{ number_format($product->reviews->avg('rating') ?? 0, 1) }}/5
                </span>
                <span class="review__text">({{ $product->reviews->count() }} تقييم)</span>
            </div>

            <div class="card-price d-flex align-items-center justify-content-between">
                <p class="mb-0">
                    <span class="price__from">السعر</span>
                    <span class="price__num">{{ number_format($product->price, 2) }} ر.س</span>
                    @if ($product->compare_price && $product->compare_price > $product->price)
                        <span class="price__num before-price">
                            {{ number_format($product->compare_price, 2) }} ر.س
                        </span>
                    @endif
                </p>
                
                <!-- زر إضافة إلى السلة -->
                <button class="btn-text add-to-cart-btn"
                    data-product-id="{{ $product->product_id }}"
                    data-store-id="{{ $product->store_id }}"
                    data-product-title="{{ $product->title }}"
                    data-quantity="1">
                    <i class="la la-shopping-cart"></i>
                </button>
            </div>
        </div>
    </div>
</div>