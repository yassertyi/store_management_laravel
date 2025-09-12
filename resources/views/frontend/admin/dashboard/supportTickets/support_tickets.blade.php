@extends('frontend.admin.dashboard.index')

@section('title', 'قائمة تذاكر الدعم')
@section('page_title', 'قائمة التذاكر')

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

            <div class="form-box">
                <div class="form-title-wrap d-flex justify-content-between align-items-center">
                    <h3 class="title">قائمة تذاكر الدعم</h3>
                    <a href="{{ route('admin.support-tickets.create') }}" class="theme-btn theme-btn-small">
                        <i class="la la-plus"></i> تذكرة جديدة
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
                                    <th>الأولوية</th>
                                    <th>المسؤول</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tickets as $ticket)
                                    <tr>
                                        <td>{{ $loop->iteration + ($tickets->currentPage() - 1) * $tickets->perPage() }}</td>
                                        <td>{{ optional($ticket->customer->user)->name ?? 'غير محدد' }}</td>
                                        <td>{{ $ticket->subject }}</td>
                                        <td>
                                            @if($ticket->status == 0)
                                                <span class="badge text-bg-success">مفتوحة</span>
                                            @elseif($ticket->status == 1)
                                                <span class="badge text-bg-warning">قيد التنفيذ</span>
                                            @else
                                                <span class="badge text-bg-danger">مغلقة</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($ticket->priority == 0)
                                                <span class="badge text-bg-danger">عالية</span>
                                            @elseif($ticket->priority == 1)
                                                <span class="badge text-bg-warning">متوسطة</span>
                                            @else
                                                <span class="badge text-bg-secondary">منخفضة</span>
                                            @endif
                                        </td>
                                        <td>{{ optional($ticket->assignedTo)->name ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('admin.support-tickets.show', $ticket->ticket_id) }}" class="theme-btn theme-btn-small me-2">
                                                <i class="la la-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.support-tickets.edit', $ticket->ticket_id) }}" class="theme-btn theme-btn-small me-2">
                                                <i class="la la-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.support-tickets.destroy', $ticket->ticket_id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل تريد حذف التذكرة؟');">
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

                        {{ $tickets->links() }}
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
