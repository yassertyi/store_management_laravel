<!-- ================================
    START SELLER SIDEBAR
================================= -->
<div class="sidebar-nav sidebar--nav">
    <div class="sidebar-nav-body">
        <div class="side-menu-close">
            <i class="la la-times"></i>
        </div>

        <!-- البروفايل -->
        <!-- البروفايل -->
<div class="author-content position-relative mb-3">
    <div class="d-flex align-items-center">
        <div class="author-img avatar-sm position-relative">
            @if (auth()->user()->profile_photo)
                <img src="{{ asset(auth()->user()->profile_photo) }}" alt="صورة {{ auth()->user()->name }}"
                    class="rounded-circle me-2" width="40" height="40">
            @else
                <img src="{{ asset('static/images/Default_pfp.jpg') }}" alt="صورة افتراضية"
                    class="rounded-circle me-2" width="40" height="40">
            @endif

            <!-- زر التعديل فوق الصورة -->
            <a href="{{ route('seller.profile.edit') }}"
               class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-1"
               style="width:20px; height:20px; font-size:12px; display:flex; align-items:center; justify-content:center;"
               title="تعديل البروفايل">
               <i class="la la-pencil"></i>
            </a>
        </div>
        <div class="author-bio ms-2">
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
                    <a href="{{ route('seller.dashboard') }}">
                        <i class="la la-dashboard me-2 text-color-2"></i>لوحة القيادة
                    </a>
                </li>

                <!-- إدارة المنتجات -->
                <li class="{{ request()->is('seller/products*') ? 'page-active' : '' }}">
                    <span class="side-menu-icon toggle-menu-icon">
                        <i class="la la-angle-down"></i>
                    </span>
                    <a href="{{ route('seller.products.index') }}"><i class="la la-cubes me-2 text-color-3"></i>إدارة
                        المنتجات</a>
                    <ul class="toggle-drop-menu">
                        <li class="{{ request()->is('seller/products') ? 'page-active' : '' }}">
                            <a href="{{ route('seller.products.index') }}">جميع المنتجات</a>
                        </li>
                        <li class="{{ request()->is('seller/products/create') ? 'page-active' : '' }}">
                            <a href="{{ route('seller.products.create') }}">إضافة منتج</a>
                        </li>
                        <li class="{{ request()->is('seller/product-variants*') ? 'page-active' : '' }}">
                            <a href="{{ route('seller.products.variants.index') }}">متغيرات المنتجات</a>
                        </li>
                        <li class="{{ request()->is('seller/product-images*') ? 'page-active' : '' }}">
                            <a href="{{ route('seller.products.images.index') }}">صور المنتجات</a>
                        </li>
                    </ul>
                </li>


                <!-- الطلبات الخاصة بالمتجر -->
                <li class="{{ request()->is('seller/orders*') ? 'page-active' : '' }}">
                    <a href="{{ route('seller.orders.index') }}">
                        <i class="la la-shopping-cart me-2 text-color-5"></i>الطلبات
                    </a>
                </li>

                <!-- المدفوعات الخاصة بالمتجر -->
                <li class="{{ request()->is('seller/payments*') ? 'page-active' : '' }}">
                    <span class="side-menu-icon toggle-menu-icon">
                        <i class="la la-angle-down"></i>
                    </span>
                    <a href="{{ route('seller.payments.index') }}">
                        <i class="la la-credit-card me-2 text-color-6"></i>المدفوعات
                    </a>
                    <ul class="toggle-drop-menu">
                        <li class="{{ request()->is('seller/payments') ? 'page-active' : '' }}">
                            <a href="{{ route('seller.payments.index') }}">إدارة المدفوعات</a>
                        </li>
                        <li class="{{ request()->is('seller/store-payment-methods*') ? 'page-active' : '' }}">
                            <a href="{{ route('seller.storePaymentMethods.index') }}">طرق الدفع الخاصة بالمتجر</a>
                        </li>
                    </ul>
                </li>


                <!-- الكوبونات والعروض -->
                <li class="{{ request()->is('seller/coupons*') ? 'page-active' : '' }}">
                    <a href="{{ route('seller.coupons.index') }}">
                        <i class="la la-gift me-2 text-color-7"></i>الكوبونات والعروض
                    </a>
                </li>

                <!-- التقييمات والمفضلة -->
                <li
                    class="{{ request()->is('seller/reviews*') || request()->is('seller/review-helpful*') || request()->is('seller/wishlists*') ? 'page-active' : '' }}">
                    <span class="side-menu-icon toggle-menu-icon">
                        <i class="la la-angle-down"></i>
                    </span>
                    <a href="{{ route('seller.reviews.index') }}"><i class="la la-star me-2 text-color-4"></i>التقييمات والمفضلة</a>
                    <ul class="toggle-drop-menu">
                        <li class="{{ request()->is('seller/reviews') ? 'page-active' : '' }}">
                            <a href="{{ route('seller.reviews.index') }}">جميع التقييمات</a>
                        </li>
                        <li class="{{ request()->is('seller/review-helpful') ? 'page-active' : '' }}">
                            <a href="{{ route('seller.review-helpful.index') }}">التقييمات المفيدة</a>
                        </li>
                        <li class="{{ request()->is('seller/wishlists') ? 'page-active' : '' }}">
                            <a href="{{ route('seller.wishlists.index') }}">قوائم المفضلة</a>
                        </li>
                    </ul>
                </li>

                <!-- والإحصائيات -->
                <li
                    class="{{ request()->is('seller/statistics*') ? 'page-active' : '' }}">
                    <span class="side-menu-icon toggle-menu-icon">
                        <i class="la la-angle-down"></i>
                    </span>
                    <a href="{{ route('seller.seller.statistics.sales') }}"><i
                            class="la la-bar-chart me-2 text-color-9"></i>التقارير</a>
                    <ul class="toggle-drop-menu">
                        <li class="{{ request()->is('seller/statistics/sales') ? 'page-active' : '' }}">
                            <a href="{{ route('seller.seller.statistics.sales') }}">تقارير المبيعات</a>
                        </li>
                        <li class="{{ request()->is('seller/statistics/users') ? 'page-active' : '' }}">
                            <a href="{{ route('seller.seller.statistics.users') }}">تقارير المستخدمين</a>
                        </li>
                        <li class="{{ request()->is('seller/statistics/products') ? 'page-active' : '' }}">
                            <a href="{{ route('seller.seller.statistics.products') }}">تقارير المنتجات</a>
                        </li>
                    </ul>
                </li>

                <!-- الأشعارات -->
                <li class="{{ request()->is('seller/statistics*') ? 'page-active' : '' }}">
                    <a href="{{ route('seller.notifications.index') }}">
                        <i class="la la-bell me-2 text-color-8"></i>الاشعارات
                    </a>
                </li>

                <!-- إعدادات المتجر -->
                <li class="{{ request()->is('seller/settings*') ? 'page-active' : '' }}">
                    <a href="{{ route('seller.seller.store.edit') }}">
                        <i class="la la-cog me-2 text-color-10"></i>إعدادات المتجر
                    </a>
                </li>

                <!-- الدعم الفني -->
                <li class="{{ request()->is('seller/support*') ? 'page-active' : '' }}">
                    <a href="{{ route('seller.seller.support.index') }}">
                        <i class="la la-life-ring me-2 text-color-11"></i>الدعم الفني
                    </a>
                </li>

                <!-- تسجيل الخروج -->
                <li>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); 
                        if(confirm('هل أنت متأكد من تسجيل الخروج؟')) { 
                            document.getElementById('logout-form').submit(); 
                        }">
                        <i class="la la-power-off me-2 text-danger"></i>تسجيل خروج
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
