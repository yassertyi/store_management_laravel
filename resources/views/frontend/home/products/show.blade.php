@extends('frontend.home.layouts.master')

@section('title', $product->title . ' - متجرنا')
@section('meta_description', Str::limit($product->description, 160))

@section('content')
    <!-- ================================
            START BREADCRUMB AREA
        ================================= -->
    <section class="breadcrumb-area bread-bg-7 py-0">
        <div class="breadcrumb-wrap">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="breadcrumb-btn">
                            <div class="btn-box">
                                @php
                                    $mainImage =
                                        $product->images->where('is_primary', true)->first() ??
                                        $product->images->first();
                                    $otherImages = $product->images->where(
                                        'image_id',
                                        '!=',
                                        optional($mainImage)->image_id,
                                    );
                                @endphp

                                @if ($mainImage)
                                    <a class="theme-btn" data-fancybox="gallery" data-src="{{ asset($mainImage->image_path) }}"
                                        data-speed="700">
                                        <i class="la la-photo me-2"></i>المزيد من الصور
                                    </a>
                                @endif

                                @foreach ($otherImages as $image)
                                    <a class="d-none" data-fancybox="gallery" data-src="{{ asset($image->image_path) }}"
                                        data-speed="700"></a>
                                @endforeach
                            </div>
                        </div>
                        <!-- end breadcrumb-btn -->
                    </div>
                    <!-- end col-lg-12 -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end breadcrumb-wrap -->
    </section>
    <!-- end breadcrumb-area -->
    <!-- ================================
            END BREADCRUMB AREA
        ================================= -->

    <!-- ================================
            START PRODUCT DETAIL AREA
        ================================= -->
    <section class="product-detail-area padding-bottom-90px">
        <div class="single-content-navbar-wrap menu section-bg" id="single-content-navbar">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="single-content-nav" id="single-content-nav">
                            <ul>
                                <li>
                                    <a data-scroll="description" href="#description" class="scroll-link active">تفاصيل
                                        المنتج</a>
                                </li>
                                <li>
                                    <a data-scroll="specifications" href="#specifications" class="scroll-link">المواصفات</a>
                                </li>
                                <li>
                                    <a data-scroll="variants" href="#variants" class="scroll-link">المتغيرات</a>
                                </li>
                                <li>
                                    <a data-scroll="shipping" href="#shipping" class="scroll-link">الشحن والتوصيل</a>
                                </li>
                                <li>
                                    <a data-scroll="reviews" href="#reviews" class="scroll-link">التقييمات</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end single-content-navbar-wrap -->

        <div class="single-content-box">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="single-content-wrap padding-top-60px">
                            <div id="description" class="page-scroll">
                                <div class="single-content-item pb-4">
                                    <h3 class="title font-size-26">{{ $product->title }}</h3>
                                    <div class="d-flex align-items-center pt-2">
                                        <p class="me-2">
                                            {{ $product->store->store_name }} - {{ $product->category->name ?? '' }}
                                        </p>
                                        <p>
                                            <span
                                                class="badge text-bg-warning text-white font-size-16">{{ number_format($averageRating, 1) }}</span>
                                            <span>({{ $totalReviews }} تقييم)</span>
                                        </p>
                                    </div>
                                </div>
                                <!-- end single-content-item -->

                                <div class="section-block"></div>

                                <div class="single-content-item py-4">
                                    <div class="row">
                                        <div class="col-lg-4 responsive-column">
                                            <div class="single-tour-feature d-flex align-items-center mb-3">
                                                <div class="single-feature-icon icon-element ms-0 flex-shrink-0 me-3">
                                                    <i class="la la-tags"></i>
                                                </div>
                                                <div class="single-feature-titles">
                                                    <h3 class="title font-size-15 font-weight-medium">السعر</h3>
                                                    <span class="font-size-13">{{ number_format($product->price, 2) }}
                                                        ر.س</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 responsive-column">
                                            <div class="single-tour-feature d-flex align-items-center mb-3">
                                                <div class="single-feature-icon icon-element ms-0 flex-shrink-0 me-3">
                                                    <i class="la la-cube"></i>
                                                </div>
                                                <div class="single-feature-titles">
                                                    <h3 class="title font-size-15 font-weight-medium">الحالة</h3>
                                                    <span
                                                        class="font-size-13 {{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
                                                        {{ $product->stock > 0 ? 'متوفر' : 'غير متوفر' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 responsive-column">
                                            <div class="single-tour-feature d-flex align-items-center mb-3">
                                                <div class="single-feature-icon icon-element ms-0 flex-shrink-0 me-3">
                                                    <i class="la la-barcode"></i>
                                                </div>
                                                <div class="single-feature-titles">
                                                    <h3 class="title font-size-15 font-weight-medium">الرمز</h3>
                                                    <span class="font-size-13">{{ $product->sku }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="section-block"></div>

                                <div class="single-content-item padding-top-40px padding-bottom-40px">
                                    <h3 class="title font-size-20">حول {{ $product->title }}</h3>
                                    <p class="py-3">
                                        {{ $product->description }}
                                    </p>
                                </div>
                                <div class="section-block"></div>
                            </div>
                            <!-- end description -->

                            <div id="variants" class="page-scroll">
                                <div class="single-content-item padding-top-40px padding-bottom-30px">
                                    <h3 class="title font-size-20">المتغيرات المتاحة</h3>

                                    @if ($product->variants->count() > 0)
                                        <div class="variants-list padding-top-30px">
                                            @foreach ($product->variants as $variant)
                                                <div class="variant-item seat-selection-item d-flex mb-4">
                                                    <div class="variant-detail flex-grow-1">
                                                        <h3 class="title">{{ $variant->name }}</h3>
                                                        <div class="row padding-top-20px">
                                                            <div class="col-lg-6 responsive-column">
                                                                <div
                                                                    class="single-tour-feature d-flex align-items-center mb-3">
                                                                    <div
                                                                        class="single-feature-icon icon-element ms-0 flex-shrink-0 me-2">
                                                                        <i class="la la-money"></i>
                                                                    </div>
                                                                    <div class="single-feature-titles">
                                                                        <h3 class="title font-size-15 font-weight-medium">
                                                                            السعر</h3>
                                                                        <span
                                                                            class="font-size-13">{{ number_format($variant->price, 2) }}
                                                                            ر.س</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 responsive-column">
                                                                <div
                                                                    class="single-tour-feature d-flex align-items-center mb-3">
                                                                    <div
                                                                        class="single-feature-icon icon-element ms-0 flex-shrink-0 me-2">
                                                                        <i class="la la-cube"></i>
                                                                    </div>
                                                                    <div class="single-feature-titles">
                                                                        <h3 class="title font-size-15 font-weight-medium">
                                                                            المخزون</h3>
                                                                        <span
                                                                            class="font-size-13 {{ $variant->stock > 0 ? 'text-success' : 'text-danger' }}">
                                                                            {{ $variant->stock > 0 ? $variant->stock . ' قطعة' : 'غير متوفر' }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="variant-price">
                                                        <p class="text-uppercase font-size-14">
                                                            للسعر<strong
                                                                class="mt-n1 text-black font-size-18 font-weight-black d-block">{{ number_format($variant->price, 2) }}
                                                                ر.س</strong>
                                                        </p>
                                                        <div class="custom-checkbox mb-0">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="variant{{ $variant->variant_id }}"
                                                                onchange="selectVariant({{ $variant->variant_id }}, {{ $variant->price }})">
                                                            <label for="variant{{ $variant->variant_id }}"
                                                                class="theme-btn theme-btn-small">تحديد</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-info mt-3">
                                            لا توجد متغيرات متاحة لهذا المنتج
                                        </div>
                                    @endif
                                </div>
                                <div class="section-block"></div>
                            </div>
                            <!-- end variants -->

                            <div id="shipping" class="page-scroll">
                                <div class="single-content-item padding-top-40px padding-bottom-40px">
                                    <h3 class="title font-size-20">الشحن والتوصيل</h3>
                                    <div class="shipping-info pt-4">
                                        <div class="row">
                                            <div class="col-lg-6 responsive-column">
                                                <div class="single-tour-feature d-flex align-items-center mb-3">
                                                    <div class="single-feature-icon icon-element ms-0 flex-shrink-0 me-3">
                                                        <i class="la la-shipping-fast"></i>
                                                    </div>
                                                    <div class="single-feature-titles">
                                                        <h3 class="title font-size-15 font-weight-medium">مدة التوصيل</h3>
                                                        <span class="font-size-13">2-5 أيام عمل</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 responsive-column">
                                                <div class="single-tour-feature d-flex align-items-center mb-3">
                                                    <div class="single-feature-icon icon-element ms-0 flex-shrink-0 me-3">
                                                        <i class="la la-undo"></i>
                                                    </div>
                                                    <div class="single-feature-titles">
                                                        <h3 class="title font-size-15 font-weight-medium">سياسة الإرجاع
                                                        </h3>
                                                        <span class="font-size-13">30 يوم للإرجاع</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="section-block"></div>
                            </div>
                            <!-- end shipping -->

                            <div id="reviews" class="page-scroll">
                                <div class="single-content-item padding-top-40px padding-bottom-40px">
                                    <h3 class="title font-size-20">تقييمات العملاء</h3>
                                    <div class="review-container padding-top-30px">
                                        <div class="row align-items-center">
                                            <div class="col-lg-4">
                                                <div class="review-summary">
                                                    <h2>{{ number_format($averageRating, 1) }}<span>/5</span></h2>
                                                    <p>
                                                        @if ($averageRating >= 4.5)
                                                            ممتاز
                                                        @elseif($averageRating >= 3.5)
                                                            جيد جداً
                                                        @elseif($averageRating >= 2.5)
                                                            جيد
                                                        @elseif($averageRating >= 1.5)
                                                            مقبول
                                                        @else
                                                            ضعيف
                                                        @endif
                                                    </p>
                                                    <span>مرتكز على {{ $totalReviews }} تقييمات</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="review-bars">
                                                    <div class="row">
                                                        @for ($i = 5; $i >= 1; $i--)
                                                            <div class="col-lg-12">
                                                                <div class="progress-item">
                                                                    <div
                                                                        class="progressbar-content line-height-20 d-flex align-items-center justify-content-between">
                                                                        <div class="d-flex align-items-center">
                                                                            <span class="me-2">{{ $i }}
                                                                                نجوم</span>
                                                                            <div class="progressbar-box flex-shrink-0"
                                                                                style="width: 200px;">
                                                                                <div class="progressbar-line"
                                                                                    data-percent="{{ $totalReviews > 0 ? ($ratingDistribution[$i] / $totalReviews) * 100 : 0 }}">
                                                                                    <div
                                                                                        class="progressbar-line-item bar-bg-{{ 6 - $i }}">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="bar-percent">
                                                                            {{ $ratingDistribution[$i] }}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="section-block"></div>

                                <div class="review-box">
                                    <div class="single-content-item padding-top-40px">
                                        <h3 class="title font-size-20">أحدث التقييمات</h3>
                                        <div class="comments-list padding-top-50px">
                                            @if ($product->reviews->count() > 0)
                                                @foreach ($product->reviews->take(3) as $review)
                                                    <div class="comment">
                                                        <div class="comment-avatar">
                                                            <div class="avatar-circle bg-primary text-white">
                                                                {{ substr($review->user->name, 0, 1) }}
                                                            </div>
                                                        </div>
                                                        <div class="comment-body">
                                                            <div class="meta-data">
                                                                <h3 class="comment__author">{{ $review->user->name }}</h3>
                                                                <div class="meta-data-inner d-flex">
                                                                    <span class="ratings d-flex align-items-center me-1">
                                                                        @for ($i = 1; $i <= 5; $i++)
                                                                            <i
                                                                                class="la la-star {{ $i <= $review->rating ? 'text-warning' : 'text-light' }}"></i>
                                                                        @endfor
                                                                    </span>
                                                                    <p class="comment__date">
                                                                        {{ $review->created_at->diffForHumans() }}</p>
                                                                </div>
                                                            </div>
                                                            <h6 class="review-title mb-2">{{ $review->title }}</h6>
                                                            <p class="comment-content">{{ $review->comment }}</p>
                                                            <div
                                                                class="comment-reply d-flex align-items-center justify-content-between">
                                                                <div class="reviews-reaction">
                                                                    <a href="#" class="comment-like"
                                                                        onclick="toggleHelpful({{ $review->review_id }})">
                                                                        <i class="la la-thumbs-up"></i>
                                                                        <span
                                                                            id="helpfulCount{{ $review->review_id }}">{{ $review->helpfuls->count() }}</span>
                                                                        مفيد
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach

                                                @if ($product->reviews->count() > 3)
                                                    <div class="btn-box load-more text-center">
                                                        <button class="theme-btn theme-btn-small theme-btn-transparent"
                                                            type="button">
                                                            تحميل المزيد من التقييمات
                                                        </button>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="text-center py-5">
                                                    <div class="empty-reviews-icon mb-4">
                                                        <i class="la la-comments fa-3x text-muted opacity-25"></i>
                                                    </div>
                                                    <h5 class="text-muted mb-3">لا توجد تقييمات حتى الآن</h5>
                                                    <p class="text-muted mb-4">كن أول من يقيم هذا المنتج وشارك تجربتك مع
                                                        الآخرين</p>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- نموذج إضافة تقييم -->
                                        <div class="comment-forum padding-top-40px">
                                            <div class="form-box">
                                                <div class="form-title-wrap">
                                                    <h3 class="title">أكتب تقييمك</h3>
                                                </div>
                                                <div class="form-content">
                                                    @auth
                                                        <div class="contact-form-action">
                                                            <form id="reviewForm">
                                                                @csrf
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <div class="input-box">
                                                                            <label class="label-text">تقييمك</label>
                                                                            <div class="star-rating-input mb-3">
                                                                                @for ($i = 1; $i <= 5; $i++)
                                                                                    <input type="radio"
                                                                                        id="star{{ $i }}"
                                                                                        name="rating"
                                                                                        value="{{ $i }}"
                                                                                        {{ $i == 5 ? 'checked' : '' }}>
                                                                                    <label for="star{{ $i }}"
                                                                                        class="star-label">
                                                                                        <i class="la la-star"></i>
                                                                                    </label>
                                                                                @endfor
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12">
                                                                        <div class="input-box">
                                                                            <label class="label-text">عنوان التقييم</label>
                                                                            <div class="form-group">
                                                                                <span class="la la-pencil form-icon"></span>
                                                                                <input class="form-control" type="text"
                                                                                    name="title"
                                                                                    placeholder="اكتب عنواناً موجزاً لتقييمك"
                                                                                    required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12">
                                                                        <div class="input-box">
                                                                            <label class="label-text">التعليق</label>
                                                                            <div class="form-group">
                                                                                <span class="la la-pencil form-icon"></span>
                                                                                <textarea class="message-control form-control" name="comment" placeholder="شاركنا تجربتك مع هذا المنتج..." required
                                                                                    minlength="10" rows="4"></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12">
                                                                        <div class="btn-box">
                                                                            <button type="submit" class="theme-btn">نشر
                                                                                التقييم</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    @else
                                                        <div class="text-center py-4">
                                                            <p class="text-muted mb-3">يجب تسجيل الدخول لإضافة تقييم</p>
                                                            <a href="{{ route('login') }}" class="theme-btn">
                                                                <i class="la la-sign-in me-2"></i>تسجيل الدخول
                                                            </a>
                                                        </div>
                                                    @endauth
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end reviews -->
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="sidebar single-content-sidebar mb-0">
                            <div class="sidebar-widget single-content-widget">
                                <div class="sidebar-widget-item">
                                    <div class="sidebar-book-title-wrap mb-3">
                                        <h3>السعر</h3>
                                        <p>
                                            <span class="text-form">السعر</span>
                                            <span class="text-value ms-2 me-1"
                                                id="currentPrice">{{ number_format($product->price, 2) }} ر.س</span>
                                            @if ($product->compare_price)
                                                <span class="before-price">{{ number_format($product->compare_price, 2) }}
                                                    ر.س</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="sidebar-widget-item">
                                    <div class="contact-form-action">
                                        <form id="orderForm">
                                            <div class="input-box">
                                                <label class="label-text">الكمية</label>
                                                <div class="form-group">
                                                    <div class="qty-box d-flex align-items-center">
                                                        <div class="qtyBtn d-flex align-items-center">
                                                            <div class="qtyDec" onclick="decreaseQuantity()"><i
                                                                    class="la la-minus"></i></div>
                                                            <input type="text" name="quantity" id="quantity"
                                                                value="1" readonly>
                                                            <div class="qtyInc" onclick="increaseQuantity()"><i
                                                                    class="la la-plus"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="btn-box pt-2">
                                    <button class="theme-btn text-center w-100 mb-2" onclick="addToCart()"
                                        id="addToCartBtn">
                                        <i class="la la-shopping-cart me-2 font-size-18"></i>
                                        <span id="addToCartText">أضف إلى السلة</span>
                                        <div class="spinner-border spinner-border-sm d-none ms-2" id="addToCartSpinner"
                                            role="status">
                                            <span class="visually-hidden">جاري التحميل...</span>
                                        </div>
                                    </button>
                                    <button class="theme-btn text-center w-100 theme-btn-transparent"
                                        onclick="toggleWishlist()" id="wishlistBtn">
                                        <i class="la la-heart-o me-2"></i>
                                        {{ $inWishlist ? 'مضاف للمفضلة' : 'أضف إلى المفضلة' }}
                                    </button>
                                    <div class="d-flex align-items-center justify-content-between pt-2">
                                        <button class="btn theme-btn-hover-gray font-size-15" onclick="shareProduct()">
                                            <i class="la la-share me-1"></i>شارك
                                        </button>
                                        <p>
                                            <i class="la la-eye me-1 font-size-15 color-text-2"></i>
                                            {{ rand(1000, 5000) }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="sidebar-widget single-content-widget">
                                <h3 class="title stroke-shape">معلومات المتجر</h3>
                                <div class="store-info">
                                    <div class="author-content d-flex">
                                        <div class="author-img">
                                            <img src="{{ asset('static/images/stors/' . $product->store->logo) ?? '/images/store-default.jpg' }}"
                                                alt="store logo">
                                        </div>
                                        <div class="author-bio">
                                            <h4 class="author__title">{{ $product->store->store_name }}</h4>
                                            <span class="author__meta">متجر معتمد</span>
                                            <span class="ratings d-flex align-items-center">
                                                <i class="la la-star"></i>
                                                <i class="la la-star"></i>
                                                <i class="la la-star"></i>
                                                <i class="la la-star"></i>
                                                <i class="la la-star-half-o"></i>
                                                <span class="ms-2">4.5</span>
                                            </span>
                                            <div class="btn-box pt-3">
    <a href="{{ route('front.stores.show', $product->store->store_id) }}" 
       class="theme-btn theme-btn-small theme-btn-transparent">
        <i class="la la-store me-1"></i>زيارة المتجر
    </a>
</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($relatedProducts->count() > 0)
        <section class="related-tour-area section--padding">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-heading text-center">
                            <h2 class="sec__title">منتجات مشابهة</h2>
                        </div>
                    </div>
                </div>
                <div class="row padding-top-50px">
                    @foreach ($relatedProducts as $relatedProduct)
                        <div class="col-lg-4 responsive-column">
                            <div class="card-item">
                                <div class="card-img">
                                    <a href="{{ route('front.products.show', $relatedProduct->product_id) }}"
                                        class="d-block">
                                        <img src="{{ $relatedProduct->images->first() ? asset($relatedProduct->images->first()->image_path) : '/images/placeholder.jpg' }}" alt="product-img">

                                    </a>
                                    @if ($relatedProduct->is_featured)
                                        <span class="badge">متميز</span>
                                    @endif
                                    <div class="add-to-wishlist icon-element" data-bs-toggle="tooltip"
                                        data-placement="top" title="إضافة إلى المفضلة">
                                        <i class="la la-heart-o"></i>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h3 class="card-title">
                                        <a
                                            href="{{ route('front.products.show', $relatedProduct->product_id) }}">{{ Str::limit($relatedProduct->title, 40) }}</a>
                                    </h3>
                                    <p class="card-meta">{{ $relatedProduct->store->store_name }}</p>
                                    <div class="card-rating">
                                        <span class="badge text-white">4.4/5</span>
                                        <span class="review__text">تقييم</span>
                                        <span class="rating__text">({{ $relatedProduct->reviews->count() }} تقييم)</span>
                                    </div>
                                    <div class="card-price d-flex align-items-center justify-content-between">
                                        <p>
                                            <span class="price__from">من</span>
                                            <span class="price__num">{{ number_format($relatedProduct->price, 2) }}
                                                ر.س</span>
                                            @if ($relatedProduct->compare_price)
                                                <span
                                                    class="before-price">{{ number_format($relatedProduct->compare_price, 2) }}
                                                    ر.ي</span>
                                            @endif
                                        </p>
                                        <a href="{{ route('front.products.show', $relatedProduct->product_id) }}"
                                            class="btn-text">
                                            انظر التفاصيل<i class="la la-angle-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection

@section('scripts')
    <script>
        // إدارة الكمية
        function increaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            const maxStock = {{ $product->stock }};
            if (parseInt(quantityInput.value) < maxStock) {
                quantityInput.value = parseInt(quantityInput.value) + 0;
            }
        }

        function decreaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            if (parseInt(quantityInput.value) > 1) {
                quantityInput.value = parseInt(quantityInput.value) - 1;
            }
        }

        // تحديد المتغير
        let selectedVariantId = null;
        let selectedVariantPrice = {{ $product->price }};

        function selectVariant(variantId, price) {
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                if (checkbox.id !== 'variant' + variantId) {
                    checkbox.checked = false;
                }
            });

            selectedVariantId = variantId;
            selectedVariantPrice = price;
            document.getElementById('currentPrice').textContent = price.toFixed(2) + ' ر.س';
        }

        // إضافة إلى السلة
        function addToCart() {
    const quantity = parseInt(document.getElementById('quantity').value);
    
    // التحقق من المخزون
    const maxStock = {{ $product->stock }};
    if (quantity > maxStock) {
        showAlert('error', 'الكمية المطلوبة غير متوفرة في المخزون. المتاح: ' + maxStock);
        return;
    }

    const data = {
        product_id: {{ $product->product_id }},
        quantity: quantity,
        _token: '{{ csrf_token() }}'
    };

    if (selectedVariantId) {
        data.variant_id = selectedVariantId;
        
        // التحقق من مخزون المتغير
        @foreach($product->variants as $variant)
            if (selectedVariantId === {{ $variant->variant_id }}) {
                if (quantity > {{ $variant->stock }}) {
                    showAlert('error', 'الكمية المطلوبة غير متوفرة في المخزون. المتاح: ' + {{ $variant->stock }});
                    return;
                }
            }
        @endforeach
    }

    // استخدام fetch مع FormData بدلاً من JSON
    const formData = new FormData();
    for (const key in data) {
        formData.append(key, data[key]);
    }

    fetch('{{ route("front.cart.add") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                updateCartCount();
                
                // تحديث واجهة السلة إذا كانت مفتوحة
                if (typeof updateCartUI === 'function') {
                    updateCartUI();
                }
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
            showAlert('error', 'حدث خطأ أثناء إضافة المنتج إلى السلة');
        });
}

        // إدارة المفضلة
        function toggleWishlist() {
            const isInWishlist = {{ $inWishlist ? 'true' : 'false' }};
            const url = isInWishlist ? '{{ route('front.wishlist.remove') }}' : '{{ route('front.wishlist.add') }}';

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: {{ $product->product_id }}
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const wishlistBtn = document.getElementById('wishlistBtn');
                        if (isInWishlist) {
                            wishlistBtn.innerHTML = '<i class="la la-heart-o me-2"></i>أضف إلى المفضلة';
                            showAlert('info', data.message);
                        } else {
                            wishlistBtn.innerHTML = '<i class="la la-heart me-2"></i>مضاف للمفضلة';
                            showAlert('success', data.message);
                        }
                    } else {
                        if (data.login_required) {
                            window.location.href = '{{ route('login') }}';
                        } else {
                            showAlert('error', data.message);
                        }
                    }
                });
        }

        // مشاركة المنتج
        function shareProduct() {
            if (navigator.share) {
                navigator.share({
                        title: '{{ $product->title }}',
                        text: '{{ Str::limit($product->description, 100) }}',
                        url: window.location.href,
                    })
                    .then(() => showAlert('success', 'تمت المشاركة بنجاح'))
                    .catch(error => showAlert('error', 'فشلت المشاركة'));
            } else {
                navigator.clipboard.writeText(window.location.href);
                showAlert('success', 'تم نسخ رابط المنتج');
            }
        }

        // إضافة تقييم
        document.getElementById('reviewForm')?.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            // إضافة product_id إلى البيانات
            formData.append('product_id', {{ $product->product_id }});

            fetch('{{ route('front.products.review.store', $product->product_id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showAlert('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'حدث خطأ أثناء إضافة التقييم');
                });
        });

        // مفيد/غير مفيد - التصحيح الكامل
        function toggleHelpful(reviewId) {
            if (!{{ Auth::check() ? 'true' : 'false' }}) {
                showAlert('error', 'يجب تسجيل الدخول أولاً');
                return;
            }

            // استخدام المسار المباشر بدلاً من route() لتجنب الأخطاء
            fetch(`/review/${reviewId}/helpful`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({})
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const countSpan = document.getElementById('helpfulCount' + reviewId);
                        if (countSpan) {
                            countSpan.textContent = data.helpful_count;
                        }
                        showAlert('success', 'شكراً لك!');
                    } else {
                        showAlert('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'حدث خطأ أثناء التحديث');
                });
        }

        // وظائف مساعدة
        function showAlert(type, message) {
            // استخدام SweetAlert2 إذا كان متوفراً، أو alert عادي
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: type,
                    title: message,
                    showConfirmButton: false,
                    timer: 3000
                });
            } else {
                if (type === 'success') {
                    alert('✅ ' + message);
                } else if (type === 'error') {
                    alert('❌ ' + message);
                } else if (type === 'info') {
                    alert('ℹ️ ' + message);
                } else {
                    alert(message);
                }
            }
        }

        function updateCartCount() {
    fetch('{{ route("front.cart.count") }}', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const cartCounts = document.querySelectorAll('.cart-count, .cart-count-badge');
        cartCounts.forEach(count => {
            count.textContent = data;
        });
    })
    .catch(error => {
        console.error('Error updating cart count:', error);
    });
}


        // تفعيل شريط التقدم
        document.addEventListener('DOMContentLoaded', function() {
            const progressBars = document.querySelectorAll('.progressbar-line');
            progressBars.forEach(bar => {
                const percent = bar.getAttribute('data-percent');
                const item = bar.querySelector('.progressbar-line-item');
                setTimeout(() => {
                    item.style.width = percent + '%';
                }, 500);
            });

            // تفعيل النجوم التفاعلية
            const starInputs = document.querySelectorAll('.star-rating-input input[type="radio"]');
            const starLabels = document.querySelectorAll('.star-rating-input .star-label');

            starLabels.forEach((label, index) => {
                label.addEventListener('mouseenter', function() {
                    starLabels.forEach((l, i) => {
                        if (i <= index) {
                            l.classList.add('hover');
                        } else {
                            l.classList.remove('hover');
                        }
                    });
                });
            });

            document.querySelector('.star-rating-input').addEventListener('mouseleave', function() {
                starLabels.forEach(l => l.classList.remove('hover'));
            });
        });

        // التنقل بين الأقسام
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.scroll-link');
            const sections = document.querySelectorAll('.page-scroll');

            function updateActiveNav() {
                let current = '';
                sections.forEach(section => {
                    const sectionTop = section.offsetTop - 100;
                    if (window.scrollY >= sectionTop) {
                        current = section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('data-scroll') === current) {
                        link.classList.add('active');
                    }
                });
            }

            window.addEventListener('scroll', updateActiveNav);

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('data-scroll');
                    const targetSection = document.getElementById(targetId);

                    window.scrollTo({
                        top: targetSection.offsetTop - 80,
                        behavior: 'smooth'
                    });

                    navLinks.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script>
@endsection
