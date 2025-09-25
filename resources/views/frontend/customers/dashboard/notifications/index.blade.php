@extends('frontend.customers.dashboard.index')

@section('title', 'الإشعارات')
@section('page_title', 'إدارة الإشعارات')

@section('css_sdebar')
<style>
:root {
    --primary-color: #4361ee;
    --success-color: #28a745;
    --warning-color: #ffc107;
    --danger-color: #dc3545;
    --info-color: #17a2b8;
}

.notification-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    margin-bottom: 15px;
    border-right: 4px solid transparent;
}

.notification-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.12);
}

.notification-card.unread {
    background: #f8f9ff;
    border-right-color: var(--primary-color);
}

.notification-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    margin-left: 15px;
}

.icon-order { background: linear-gradient(45deg, var(--primary-color), #3a56e4); }
.icon-support { background: linear-gradient(45deg, var(--info-color), #138496); }
.icon-promo { background: linear-gradient(45deg, var(--success-color), #218838); }
.icon-system { background: linear-gradient(45deg, #6c757d, #5a6268); }
.icon-review { background: linear-gradient(45deg, var(--warning-color), #e0a800); }

.notification-badge {
    position: absolute;
    top: -5px;
    left: -5px;
    background: var(--danger-color);
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 0.7rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.filter-badge {
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-badge.active {
    background: var(--primary-color) !important;
    color: white !important;
}

.notification-actions {
    opacity: 0;
    transition: opacity 0.3s ease;
}

.notification-card:hover .notification-actions {
    opacity: 1;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.5;
}
</style>
@endsection

@section('contects')
<br><br><br>
<div class="dashboard-main-content">
    <div class="container-fluid">
        <br><br>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- رأس الصفحة مع الإحصائيات -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="title">الإشعارات</h3>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" id="markAllReadBtn">
                            <i class="la la-check-double me-1"></i> تمييز الكل كمقروء
                        </button>
                        <button class="btn btn-outline-danger" id="clearReadBtn">
                            <i class="la la-trash me-1"></i> حذف المقروء
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- فلترة الإشعارات -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-3 align-items-center">
                            <span class="fw-bold">الفلاتر:</span>
                            
                            <!-- فلترة حسب النوع -->
                            <div class="btn-group" role="group">
                                <a href="{{ request()->fullUrlWithQuery(['type' => 'all']) }}" 
                                   class="btn btn-outline-secondary filter-badge {{ !request('type') || request('type') == 'all' ? 'active' : '' }}">
                                    الكل
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['type' => 'order']) }}" 
                                   class="btn btn-outline-secondary filter-badge {{ request('type') == 'order' ? 'active' : '' }}">
                                    الطلبات
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['type' => 'support']) }}" 
                                   class="btn btn-outline-secondary filter-badge {{ request('type') == 'support' ? 'active' : '' }}">
                                    الدعم
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['type' => 'promotion']) }}" 
                                   class="btn btn-outline-secondary filter-badge {{ request('type') == 'promotion' ? 'active' : '' }}">
                                    العروض
                                </a>
                            </div>

                            <!-- فلترة حسب حالة القراءة -->
                            <div class="btn-group" role="group">
                                <a href="{{ request()->fullUrlWithQuery(['read_status' => 'all']) }}" 
                                   class="btn btn-outline-secondary filter-badge {{ !request('read_status') || request('read_status') == 'all' ? 'active' : '' }}">
                                    جميع الإشعارات
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['read_status' => 'unread']) }}" 
                                   class="btn btn-outline-secondary filter-badge {{ request('read_status') == 'unread' ? 'active' : '' }}">
                                    غير المقروءة
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['read_status' => 'read']) }}" 
                                   class="btn btn-outline-secondary filter-badge {{ request('read_status') == 'read' ? 'active' : '' }}">
                                    المقروءة
                                </a>
                            </div>

                            <!-- إحصائيات سريعة -->
                            <div class="ms-auto d-flex gap-3 text-muted">
                                <small>الإجمالي: <strong>{{ $notifications->total() }}</strong></small>
                                <small>غير مقروء: <strong class="text-danger">{{ $unreadCount }}</strong></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- قائمة الإشعارات -->
        <div class="row">
            <div class="col-12">
                @if($notifications->count() > 0)
                    <div id="notificationsList">
                        @foreach($notifications as $notification)
                            <div class="card notification-card {{ !$notification->is_read ? 'unread' : '' }}" 
                                 id="notification-{{ $notification->notification_id }}">
                                <div class="card-body">
                                    <div class="d-flex align-items-start">
                                        <!-- أيقونة الإشعار -->
                                        <div class="position-relative">
                                            <div class="notification-icon text-white 
                                                @if($notification->type == 'order') icon-order
                                                @elseif($notification->type == 'support') icon-support
                                                @elseif($notification->type == 'promotion') icon-promo
                                                @elseif($notification->type == 'review') icon-review
                                                @else icon-system @endif">
                                                @if($notification->type == 'order') <i class="la la-shopping-cart"></i>
                                                @elseif($notification->type == 'support') <i class="la la-headset"></i>
                                                @elseif($notification->type == 'promotion') <i class="la la-gift"></i>
                                                @elseif($notification->type == 'review') <i class="la la-star"></i>
                                                @else <i class="la la-bell"></i> @endif
                                            </div>
                                            @if(!$notification->is_read)
                                                <span class="notification-badge">!</span>
                                            @endif
                                        </div>

                                        <!-- محتوى الإشعار -->
                                        <div class="flex-grow-1 ms-3">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h6 class="card-title mb-1">{{ $notification->title }}</h6>
                                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="card-text mb-2">{{ $notification->content }}</p>
                                            
                                            @if($notification->related_id && $notification->related_type)
                                                <small class="text-primary">
                                                    <i class="la la-link me-1"></i>
                                                    @if($notification->related_type == 'order')
                                                        <a href="{{ route('customer.orders.show', $notification->related_id) }}">عرض الطلب</a>
                                                    @elseif($notification->related_type == 'support_ticket')
                                                        <a href="{{ route('customer.support.tickets.show', $notification->related_id) }}">عرض التذكرة</a>
                                                    @endif
                                                </small>
                                            @endif
                                        </div>

                                        <!-- أزرار الإجراءات -->
                                        <div class="notification-actions">
                                            @if(!$notification->is_read)
                                                <button class="btn btn-sm btn-outline-success mark-read-btn" 
                                                        data-id="{{ $notification->notification_id }}"
                                                        title="تمييز كمقروء">
                                                    <i class="la la-check"></i>
                                                </button>
                                            @endif
                                            <button class="btn btn-sm btn-outline-danger delete-notification-btn" 
                                                    data-id="{{ $notification->notification_id }}"
                                                    title="حذف الإشعار">
                                                <i class="la la-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- الترقيم -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $notifications->links() }}
                    </div>

                @else
                    <!-- حالة عدم وجود إشعارات -->
                    <div class="empty-state">
                        <i class="la la-bell-slash"></i>
                        <h4>لا توجد إشعارات</h4>
                        <p class="text-muted">ليس لديك أي إشعارات لعرضها حالياً</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

@section('script_sdebar')
<script>
$(document).ready(function() {
    // تحديث عدد الإشعارات غير المقروءة
    function updateUnreadCount() {
        $.get('{{ route("customer.notifications.unreadCount") }}', function(data) {
            $('#unreadCountBadge').text(data.count > 0 ? data.count : '');
        });
    }

    // تمييز إشعار كمقروء
    $('.mark-read-btn').click(function() {
        const notificationId = $(this).data('id');
        const notificationCard = $('#notification-' + notificationId);

        $.post('/customer/notifications/' + notificationId + '/read', {
            _token: '{{ csrf_token() }}'
        }, function(response) {
            if (response.success) {
                notificationCard.removeClass('unread');
                notificationCard.find('.notification-badge').remove();
                $(this).remove();
                updateUnreadCount();
                showToast('success', response.message);
            }
        });
    });

    // تمييز الكل كمقروء
    $('#markAllReadBtn').click(function() {
        if (confirm('هل تريد تمييز جميع الإشعارات كمقروءة؟')) {
            $.post('{{ route("customer.notifications.markAllRead") }}', {
                _token: '{{ csrf_token() }}'
            }, function(response) {
                if (response.success) {
                    $('.notification-card').removeClass('unread');
                    $('.notification-badge').remove();
                    $('.mark-read-btn').remove();
                    updateUnreadCount();
                    showToast('success', response.message);
                }
            });
        }
    });

    // حذف إشعار مع عمل Refresh
    $('.delete-notification-btn').click(function() {
        const notificationId = $(this).data('id');
        
        if (confirm('هل تريد حذف هذا الإشعار؟')) {
            $.ajax({
                url: '{{ url("customer/notifications") }}/' + notificationId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    showToast('success', 'تم حذف الإشعار بنجاح');
                    // إعادة تحميل الصفحة بعد 300ms
                    setTimeout(function() {
                        location.reload();
                    }, 300);
                }
            });
        }
    });

    // حذف الإشعارات المقروءة مع Refresh
    $('#clearReadBtn').click(function() {
        if (confirm('هل تريد حذف جميع الإشعارات المقروءة؟')) {
            $.ajax({
                url: '{{ route("customer.notifications.clearRead") }}',
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    showToast('success', response.message || 'تم الحذف بنجاح');
                    setTimeout(function() {
                        location.reload();
                    }, 300);
                }
            });
        }
    });

    // دالة لعرض الإشعارات
    function showToast(type, message) {
        const toast = `<div class="alert alert-${type} alert-dismissible fade show position-fixed" 
                      style="top: 20px; right: 20px; z-index: 9999;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
        $('body').append(toast);
        setTimeout(() => $('.alert').alert('close'), 3000);
    }

    // تحديث العد كل 30 ثانية
    setInterval(updateUnreadCount, 30000);
    updateUnreadCount(); // التحميل الأولي
});
</script>
@endsection
