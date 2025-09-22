@extends('frontend.Seller.dashboard.index')

@section('title', 'الإشعارات')
@section('page_title', 'الإشعارات')

@section('css_sdebar')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body { font-family: 'Tajawal', sans-serif; background: #f5f7f9; }
    .notifications-container { display: flex; gap: 20px; margin-top: 20px; }
    .notifications-table, .notification-form { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); flex:1; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { padding: 10px; border-bottom: 1px solid #eee; text-align: left; }
    th { background: #f8f9fa; }
    .action-btn { cursor: pointer; margin-right: 5px; }
    .read-badge { padding: 3px 10px; border-radius: 20px; font-size: 0.8rem; color: white; }
    .read { background: green; }
    .unread { background: red; }
</style>
@endsection

@section('contects')
<br> <br>
<div class="dashboard-main-content">

    <div class="container-fluid py-4">
      
        <div class="notifications-container">
        @if (session('success'))
            <div id="flash-message" class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div id="flash-message" class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
            <!-- جدول الإشعارات -->
            <div class="notifications-table">
                <button id="markAllRead" class="btn btn-sm btn-success mb-2">تمييز الكل كمقروء</button>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>العنوان</th>
                            <th>المحتوى</th>
                            <th>المستخدم</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notifications as $notification)
                        <tr id="notif-{{ $notification->notification_id }}">
                            <td>{{ $notification->notification_id }}</td>
                            <td>{{ $notification->title }}</td>
                            <td>{{ $notification->content }}</td>
                            <td>{{ $notification->user ? $notification->user->name : 'الكل' }}</td>
                            <td>
                                <span class="read-badge {{ $notification->is_read ? 'read' : 'unread' }}">
                                    {{ $notification->is_read ? 'مقروء' : 'جديد' }}
                                </span>
                            </td>
                            <td>
                                <i class="fas fa-edit action-btn text-primary editNotification" data-id="{{ $notification->notification_id }}" style="cursor:pointer"></i>
                                <i class="fas fa-trash action-btn text-danger deleteNotification" data-id="{{ $notification->notification_id }}" style="cursor:pointer"></i>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $notifications->links() }}
            </div>

            <!-- نموذج إضافة/تعديل إشعار -->
            <div class="notification-form">
                <h5 id="formTitle">إضافة إشعار جديد</h5>
                <form id="notificationForm" method="POST" action="{{ route('seller.notifications.store') }}">
                    @csrf
                    <input type="hidden" name="notification_id" id="notification_id">
                    <div class="mb-3">
                        <label>العنوان</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>المحتوى</label>
                        <textarea name="content" id="content" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>المستخدمون</label>
                        <select name="users[]" id="users" class="form-control" multiple>
                            <option value="all">كل المستخدمين</option>
                            @foreach($users as $user)
                                <option value="{{ $user->user_id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        <small>اضغط Ctrl لاختيار أكثر من مستخدم</small>
                    </div>
                    <button type="submit" class="btn btn-primary" id="submitBtn">إرسال الإشعار</button>
                    <button type="button" class="btn btn-secondary" id="cancelEdit" style="display:none;">إلغاء التعديل</button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection

@section('script_sdebar')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // تمييز الكل كمقروء
    document.getElementById('markAllRead').addEventListener('click', function() {
        fetch("{{ route('seller.seller.notifications.markAllRead') }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        }).then(res => location.reload());
    });

    // تعديل إشعار
    document.querySelectorAll('.editNotification').forEach(btn => {
        btn.addEventListener('click', function() {
            let id = this.dataset.id;
            fetch("{{ url('admin/notifications') }}/" + id)
                .then(res => res.json())
                .then(data => {
                    // تعبئة الفورم
                    document.getElementById('formTitle').innerText = 'تعديل الإشعار';
                    document.getElementById('title').value = data.title;
                    document.getElementById('content').value = data.content;
                    document.getElementById('notification_id').value = data.notification_id;
                    document.getElementById('submitBtn').innerText = 'تحديث الإشعار';
                    document.getElementById('cancelEdit').style.display = 'inline-block';
                })
                .catch(err => console.error(err));
        });
    });

    // إلغاء التعديل
    document.getElementById('cancelEdit').addEventListener('click', function() {
        document.getElementById('notificationForm').reset();
        document.getElementById('formTitle').innerText = 'إضافة إشعار جديد';
        document.getElementById('submitBtn').innerText = 'إرسال الإشعار';
        this.style.display = 'none';
        document.getElementById('notification_id').value = '';
    });

    // حذف إشعار
    document.querySelectorAll('.deleteNotification').forEach(btn => {
        btn.addEventListener('click', function() {
            if(confirm('هل أنت متأكد من حذف هذا الإشعار؟')) {
                let id = this.dataset.id;
                fetch("{{ url('admin/notifications') }}/" + id, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                }).then(res => document.getElementById('notif-' + id).remove());
            }
        });
    });

});
</script>
    <script>
        setTimeout(function() {
            const flash = document.getElementById('flash-message');
            if(flash) {
                flash.style.transition = "opacity 0.5s ease";
                flash.style.opacity = '0';
                setTimeout(() => flash.remove(), 500);
            }
        }, 3000);
    </script>

@endsection

