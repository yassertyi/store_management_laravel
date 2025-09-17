@extends('frontend.admin.dashboard.index')

@section('page_title', 'إدارة طلبات فتح حساب متجر')
@section('title', 'طلبات فتح حساب متجر')

@section('contects')
<br><br><br>

<div class="dashboard-main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-box">
                    <div class="form-title-wrap d-flex justify-content-between align-items-center">
                        <h3 class="title">قائمة الطلبات</h3>
                    </div>

                    <div class="form-content pb-2">
                        @foreach ($requests as $request)
                            <div class="card-item card-item-list">
                                <div class="card-img">
                                   <img src="{{ asset($request->profile_photo ?: 'static/images/thrifty.png') }}" alt="{{ $request->name }}" />

                                </div>

                                <div class="card-body">
                                    <h3 class="card-title">{{ $request->name }}</h3>
                                    <ul class="list-items list-items-2 pt-2 pb-3">
                                        <li><span>البريد الإلكتروني:</span> {{ $request->email }}</li>
                                        <li><span>الهاتف:</span> {{ $request->phone_number ?? $request->phone }}</li>
                                        <li><span>اسم المتجر:</span> {{ $request->store_name }}</li>
                                        <li><span>حالة الطلب:</span> 
                                            @if($request->status == 'pending')
                                                <span class="badge bg-warning">قيد الانتظار</span>
                                            @elseif($request->status == 'approved')
                                                <span class="badge bg-success">وافق</span>
                                            @elseif($request->status == 'rejected')
                                                <span class="badge bg-danger">مرفوض</span>
                                            @endif
                                        </li>
                                    </ul>
                                    <div class="btn-box">
                                        <a href="{{ route('admin.seller-requests.show', $request->request_id) }}" 
                                           class="theme-btn theme-btn-small theme-btn-transparent">
                                           <i class="la la-eye me-1"></i>عرض التفاصيل
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-3">
                        {{ $requests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
