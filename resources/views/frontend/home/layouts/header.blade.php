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
                                    <a href="{{ route('register.submit') }}" class="theme-btn theme-btn-small theme-btn-transparent me-1l">سجل</a>
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
                            <a href="{{ route('front.home') }}"><img src="{{ asset('static/images/logo.png') }}" alt="logo" /></a>
                            <div class="menu-toggler">
                                <i class="la la-bars"></i>
                                <i class="la la-times"></i>
                            </div>
                        </div>
                        <div class="main-menu-content">
                            <nav>
                                <ul>
                                    <li>
                                        <a href="#">الصفحة الرئيسية <i class="la la-angle-down"></i></a>
                                        <ul class="dropdown-menu-item">
                                            <li><a href="{{ route('front.home') }}">الصفحة الرئيسية - الرئيسية</a></li>
                                            <li><a href="index2.html">الصفحة الرئيسية - الإلكترونيات</a></li>
                                            <li><a href="index3.html">الصفحة الرئيسية - الأزياء</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#">الفئات <i class="la la-angle-down"></i></a>
                                        <ul class="dropdown-menu-item">
                                            <li><a href="category-grid.html">الإلكترونيات</a></li>
                                            <li><a href="category-grid.html">الأزياء</a></li>
                                            <li><a href="category-grid.html">المنزل</a></li>
                                            <li><a href="category-grid.html">الجمال والعناية</a></li>
                                            <li><a href="category-grid.html">الرياضة</a></li>
                                            <li><a href="category-grid.html">الأطفال</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#">منتجات مميزة <i class="la la-angle-down"></i></a>
                                        <ul class="dropdown-menu-item">
                                            <li><a href="products.html">الأكثر مبيعاً</a></li>
                                            <li><a href="products.html">العروض الخاصة</a></li>
                                            <li><a href="products.html">وصل حديثاً</a></li>
                                        </ul>
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
                                        <a href="#">العروض <i class="la la-angle-down"></i></a>
                                        <ul class="dropdown-menu-item">
                                            <li><a href="offers.html">تخفيضات حتى 50%</a></li>
                                            <li><a href="offers.html">عروض نهاية الموسم</a></li>
                                            <li><a href="offers.html">عروض التصفية</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#">المتجر <i class="la la-angle-down"></i></a>
                                        <ul class="dropdown-menu-item">
                                            <li><a href="brands.html">العلامات التجارية</a></li>
                                            <li><a href="stores.html">المتاجر</a></li>
                                            <li><a href="vendor.html">البائعون</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </nav>
                        </div>

                        @auth
                            <div class="nav-btn">
                                <a href="{{ route('front.cart.index') }}" class="theme-btn position-relative">
                                    <i class="la la-shopping-cart"></i>
                                    <span class="cart-count">3</span>
                                </a>
                            </div>
                        @endauth

                        @guest
                            <div class="nav-btn">
                                <a href="{{ route('seller.registerStore.form') }}" class="theme-btn theme-btn-small">أضف متجرك الآن</a>
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
            color: rgb(64, 0, 255); /* غيّر اللون كما تريد */
        }
    </style>

</header>
<!-- ================================
     END HEADER AREA
================================= -->
