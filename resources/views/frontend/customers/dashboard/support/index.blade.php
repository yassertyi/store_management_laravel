@extends('frontend.customers.dashboard.index')

@section('title', 'الدعم الفني')
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
                <h3 class="title">التذاكر الخاصة بي</h3>
                <a href="{{ route('customer.support.tickets.create') }}" class="theme-btn theme-btn-small">
                    <i class="la la-plus"></i> تذكرة جديدة
                </a>
            </div>

            <div class="form-content">
                <div class="table-form table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>الموضوع</th>
                                <th>الأولوية</th>
                                <th>الحالة</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                                <tr>
                                    <td>{{ $loop->iteration + ($tickets->currentPage() - 1) * $tickets->perPage() }}</td>
                                    <td>{{ $ticket->subject }}</td>
                                    <td>
                                        @if($ticket->priority == 3)
                                            <span class="badge bg-danger">عالية</span>
                                        @elseif($ticket->priority == 2)
                                            <span class="badge bg-warning text-dark">متوسطة</span>
                                        @else
                                            <span class="badge bg-secondary">منخفضة</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ticket->status == 0)
                                            <span class="badge bg-success">مفتوحة</span>
                                        @elseif($ticket->status == 1)
                                            <span class="badge bg-warning text-dark">قيد المراجعة</span>
                                        @else
                                            <span class="badge bg-dark">مغلقة</span>
                                        @endif
                                    </td>
                                    <td>{{ $ticket->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route('customer.support.tickets.show', $ticket->ticket_id) }}" 
                                           class="theme-btn theme-btn-small me-2">
                                            <i class="la la-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="la la-inbox la-3x text-muted mb-2"></i>
                                        <p>لا توجد تذاكر حالياً</p>
                                        <a href="{{ route('customer.support.tickets.create') }}" class="theme-btn theme-btn-small">
                                            إنشاء أول تذكرة
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $tickets->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection