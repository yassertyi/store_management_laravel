<div class="notification-item">
    <div class="dropdown">
        <a href="#" class="dropdown-toggle" id="userDropdownMenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div class="d-flex align-items-center">
                <div class="avatar avatar-sm flex-shrink-0 me-2">
                    <img src="{{ Auth::user()->profile_photo ? asset(Auth::user()->profile_photo) : asset('static/images/Default_pfp.jpg') }}" 
                         alt="{{ Auth::user()->name }}" class="rounded-circle" width="40" height="40"/>
                </div>
                <span class="font-size-14 font-weight-bold">{{ Auth::user()->name }}</span>
            </div>
        </a>

        <div class="dropdown-menu dropdown-reveal dropdown-menu-md dropdown-menu-right">
            <!-- الترحيب -->
            <div class="dropdown-item drop-reveal-header user-reveal-header">
                <h6 class="title text-uppercase">أهلا بك, {{ Auth::user()->name }}!</h6>
            </div>

            <div class="list-group drop-reveal-list user-drop-reveal-list">
                <!-- 🔹 لوحة التحكم حسب نوع المستخدم -->
                @php
                    $userType = Auth::user()->user_type ?? null; // 1 = بائع , 2 = أدمن
                @endphp

                @if($userType == 2)
                    <!-- أدمن -->
                    <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action">
                        <div class="msg-body">
                            <div class="msg-content">
                                <h3 class="title"><i class="la la-tachometer-alt me-2"></i> لوحة التحكم (أدمن)</h3>
                            </div>
                        </div>
                    </a>
                @elseif($userType == 1)
                    <!-- بائع -->
                    <a href="{{ route('seller.dashboard') }}" class="list-group-item list-group-item-action">
                        <div class="msg-body">
                            <div class="msg-content">
                                <h3 class="title"><i class="la la-store me-2"></i> لوحة التحكم (بائع)</h3>
                            </div>
                        </div>
                    </a>
                @endif

                <!-- تعديل الملف الشخصي -->
                <a href="{{ $userType == 2 ? route('admin.profile.edit') : route('seller.profile.edit') }}" 
                   class="list-group-item list-group-item-action">
                    <div class="msg-body">
                        <div class="msg-content">
                            <h3 class="title"><i class="la la-user me-2"></i> تعديل الملف الشخصي</h3>
                        </div>
                    </div>
                </a>

                <!-- الطلبات -->
                <a href="{{ $userType == 2 ? route('admin.orders.index') : route('seller.orders.index') }}" 
                   class="list-group-item list-group-item-action">
                    <div class="msg-body">
                        <div class="msg-content">
                            <h3 class="title"><i class="la la-shopping-cart me-2"></i> الطلبات</h3>
                        </div>
                    </div>
                </a>

                <!-- الإعدادات -->
                <a href="{{ $userType == 2 ? route('admin.profile.edit') : route('seller.profile.edit') }}" 
                   class="list-group-item list-group-item-action">
                    <div class="msg-body">
                        <div class="msg-content">
                            <h3 class="title"><i class="la la-cog me-2"></i> الإعدادات</h3>
                        </div>
                    </div>
                </a>

                <div class="section-block"></div>

                <!-- تسجيل الخروج -->
                <a href="{{ route('logout') }}" class="list-group-item list-group-item-action"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <div class="msg-body">
                        <div class="msg-content">
                            <h3 class="title"><i class="la la-power-off me-2"></i> تسجيل خروج</h3>
                        </div>
                    </div>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
