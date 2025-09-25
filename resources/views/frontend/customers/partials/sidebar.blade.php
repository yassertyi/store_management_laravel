<!-- ================================
    START CUSTOMER DASHBOARD NAV
================================= -->
<div class="sidebar-nav sidebar--nav">
    <div class="sidebar-nav-body">
        <div class="side-menu-close">
            <i class="la la-times"></i>
        </div>
        <!-- end menu-toggler -->
        <div class="author-content">
            <div class="d-flex align-items-center">
                <div class="author-img avatar-sm">
                    @if (auth()->user()->profile_photo)
                        <img src="{{ asset(auth()->user()->profile_photo) }}" alt="صورة {{ auth()->user()->name }}"
                            class="rounded-circle me-2" width="40" height="40">
                    @else
                        <img src="{{ asset('static/images/Default_pfp.jpg') }}" alt="صورة افتراضية"
                            class="rounded-circle me-2" width="40" height="40">
                    @endif
                </div>
                <div class="author-bio">
                    <h4 class="author__title">{{ auth()->user()->name }}</h4>
                    <span class="author__meta">مرحبًا بك في حسابك</span>
                </div>
            </div>
        </div>

        <div class="sidebar-menu-wrap">
            <ul class="sidebar-menu toggle-menu list-items">
                <li class="{{ request()->is('customer/dashboard') ? 'page-active' : '' }}">
                    <a href="{{ route('customer.dashboard') }}"><i class="la la-dashboard me-2"></i>لوحة التحكم</a>
                </li>

                <!-- الطلبات -->
                <li class="{{ request()->is('customer/orders*') ? 'page-active' : '' }}">
                    <a href="{{ route('customer.orders.index') }}"><i
                            class="la la-shopping-cart me-2 text-color"></i>طلباتي</a>
                </li>

                <!-- العناوين -->
                <li class="{{ request()->is('customer/addresses*') ? 'page-active' : '' }}">
                    <a href="{{ route('customer.addresses.index') }}">
                        <i class="la la-map-marker me-2 text-color-2"></i>عناويني
                    </a>
                </li>


                <!-- المفضلة -->
                <li class="{{ request()->is('customer/wishlist*') ? 'page-active' : '' }}">
                    <a href="{{ route('customer.wishlist.index') }}"><i class="la la-heart me-2 text-color-3"></i>قائمة
                        المفضلة</a>
                </li>

                <!-- التقييمات -->
                <li class="{{ request()->is('customer/reviews*') ? 'page-active' : '' }}">
                    <a href="{{ route('customer.reviews.index') }}">
                        <i class="la la-star me-2 text-color-4"></i>تقييماتي
                    </a>
                </li>


                <!-- المحادثات والدعم -->
                <li class="{{ request()->is('customer/support*') ? 'page-active' : '' }}">
                    <a href="{{ route('customer.support.tickets.index') }}">
                        <i class="la la-headset me-2 text-color-5"></i>الدعم الفني
                    </a>
                </li>
                
                <!-- الأشعارات -->
                <li class="{{ request()->is('seller/statistics*') ? 'page-active' : '' }}">
                    <a href="{{ route('customer.notifications.index') }}">
                        <i class="la la-bell me-2 text-color-8"></i>الاشعارات
                    </a>
                </li>

                <!-- الإعدادات -->
                <li class="{{ request()->is('customer/settings*') ? 'page-active' : '' }}">
                    <a href="{{ route('customer.profile.edit') }}"><i class="la la-cog me-2 text-color-6"></i>الإعدادات</a>
                </li>

                <!-- تسجيل الخروج -->
                <li>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); 
                        if(confirm('هل أنت متأكد من تسجيل الخروج؟')) { 
                            document.getElementById('logout-form').submit(); 
                        }">
                        <i class="la la-power-off me-2 text-color-7"></i>تسجيل خروج
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
        <!-- end sidebar-menu-wrap -->
    </div>
</div>
<!-- end sidebar-nav -->
