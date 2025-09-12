@extends('frontend.admin.dashboard.index')

@section('title', 'الرسائل')
@section('page_title', 'إدارة الرسائل')

@section('contects')
<br><br><br>
<div class="dashboard-main-content">
    <div class="container-fluid">

        @if (session('success'))
            <div id="flash-message" class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        
        <div class="form-box">
            <div class="form-title-wrap d-flex justify-content-between align-items-center">
                <h3 class="title">قائمة الرسائل</h3>
                <a href="{{ route('admin.messages.create') }}" class="theme-btn theme-btn-small">
                    <i class="la la-plus"></i> رسالة جديدة
                </a>
            </div>

            <div class="form-content">
                <div class="table-form table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>المرسل</th>
                                <th>المستلم</th>
                                <th>الموضوع</th>
                                <th>الحالة</th>
                                <th>تاريخ الإرسال</th>
                                <th>التحكم</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($messages as $message)
                                <tr>
                                    <td>{{ $loop->iteration + ($messages->currentPage() - 1) * $messages->perPage() }}</td>
                                    <td>{{ $message->sender->name ?? 'غير محدد' }}</td>
                                    <td>{{ $message->receiver->name ?? 'غير محدد' }}</td>
                                    <td>{{ $message->subject }}</td>
                                    <td>
                                        @if ($message->is_read)
                                            <span class="badge text-bg-success">مقروءة</span>
                                        @else
                                            <span class="badge text-bg-warning">غير مقروءة</span>
                                        @endif
                                    </td>
                                    <td>{{ $message->created_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        {{-- رابط التعديل --}}
                                        <a href="{{ route('admin.messages.edit', ['message' => $message->message_id]) }}"
                                           class="theme-btn theme-btn-small me-2">
                                           <i class="la la-edit"></i>
                                        </a>

                                        {{-- رابط الحذف --}}
                                        <form action="{{ route('admin.messages.destroy', ['message' => $message->message_id]) }}"
                                              method="POST" class="d-inline" 
                                              onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
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
                    {{-- روابط الباجينيشن --}}
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
        if (flash) {
            flash.style.transition = "opacity 0.5s ease";
            flash.style.opacity = '0';
            setTimeout(() => flash.remove(), 500);
        }
    }, 3000);
</script>
@endsection
