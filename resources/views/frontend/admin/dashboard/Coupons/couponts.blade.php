@extends('frontend.admin.dashboard.index')

@section('title')
الكوبونات
@endsection

@section('page_title')
إدارة الكوبونات
@endsection

@section('contects')
<br><br><br>
<div class="dashboard-main-content">
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-box">
                    <div class="form-title-wrap d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="title">قائمة الكوبونات</h3>
                            <p class="font-size-14">
                                إظهار {{ $coupons->firstItem() }} إلى {{ $coupons->lastItem() }} من أصل {{ $coupons->total() }} مُدخل
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('admin.coupons.create') }}" class="theme-btn theme-btn-small">
                                <i class="la la-plus"></i> كوبون جديد
                            </a>
                        </div>
                    </div>

                    <div class="form-content">
                        <div class="table-form table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>الرقم</th>
                                        <th>الكود</th>
                                        <th>نوع الخصم</th>
                                        <th>قيمة الخصم</th>
                                        <th>تاريخ البداية</th>
                                        <th>تاريخ الانتهاء</th>
                                        <th>الحالة</th>
                                        <th>عمليات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($coupons as $coupon)
                                    <tr>
                                        <td>{{ $coupon->coupon_id }}</td>
                                        <td><strong>{{ $coupon->code }}</strong></td>
                                        <td>
                                            @if($coupon->discount_type === 'percentage')
                                                <span class="badge text-bg-info">نسبة %</span>
                                            @else
                                                <span class="badge text-bg-primary">مبلغ ثابت</span>
                                            @endif
                                        </td>
                                        <td>{{ $coupon->discount_value }}</td>
                                        <td>{{ $coupon->start_date->format('Y-m-d') }}</td>
                                        <td>{{ $coupon->expiry_date->format('Y-m-d') }}</td>
                                        <td>
                                            @if($coupon->is_active)
                                                <span class="badge text-bg-success">نشط</span>
                                            @else
                                                <span class="badge text-bg-danger">غير نشط</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('admin.coupons.edit', $coupon->coupon_id) }}" 
                                                   class="theme-btn theme-btn-small me-2" title="تعديل">
                                                    <i class="la la-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.coupons.destroy', $coupon->coupon_id) }}" method="POST" 
                                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا الكوبون؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="theme-btn theme-btn-small bg-danger" title="حذف">
                                                        <i class="la la-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="row">
                    <div class="col-lg-12">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <li class="page-item {{ $coupons->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $coupons->previousPageUrl() }}">
                                        <i class="la la-angle-left"></i>
                                    </a>
                                </li>
                                @for ($i = 1; $i <= $coupons->lastPage(); $i++)
                                    <li class="page-item {{ $coupons->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $coupons->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                <li class="page-item {{ !$coupons->hasMorePages() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $coupons->nextPageUrl() }}">
                                        <i class="la la-angle-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
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