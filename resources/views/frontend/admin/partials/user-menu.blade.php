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
            <div class="dropdown-item drop-reveal-header user-reveal-header">
                <h6 class="title text-uppercase">أهلا بك, {{ Auth::user()->name }}!</h6>
            </div>

            <div class="list-group drop-reveal-list user-drop-reveal-list">
                <a href="{{ route('admin.profile.edit') }}" class="list-group-item list-group-item-action">
                    <div class="msg-body">
                        <div class="msg-content">
                            <h3 class="title"><i class="la la-user me-2"></i> تعديل الملف الشخصي</h3>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.orders.index') }}" class="list-group-item list-group-item-action">
                    <div class="msg-body">
                        <div class="msg-content">
                            <h3 class="title"><i class="la la-shopping-cart me-2"></i> الطلبات</h3>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.profile.edit') }}" class="list-group-item list-group-item-action">
                    <div class="msg-body">
                        <div class="msg-content">
                            <h3 class="title"><i class="la la-cog me-2"></i> الإعدادات</h3>
                        </div>
                    </div>
                </a>

                <div class="section-block"></div>

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
