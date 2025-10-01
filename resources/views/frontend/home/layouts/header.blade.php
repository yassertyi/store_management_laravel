<!-- ================================
            START HEADER AREA
================================= -->
<header class="header-area page-specific-header">
    <div class="header-top-bar padding-right-100px padding-left-100px">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="header-top-content">
                        <div class="header-left">
                            <ul class="list-items">
                                <li>
                                    <a href="#"><i class="la la-phone me-1"></i>(123) 123-456</a>
                                </li>
                                <li>
                                    <a href="#"><i class="la la-envelope me-1"></i>info@mystore.com</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="header-top-content">
                        <div class="header-right d-flex align-items-center justify-content-end">


                            @guest
                                {{-- إذا لم يكن مسجل دخول --}}
                                <div class="header-right-action">
                                    <a href="{{ route('register.submit') }}"
                                        class="theme-btn theme-btn-small theme-btn-transparent me-1l">سجل</a>
                                    <a href="{{ route('login.form') }}" class="theme-btn theme-btn-small">تسجيل الدخول</a>
                                </div>
                            @endguest
                            @auth
                                {{-- إذا كان مسجل دخول --}}
                                @include('frontend.admin.partials.notifications')
                                @include('frontend.admin.partials.user-menu')
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-menu-wrapper padding-right-100px padding-left-100px">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="menu-wrapper">
                        <a href="#" class="down-button"><i class="la la-angle-down"></i></a>
                        <div class="logo">
                            <a href="{{ route('front.home') }}"><img src="{{ asset('static/images/logo.png') }}"
                                    alt="logo" /></a>
                            <div class="menu-toggler">
                                <i class="la la-bars"></i>
                                <i class="la la-times"></i>
                            </div>
                        </div>
                        <div class="main-menu-content">
                            <nav>
                                <ul>
                                    <li>
                                        <a href="{{ route('front.home') }}">الصفحة الرئيسية</a>
                                    </li>
                                    <li>
                                        <a href="#categories">الفئات</a>
                                    </li>
                                    <li>
                                        <a href="#featured-products">منتجات مميزة</a>
                                    </li>
                                    <li>
                                        <a href="#">الصفحات <i class="la la-angle-down"></i></a>
                                        <div class="dropdown-menu-item mega-menu">
                                            <ul class="row no-gutters">
                                                <li class="col-lg-3 mega-menu-item">
                                                    <ul>
                                                        <li><a href="about.html">عن المتجر</a></li>
                                                        <li><a href="contact.html">اتصل بنا</a></li>
                                                        <li><a href="faq.html">الأسئلة الشائعة</a></li>
                                                        <li><a href="terms.html">الشروط والأحكام</a></li>
                                                    </ul>
                                                </li>
                                                <li class="col-lg-3 mega-menu-item">
                                                    <ul>
                                                        <li><a href="user-dashboard.html">لوحة تحكم المستخدم</a></li>
                                                        <li><a href="cart.html">عربة التسوق</a></li>
                                                        <li><a href="checkout.html">الدفع</a></li>
                                                        <li><a href="track-order.html">تتبع الطلب</a></li>
                                                    </ul>
                                                </li>
                                                <li class="col-lg-3 mega-menu-item">
                                                    <ul>
                                                        <li><a href="blog-grid.html">المدونة</a></li>
                                                        <li><a href="blog-single.html">تفاصيل المدونة</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li>
                                        <a href="#offers">العروض</a>
                                    </li>
                                    <li>
                                        <a href="#stores">المتجر</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>


                        @auth
                            <a href="{{ route('front.cart.index') }}" class="nav-cart position-relative">
                                <div class="cart-icon-wrapper">
                                    <div class="cart-icon">
                                        <i class="fas fa-shopping-bag"></i>
                                        <div class="cart-pulse"></div>
                                    </div>
                                    <div class="cart-info">
                                        <span class="cart-label">السلة</span>
                                        <div class="cart-stats">
                                            <span
                                                class="cart-total">{{ Auth::check()? number_format(\App\Models\CartItem::where('user_id', Auth::id())->get()->sum(function ($item) {return $item->price * $item->quantity;}),2): '0.00' }}
                                                ر.ي</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- عدادات المنتجات والمتاجر -->
                                <div class="cart-badges">
                                    <span
                                        class="cart-count badge">{{ Auth::check() ? \App\Models\CartItem::where('user_id', Auth::id())->sum('quantity') : 0 }}</span>
                                    <span
                                        class="stores-count badge">{{ Auth::check() ? \App\Models\CartItem::where('user_id', Auth::id())->distinct('store_id')->count('store_id') : 0 }}</span>
                                </div>

                                <!-- تأثير Hover -->
                                <div class="cart-hover-effect"></div>
                            </a>
                        @endauth

                        @guest
                            <div class="nav-btn">
                                <a href="{{ route('seller.registerStore.form') }}" class="theme-btn theme-btn-small">أضف
                                    متجرك الآن</a>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- إضافة CSS لتغيير لون أيقونة الإشعارات في هذه الصفحة فقط -->
    <style>
        .page-specific-header .header-right .la-bell {
            color: rgb(64, 0, 255);
            /* غيّر اللون كما تريد */
        }
    </style>

</header>
<!-- ================================
     END HEADER AREA
================================= -->
<style>
    .nav-cart {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: #333;
        padding: 12px 16px;
        border-radius: 12px;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow:
            0 4px 15px rgba(0, 0, 0, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.8);
        position: relative;
        overflow: hidden;
        min-width: 140px;
    }

    .nav-cart::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: left 0.6s;
    }

    .nav-cart:hover::before {
        left: 100%;
    }

    .nav-cart:hover {
        transform: translateY(-2px);
        box-shadow:
            0 8px 25px rgba(74, 108, 247, 0.15),
            0 4px 15px rgba(0, 0, 0, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.9);
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-color: #4a6cf7;
    }

    .cart-icon-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .cart-icon {
        position: relative;
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #4a6cf7 0%, #3a5ce5 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(74, 108, 247, 0.3);
        transition: all 0.3s ease;
    }

    .nav-cart:hover .cart-icon {
        transform: scale(1.1) rotate(-5deg);
        box-shadow: 0 6px 20px rgba(74, 108, 247, 0.4);
    }

    .cart-icon i {
        font-size: 18px;
        color: white;
        transition: transform 0.3s ease;
    }

    .nav-cart:hover .cart-icon i {
        transform: scale(1.1);
    }

    .cart-pulse {
        position: absolute;
        top: -2px;
        right: -2px;
        width: 16px;
        height: 16px;
        background: #ff4757;
        border-radius: 50%;
        border: 2px solid white;
        animation: pulse 2s infinite;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 9px;
        font-weight: bold;
        color: white;
    }

    .cart-info {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .cart-label {
        font-size: 14px;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 2px;
    }

    .cart-stats {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .cart-total {
        font-size: 12px;
        font-weight: 700;
        color: #4a6cf7;
        background: rgba(74, 108, 247, 0.1);
        padding: 2px 8px;
        border-radius: 6px;
    }

    .cart-badges {
        position: absolute;
        top: -8px;
        right: -8px;
        display: flex;
        gap: 2px;
    }

    .cart-badges .badge {
        min-width: 20px;
        height: 20px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .cart-count {
        background: linear-gradient(135deg, #ff4757 0%, #ff3742 100%);
        color: white;
        z-index: 2;
    }

    .stores-count {
        background: linear-gradient(135deg, #ffa502 0%, #ff9f1a 100%);
        color: white;
        z-index: 1;
        margin-left: -5px;
    }

    .cart-hover-effect {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(74, 108, 247, 0.1) 0%, transparent 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        border-radius: 12px;
    }

    .nav-cart:hover .cart-hover-effect {
        opacity: 1;
    }

    /* أنيميشن النبض */
    @keyframes pulse {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(255, 71, 87, 0.7);
        }

        70% {
            transform: scale(1.05);
            box-shadow: 0 0 0 6px rgba(255, 71, 87, 0);
        }

        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(255, 71, 87, 0);
        }
    }

    /* تأثير عند إضافة منتج جديد */
    .cart-add-animation {
        animation: cartBounce 0.6s ease;
    }

    @keyframes cartBounce {
        0% {
            transform: scale(1);
        }

        25% {
            transform: scale(1.1);
        }

        50% {
            transform: scale(0.95);
        }

        75% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }

    /* تصميم متجاوب */
    @media (max-width: 768px) {
        .nav-cart {
            padding: 10px 12px;
            min-width: auto;
        }

        .cart-icon-wrapper {
            gap: 8px;
        }

        .cart-icon {
            width: 36px;
            height: 36px;
        }

        .cart-icon i {
            font-size: 16px;
        }

        .cart-label {
            font-size: 12px;
        }

        .cart-total {
            font-size: 10px;
            padding: 1px 6px;
        }

        .cart-badges .badge {
            min-width: 16px;
            height: 16px;
            font-size: 9px;
        }
    }

    /* حالة السلة الفارغة */
    .nav-cart.empty .cart-icon {
        background: linear-gradient(135deg, #a0aec0 0%, #718096 100%);
    }

    .nav-cart.empty .cart-pulse {
        display: none;
    }

    .nav-cart.empty .cart-total {
        color: #a0aec0;
        background: rgba(160, 174, 192, 0.1);
    }
</style>

<script>
    // دالة لتحديث أيقونة السلة
    function updateCartIcon(cartCount, storesCount, grandTotal) {
        const navCart = document.querySelector('.nav-cart');
        const cartCountBadge = document.querySelector('.cart-count');
        const storesCountBadge = document.querySelector('.stores-count');
        const cartTotal = document.querySelector('.cart-total');

        // تحديث القيم
        cartCountBadge.textContent = cartCount;
        storesCountBadge.textContent = storesCount;
        cartTotal.textContent = grandTotal + ' ر.س';

        // إضافة أنيميشن عند التحديث
        navCart.classList.add('cart-add-animation');
        setTimeout(() => {
            navCart.classList.remove('cart-add-animation');
        }, 600);

        // إظهار/إخفاء العدادات
        if (cartCount > 0) {
            navCart.classList.remove('empty');
            cartCountBadge.style.display = 'flex';
            storesCountBadge.style.display = 'flex';
        } else {
            navCart.classList.add('empty');
            cartCountBadge.style.display = 'none';
            storesCountBadge.style.display = 'none';
        }

        // تأثير النبض عند إضافة منتج جديد
        if (cartCount > parseInt(cartCountBadge.getAttribute('data-prev-count') || 0)) {
            const pulse = document.querySelector('.cart-pulse');
            pulse.style.animation = 'none';
            setTimeout(() => {
                pulse.style.animation = 'pulse 2s infinite';
            }, 10);
        }

        // حفظ العدد السابق للمقارنة
        cartCountBadge.setAttribute('data-prev-count', cartCount);
    }

    // تحديث الأيقونة عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        @if (Auth::check())
            const cartCount = {{ \App\Models\CartItem::where('user_id', Auth::id())->sum('quantity') }};
            const storesCount =
                {{ \App\Models\CartItem::where('user_id', Auth::id())->distinct('store_id')->count('store_id') }};
            const grandTotal =
                {{ \App\Models\CartItem::where('user_id', Auth::id())->get()->sum(function ($item) {return $item->price * $item->quantity;}) }};
            updateCartIcon(cartCount, storesCount, grandTotal.toFixed(2));
        @else
            updateCartIcon(0, 0, '0.00');
        @endif
    });

    // دالة يمكن استدعاؤها عند إضافة منتج للسلة
    function animateCartIcon() {
        const navCart = document.querySelector('.nav-cart');
        navCart.classList.add('cart-add-animation');
        setTimeout(() => {
            navCart.classList.remove('cart-add-animation');
        }, 600);
    }
</script>
