@extends('frontend.admin.dashboard.index')

@section('title', 'قائمة رسائل التذاكر')
@section('page_title', 'قائمة الرسائل')

@section('contects')
    <br><br><br>
    <div class="dashboard-main-content">
        <div class="container-fluid">

            @if(session('success'))
                <div id="flash-message" class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div id="flash-message" class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="form-box">
                <div class="form-title-wrap d-flex justify-content-between align-items-center">
                    <h3 class="title">قائمة رسائل التذاكر</h3>
                    <a href="{{ route('admin.ticket-messages.create') }}" class="theme-btn theme-btn-small">
                        <i class="la la-plus"></i> رسالة جديدة
                    </a>
                </div>

                <div class="form-content">
                    <div class="table-form table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>التذكرة</th>
                                    <th>المستخدم</th>
                                    <th>الرسالة</th>
                                    <th>الحالة</th>
                                    <th>التاريخ</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($messages as $msg)
                                    <tr>
                                        <td>{{ $loop->iteration + ($messages->currentPage() - 1) * $messages->perPage() }}</td>
                                        <td>{{ $msg->ticket->subject ?? '-' }}</td>
                                        <td>{{ $msg->user->name ?? 'غير محدد' }}</td>
                                        <td>{{ Str::limit($msg->message, 50) }}</td>
                                        <td>
                                            @if($msg->is_read == 0)
                                                <span class="badge text-bg-warning">غير مقروءة</span>
                                            @else
                                                <span class="badge text-bg-success">مقروءة</span>
                                            @endif
                                        </td>
                                        <td>{{ $msg->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <a href="{{ route('admin.ticket-messages.edit', $msg->message_id) }}" class="theme-btn theme-btn-small me-2">
                                                <i class="la la-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.ticket-messages.destroy', $msg->message_id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل تريد حذف الرسالة؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="theme-btn theme-btn-small bg-danger">
                                                    <i class="la la-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">لا توجد رسائل</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{ $messages->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script_sdebar')
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
