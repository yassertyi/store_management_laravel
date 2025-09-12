@extends('frontend.admin.dashboard.index')

@section('title', 'المحادثات')
@section('page_title', 'إدارة المحادثات')

@section('contects')
<div class="dashboard-main-content">
    <br><br><br>
    <div class="container-fluid">

        @if(session('success'))
            <div id="flash-message" class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="form-box">
            <div class="form-title-wrap d-flex justify-content-between align-items-center">
                <h3 class="title">قائمة المحادثات</h3>
                <a href="{{ route('admin.chats.create') }}" class="theme-btn theme-btn-small">
                    <i class="la la-plus"></i> محادثة جديدة
                </a>
            </div>

            <div class="form-content">
                <div class="table-form table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>المستخدم</th>
                                <th>الموضوع</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($chats as $chat)
                                <tr>
                                    <td>{{ $loop->iteration + ($chats->currentPage() - 1) * $chats->perPage() }}</td>
                                    <td>{{ $chat->user->name ?? 'ضيف' }}</td>
                                    <td>{{ $chat->subject ?? '-' }}</td>
                                    <td>
                                        @if($chat->status == 'open')
                                            <span class="badge text-bg-success">مفتوحة</span>
                                        @else
                                            <span class="badge text-bg-danger">مغلقة</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.chats.show', $chat->chat_id) }}" class="theme-btn theme-btn-small me-2">
                                            <i class="la la-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.chats.destroy', $chat->chat_id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل تريد حذف المحادثة؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="theme-btn theme-btn-small bg-danger">
                                                <i class="la la-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $chats->links() }}
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
        if(flash){
            flash.style.transition = "opacity 0.5s ease";
            flash.style.opacity = '0';
            setTimeout(() => flash.remove(), 500);
        }
    }, 3000);
</script>
@endsection
