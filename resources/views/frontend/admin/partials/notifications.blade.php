<div class="notification-item me-2">
    <div class="dropdown">
        <a href="#" class="dropdown-toggle drop-reveal-toggle-icon" id="notificationDropdownMenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="la la-bell"></i>
            <span class="noti-count">{{ $unreadCount ?? 0 }}</span>
        </a>
        <div class="dropdown-menu dropdown-reveal dropdown-menu-xl dropdown-menu-right">
            <div class="dropdown-header drop-reveal-header">
                <h6 class="title">لديك <strong class="text-black">{{ $unreadCount ?? 0 }}</strong> إشعارات</h6>
            </div>
            <div class="list-group drop-reveal-list">
                @forelse($unreadNotifications as $notification)
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="msg-body d-flex align-items-center">
                            <div class="icon-element flex-shrink-0 me-3 ms-0">
                                <i class="la la-bell"></i>
                            </div>
                            <div class="msg-content w-100">
                                <h3 class="title pb-1">{{ $notification->title }}</h3>
                                <p class="msg-text">{{ $notification->content }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-center p-2">لا توجد إشعارات جديدة</p>
                @endforelse
            </div>
            <a href="
    @if(auth()->user()->user_type == 1)
        {{ route('seller.notifications.index') }}
    @elseif(auth()->user()->user_type == 2)
        {{ route('admin.notifications.index') }}
    @else
        {{ route('home') }}
    @endif
" class="dropdown-item drop-reveal-btn text-center">
    مشاهدة الكل
</a>

        </div>
    </div>
</div>
