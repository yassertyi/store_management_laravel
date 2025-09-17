<!-- ================================
    START SELLER SIDEBAR
================================= -->
<div class="sidebar-nav sidebar--nav">
    <div class="sidebar-nav-body">
        <div class="side-menu-close">
            <i class="la la-times"></i>
        </div>
        <!-- البروفايل -->
        <div class="author-content">
            <div class="d-flex align-items-center">
                <div class="author-img avatar-sm">
                    @if(auth()->user()->profile_photo)
                        <img src="{{ asset(auth()->user()->profile_photo) }}" 
                             alt="صورة {{ auth()->user()->name }}" 
                             class="rounded-circle me-2" width="40" height="40">
                    @else
                        <img src="{{ asset('static/images/Default_pfp.jpg') }}" 
                             alt="صورة افتراضية" 
                             class="rounded-circle me-2" width="40" height="40">
                    @endif
                </div>
                <div class="author-bio">
                    <h4 class="author__title">{{ auth()->user()->name }}</h4>
                    <span class="author__meta">بائع</span>
                </div>
            </div>
        </div>

        <!-- روابط القائمة -->
        <div class="sidebar-menu-wrap">
            <ul class="sidebar-menu toggle-menu list-items">
                <!-- لوحة القيادة -->
                <li class="{{ request()->is('seller/dashboard') ? 'page-active' : '' }}">
                    <a href="{{ route('seller.dashboard') }}"><i class="la la-dashboard me-2 text-color-2"></i>لوحة القيادة</a>
                </li>

                <!-- إدارة المنتجات -->
                <li class="{{ request()->is('seller/products*') ? 'page-active' : '' }}">
                    <span class="side-menu-icon toggle-menu-icon"><i class="la la-angle-down"></i></span>
                    <a href="#"><i class="la la-cubes me-2 text-color-3"></i>إدارة المنتجات</a>
                    <ul class="toggle-drop-menu">
                        <li><a href="#">جميع المنتجات</a></li>
                        <li><a href="#">إضافة منتج</a></li>
                    </ul>
                </li>

                <!-- التصنيفات -->
                <li class="{{ request()->is('seller/categories*') ? 'page-active' : '' }}">
                    <a href="#"><i class="la la-tags me-2 text-color-4"></i>التصنيفات</a>
                </li>

                <!-- الطلبات -->
                <li class="{{ request()->is('seller/orders*') ? 'page-active' : '' }}">
                    <a href="#"><i class="la la-shopping-cart me-2 text-color-5"></i>الطلبات</a>
                </li>

                <!-- المدفوعات -->
                <li class="{{ request()->is('seller/payments*') ? 'page-active' : '' }}">
                    <a href="#"><i class="la la-credit-card me-2 text-color-6"></i>المدفوعات</a>
                </li>

                <!-- الكوبونات -->
                <li class="{{ request()->is('seller/coupons*') ? 'page-active' : '' }}">
                    <a href="#"><i class="la la-gift me-2 text-color-7"></i>الكوبونات والعروض</a>
                </li>

                <!-- التقييمات -->
                <li class="{{ request()->is('seller/reviews*') ? 'page-active' : '' }}">
                    <a href="#"><i class="la la-star me-2 text-color-8"></i>التقييمات</a>
                </li>

                <!-- العملاء -->
                <li class="{{ request()->is('seller/customers*') ? 'page-active' : '' }}">
                    <a href="#"><i class="la la-users me-2 text-color-9"></i>العملاء</a>
                </li>

                <!-- الإحصائيات -->
                <li class="{{ request()->is('seller/statistics*') ? 'page-active' : '' }}">
                    <a href="#"><i class="la la-bar-chart me-2 text-color-10"></i>الإحصائيات</a>
                </li>

                <!-- إعدادات المتجر -->
                <li class="{{ request()->is('seller/settings*') ? 'page-active' : '' }}">
                    <a href="#"><i class="la la-cog me-2 text-color-11"></i>إعدادات المتجر</a>
                </li>

                <!-- الدعم الفني -->
                <li class="{{ request()->is('seller/support*') ? 'page-active' : '' }}">
                    <a href="#"><i class="la la-life-ring me-2 text-color-12"></i>الدعم الفني</a>
                </li>

                <!-- تسجيل الخروج -->
                <li>
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); if(confirm('هل أنت متأكد من تسجيل الخروج؟')) { document.getElementById('logout-form').submit(); }">
                        <i class="la la-power-off me-2 text-danger"></i> تسجيل خروج
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- ================================
    END SELLER SIDEBAR
================================= -->
