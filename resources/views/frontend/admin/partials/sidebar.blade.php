<!-- ================================
    START DASHBOARD NAV
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
            <span class="author__meta">مرحبًا بك في لوحة الإدارة</span>
        </div>
    </div>
</div>

        <div class="sidebar-menu-wrap">
            <ul class="sidebar-menu toggle-menu list-items">
                <li class="{{ request()->is('admin/dashboard') ? 'page-active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}"><i class="la la-dashboard me-2"></i>لوحة القيادة</a>
                </li>

                <!-- إدارة المستخدمين -->
                <li
                    class="{{ request()->is('admin/users*') || request()->is('admin/admins*') || request()->is('admin/customers*') || request()->is('admin/sellers*') ? 'page-active' : '' }}">
                    <span class="side-menu-icon toggle-menu-icon">
                        <i class="la la-angle-down"></i>
                    </span>
                    <a href="{{ route('admin.users.index') }}"><i class="la la-users me-2 text-color-3"></i>إدارة
                        المستخدمين</a>
                    <ul class="toggle-drop-menu">
                        <li class="{{ request()->is('admin/users') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.users.index') }}">جميع المستخدمين</a>
                        </li>
                        <li class="{{ request()->is('admin/customers') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.customers.index') }}">العملاء</a>
                        </li>
                        <li class="{{ request()->is('admin/sellers') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.sellers.index') }}">البائعون</a>
                        </li>
                        <li class="{{ request()->is('admin/seller-request') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.seller-requests.index') }}">طلبات فتح حساب متجر</a>
                        </li>
                        <li class="{{ request()->is('admin/user-activities') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.user-activities.index') }}">نشاطات المستخدمين</a>
                        </li>
                    </ul>
                </li>

                <!-- إدارة المتاجر -->
                <li
                    class="{{ request()->is('admin/stores*') || request()->is('admin/store-*') ? 'page-active' : '' }}">
                    <span class="side-menu-icon toggle-menu-icon">
                        <i class="la la-angle-down"></i>
                    </span>
                    <a href="{{ route('admin.stores.index') }}"><i class="la la-store me-2 text-color-10"></i>إدارة
                        المتاجر</a>
                    <ul class="toggle-drop-menu">
                        <li class="{{ request()->is('admin/stores') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.stores.index') }}">جميع المتاجر</a>
                        </li>
                        <li class="{{ request()->is('admin/stores/create') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.stores.create') }}">إضافة متجر</a>
                        </li>
                        <li class="{{ request()->is('admin/store-phones') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.store.phones') }}">اتصالات المتاجر</a>
                        </li>
                        <li class="{{ request()->is('admin/store-addresses') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.store.addresses') }}">عناوين المتاجر</a>
                        </li>
                        
                    </ul>
                </li>

                <!-- إدارة التصنيفات -->
                <li class="{{ request()->is('admin/categories*') ? 'page-active' : '' }}">
                    <span class="side-menu-icon toggle-menu-icon">
                        <i class="la la-angle-down"></i>
                    </span>
                    <a href="{{ route('admin.categories.index') }}"><i
                            class="la la-sitemap me-2 text-color-7"></i>إدارة التصنيفات</a>
                    <ul class="toggle-drop-menu">
                        <li class="{{ request()->is('admin/categories') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.categories.index') }}">جميع التصنيفات</a>
                        </li>
                        <li class="{{ request()->is('admin/categories/create') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.categories.create') }}">إضافة تصنيف</a>
                        </li>
                    </ul>
                </li>

                <!-- المنتجات والمتغيرات -->
                <li
                    class="{{ request()->is('admin/products*') || request()->is('admin/product-*') ? 'page-active' : '' }}">
                    <span class="side-menu-icon toggle-menu-icon">
                        <i class="la la-angle-down"></i>
                    </span>
                    <a href="{{ route('admin.products.index') }}"><i class="la la-cube me-2 text-color-2"></i>المنتجات
                        والمتغيرات</a>
                    <ul class="toggle-drop-menu">
                        <li class="{{ request()->is('admin/products') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.products.index') }}">جميع المنتجات</a>
                        </li>
                        <li class="{{ request()->is('admin/products/create') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.products.create') }}">إضافة منتج</a>
                        </li>
                        <li class="{{ request()->is('admin/product-images') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.products.images.index') }}">صور المنتجات</a>
                        </li>
                        <li class="{{ request()->is('admin/product-variants') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.products.variants.index') }}">متغيرات المنتجات</a>
                        </li>
                    </ul>
                </li>

                <!-- التقييمات والمفضلة -->
                <li
                    class="{{ request()->is('admin/reviews*') || request()->is('admin/review-helpful*') || request()->is('admin/wishlists*') ? 'page-active' : '' }}">
                    <span class="side-menu-icon toggle-menu-icon">
                        <i class="la la-angle-down"></i>
                    </span>
                    <a href="#"><i class="la la-star me-2 text-color-4"></i>التقييمات والمفضلة</a>
                    <ul class="toggle-drop-menu">
                        <li class="{{ request()->is('admin/reviews') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.reviews.index') }}">جميع التقييمات</a>
                        </li>
                        <li class="{{ request()->is('admin/review-helpful') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.review-helpful.index') }}">التقييمات المفيدة</a>
                        </li>
                        <li class="{{ request()->is('admin/wishlists') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.wishlists.index') }}">قوائم المفضلة</a>
                        </li>
                    </ul>
                </li>


                <!-- الطلبات والعناوين -->
                <li
                    class="{{ request()->is('admin/orders*') || request()->is('admin/order-*') ? 'page-active' : '' }}">
                    <span class="side-menu-icon toggle-menu-icon">
                        <i class="la la-angle-down"></i>
                    </span>
                    <a href="{{ route('admin.orders.index') }}"><i
                            class="la la-shopping-cart me-2 text-color"></i>الطلبات والعناوين</a>
                    <ul class="toggle-drop-menu">
                        <li class="{{ request()->is('admin/orders') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.orders.index') }}">جميع الطلبات</a>
                        </li>
                        <li class="{{ request()->is('admin/orders/items') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.orders.items.index') }}">عناصر الطلبات</a>
                        </li>

                        <li class="{{ request()->is('admin/order-addresses') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.orders.addresses.index') }}">عناوين الطلبات</a>
                        </li>
                        <li class="{{ request()->is('admin/addresses') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.customer-addresses.index') }}">عناوين العملاء</a>
                        </li>
                    </ul>
                </li>

                <!-- المدفوعات والشحن -->
                <li
                    class="{{ request()->is('admin/payments*') || request()->is('admin/shipping*') ? 'page-active' : '' }}">
                    <span class="side-menu-icon toggle-menu-icon">
                        <i class="la la-angle-down"></i>
                    </span>
                    <a href="{{ route('admin.payment-options.index') }}"><i
                            class="la la-credit-card me-2 text-color-6"></i> المدفوعات والشحن</a>

                    <ul class="toggle-drop-menu">

                        <!-- خيارات الدفع -->
                        <li class="{{ request()->is('admin/payment-options') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.payment-options.index') }}">خيارات الدفع</a>
                        </li>

                        <!-- طرق الدفع للمحلات -->
                        <li class="{{ request()->is('admin/store-payment-methods') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.store-payment-methods.store') }}">طرق دفع المتاجر</a>
                        </li>

                        <!-- المدفوعات -->
                        <li class="{{ request()->is('admin/payments') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.payments.index') }}">المدفوعات</a>
                        </li>

                        <!-- إدارة الشحن -->
                        <li class="{{ request()->is('admin/shipping') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.shippings.index') }}">إدارة الشحن</a>
                        </li>
                    </ul>
                </li>


                <!-- الكوبونات والعروض -->
                <li class="{{ request()->is('admin/coupons*') ? 'page-active' : '' }}">
                    <span class="side-menu-icon toggle-menu-icon">
                        <i class="la la-angle-down"></i>
                    </span>
                    <a href="{{ route('admin.coupons.index') }}"><i
                            class="la la-ticket me-2 text-color-5"></i>الكوبونات والعروض</a>
                    <ul class="toggle-drop-menu">
                        <li class="{{ request()->is('admin/coupons') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.coupons.index') }}">جميع الكوبونات</a>
                        </li>
                        <li class="{{ request()->is('admin/coupons/create') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.coupons.create') }}">إضافة كوبون</a>
                        </li>
                        <li class="{{ request()->is('admin/coupon-usage') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.coupon-usage.index') }}">استخدام الكوبونات</a>
                        </li>
                    </ul>
                </li>

                <!-- الدعم والمراسلات -->
                <li
                    class="{{ request()->is('admin/support*') || request()->is('admin/ticket*') ? 'page-active' : '' }}">
                    <span class="side-menu-icon toggle-menu-icon">
                        <i class="la la-angle-down"></i>
                    </span>
                    <a href="{{ route('admin.support-tickets.index') }}"><i
                            class="la la-headset me-2 text-color-11"></i>الدعم والمراسلات</a>
                    <ul class="toggle-drop-menu">
                        <li class="{{ request()->is('admin/support-tickets') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.support-tickets.index') }}">تذاكر الدعم</a>
                        </li>
                        <li class="{{ request()->is('admin/ticket-messages') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.ticket-messages.index') }}">رسائل التذاكر</a>
                        </li>
                        <li class="{{ request()->is('admin/chats') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.chats.index') }}">المحادثات</a>
                        </li>
                        <li class="{{ request()->is('admin/messages') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.messages.index') }}">الرسائل</a>
                        </li>
                    </ul>
                </li>

                <!-- الإشعارات والإحصائيات -->
                <li
                    class="{{ request()->is('admin/notifications*') || request()->is('admin/statistics*') ? 'page-active' : '' }}">
                    <span class="side-menu-icon toggle-menu-icon">
                        <i class="la la-angle-down"></i>
                    </span>
                    <a href="{{ route('admin.notifications.index') }}"><i
                            class="la la-bell me-2 text-color-8"></i>الإشعارات والإحصائيات</a>
                    <ul class="toggle-drop-menu">
                        <li class="{{ request()->is('admin/notifications') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.notifications.index') }}">الإشعارات</a>
                        </li>
                        <li class="{{ request()->is('admin/statistics/sales') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.admin.statistics.sales') }}">إحصائيات المبيعات</a>
                        </li>
                        <li class="{{ request()->is('admin/statistics/users') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.statistics.users') }}">إحصائيات المستخدمين</a>
                        </li>
                        <li class="{{ request()->is('admin/statistics/products') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.statistics.products') }}">إحصائيات المنتجات</a>
                        </li>
                    </ul>
                </li>

                <!-- الإعدادات -->
                <li class="{{ request()->is('admin/settings*') ? 'page-active' : '' }}">
                    <a href="{{ route('admin.profile.edit') }}"><i
                            class="la la-cog me-2 text-color-9"></i>الإعدادات</a>
                </li>
                <!-- إدارة المحتوى الديناميكي -->
                <li class="{{ request()->is('admin/content*') || request()->is('admin/settings*') || request()->is('admin/brands*') || request()->is('admin/testimonials*') || request()->is('admin/footer*') || request()->is('admin/social*') ? 'page-active' : '' }}">
                    <span class="side-menu-icon toggle-menu-icon">
                        <i class="la la-angle-down"></i>
                    </span>
                    <a href="#"><i class="la la-globe me-2 text-color-3"></i>إدارة المحتوى</a>
                    <ul class="toggle-drop-menu">
                        <!-- الإعدادات العامة -->
                        <li class="{{ request()->is('admin/settings*') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.content.settings.index') }}"><i class="la la-cog me-2"></i>الإعدادات العامة</a>
                        </li>
                        
                        <!-- العلامات التجارية -->
                        <li class="{{ request()->is('admin/brands*') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.content.brands.index') }}"><i class="la la-tags me-2"></i>العلامات التجارية</a>
                        </li>
                        
                        <!-- آراء العملاء -->
                        <li class="{{ request()->is('admin/testimonials*') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.content.testimonials.index') }}"><i class="la la-comments me-2"></i>آراء العملاء</a>
                        </li>
                        
                        <!-- روابط الفوتر -->
                        <li class="{{ request()->is('admin/footer-links*') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.content.footer-links.index') }}"><i class="la la-link me-2"></i>روابط الفوتر</a>
                        </li>
                        
                        <!-- وسائل التواصل -->
                        <li class="{{ request()->is('admin/social-media*') ? 'page-active' : '' }}">
                            <a href="{{ route('admin.content.social-media.index') }}"><i class="la la-share-alt me-2"></i>وسائل التواصل</a>
                        </li>
                    </ul>
                </li>

                <!-- تسجيل الخروج -->
                <li>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); 
                if(confirm('هل أنت متأكد من تسجيل الخروج؟')) { 
                    document.getElementById('logout-form').submit(); 
                }">
                        <i class="la la-power-off me-2 text-color-12"></i>تسجيل خروج
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
