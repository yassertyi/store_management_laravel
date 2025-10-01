<!-- Featured Products Section -->
<section class="featured-products-area section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading text-center">
                    <h2 class="sec__title line-height-55">منتجات مميزة</h2>
                </div>
            </div>
        </div>

        <div class="row g-4 padding-top-50px">
            @foreach ($featuredProducts as $product)
                <div class="col-lg-3 col-md-6">
                    <div class="card-item product-card">
                        <div class="card-img">
                            <a href="{{ route('front.products.show', $product->product_id) }}" class="d-block">
                                <img src="{{ $product->images->first() ? asset($product->images->first()->image_path) : 'https://via.placeholder.com/500x300' }}"
                                    alt="{{ $product->title }}" />
                            </a>

                            @if ($product->compare_price && $product->compare_price > $product->price)
                                <span class="badge">
                                    -{{ round((($product->compare_price - $product->price) / $product->compare_price) * 100) }}%
                                </span>
                            @elseif($product->created_at->gt(now()->subDays(7)))
                                <span class="badge">جديد</span>
                            @endif

                            <button class="action-btn wishlist-btn" 
                                    data-product-id="{{ $product->product_id }}" 
                                    title="إضافة إلى المفضلة"
                                    data-in-wishlist="">
                                <i class=" fa-heart"></i>
                            </button>
                        </div>

                        <div class="card-body">
                            <h3 class="card-title">
                                <a href="">{{ $product->title }}</a>
                            </h3>
                            <p class="card-meta">
                                {{ $product->category->name ?? 'غير مصنف' }}
                            </p>
                            <p class="card-meta">
                                <small class="text-muted">متجر: {{ $product->store->store_name ?? 'غير محدد' }}</small>
                            </p>

                            <div class="card-rating">
                                <span class="badge text-white">
                                    {{ number_format($product->reviews->avg('rating') ?? 0, 1) }}/5
                                </span>
                                <span class="review__text">({{ $product->reviews->count() }} تقييم)</span>
                            </div>

                            <div class="card-price d-flex align-items-center justify-content-between">
                                <p>
                                    <span class="price__from">السعر</span>
                                    <span class="price__num">{{ number_format($product->price, 2) }} ريال</span>
                                    @if ($product->compare_price && $product->compare_price > $product->price)
                                        <span class="price__num before-price">
                                            {{ number_format($product->compare_price, 2) }} ريال
                                        </span>
                                    @endif
                                </p>
                                
                                <!-- زر إضافة إلى السلة باستخدام AJAX -->
                                <button class="btn-text add-to-cart-btn"
                                    data-product-id="{{ $product->product_id }}"
                                    data-store-id="{{ $product->store_id }}"
                                    data-product-title="{{ $product->title }}"
                                    data-quantity="1">
                                    أضف إلى السلة <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>