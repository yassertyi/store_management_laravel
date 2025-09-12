@extends('frontend.admin.dashboard.index')

@section('title', 'تفاصيل التذكرة')
@section('page_title', 'تفاصيل التذكرة')

@section('contects')
    <br><br><br>
    <div class="dashboard-main-content">
        <div class="container-fluid">

            <div class="form-box">
                <div class="form-title-wrap">
                    <h3 class="title">تفاصيل التذكرة رقم #{{ $ticket->ticket_id }}</h3>
                </div>

                <div class="form-content">
                    <table class="table table-bordered">
                        <tr>
                            <th>المستخدم</th>
                            <td>{{ optional($ticket->customer->user)->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>الموضوع</th>
                            <td>{{ $ticket->subject }}</td>
                        </tr>
                        <tr>
                            <th>الوصف</th>
                            <td>{{ $ticket->description }}</td>
                        </tr>
                        <tr>
                            <th>الحالة</th>
                            <td>
                                @if($ticket->status == 0)
                                    <span class="badge text-bg-success">مفتوحة</span>
                                @elseif($ticket->status == 1)
                                    <span class="badge text-bg-warning">قيد التنفيذ</span>
                                @else
                                    <span class="badge text-bg-danger">مغلقة</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>الأولوية</th>
                            <td>
                                @if($ticket->priority == 0)
                                    <span class="badge text-bg-danger">عالية</span>
                                @elseif($ticket->priority == 1)
                                    <span class="badge text-bg-warning">متوسطة</span>
                                @else
                                    <span class="badge text-bg-secondary">منخفضة</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>المسؤول</th>
                            <td>{{ optional($ticket->assignedTo)->name ?? '-' }}</td>
                        </tr>
                    </table>

                    <h5 class="mt-4">الرسائل</h5>
                    <ul class="list-group">
                        @foreach($ticket->messages as $msg)
                            <li class="list-group-item">
                                <strong>{{ optional($msg->user)->name ?? 'ضيف' }}:</strong> {{ $msg->message }}
                                <br>
                                <small class="text-muted">{{ $msg->created_at->format('Y-m-d H:i') }}</small>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-3">
                        <a href="{{ route('admin.support-tickets.index') }}" class="theme-btn theme-btn-small bg-secondary">
                            <i class="la la-arrow-left"></i> العودة للقائمة
                        </a>
                        <a href="{{ route('admin.support-tickets.edit', $ticket->ticket_id) }}" class="theme-btn theme-btn-small">
                            <i class="la la-edit"></i> تعديل التذكرة
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
