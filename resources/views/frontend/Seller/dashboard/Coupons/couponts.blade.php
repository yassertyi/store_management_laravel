@extends('frontend.Seller.dashboard.index')

@section('title', 'الكوبونات')
@section('page_title', 'إدارة الكوبونات')

@section('contects')
<div class="dashboard-main-content">
    <br><br><br>
    <div class="container-fluid">

        {{-- رسائل نجاح / خطأ --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

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
                            <a href="{{ route('seller.coupons.create') }}" class="theme-btn theme-btn-small">
                                <i class="la la-plus"></i> كوبون جديد
                            </a>
                        </div>
                    </div>

                    <div class="form-content">
                        <div class="table-form table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
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
                                    @forelse($coupons as $coupon)
                                    <tr>
                                        <td>{{ $coupon->coupon_id }}</td>
                                        <td><strong>{{ $coupon->code }}</strong></td>
                                        <td>
                                            @if($coupon->discount_type === 'percentage')
                                                <span class="badge bg-info">نسبة %</span>
                                            @else
                                                <span class="badge bg-primary">مبلغ ثابت</span>
                                            @endif
                                        </td>
                                        <td>{{ $coupon->discount_value }}</td>
                                        <td>{{ $coupon->start_date->format('Y-m-d') }}</td>
                                        <td>{{ $coupon->expiry_date->format('Y-m-d') }}</td>
                                        <td>
                                            @if($coupon->is_active)
                                                <span class="badge bg-success">نشط</span>
                                            @else
                                                <span class="badge bg-danger">غير نشط</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('seller.coupons.edit', $coupon->coupon_id) }}" 
                                                   class="theme-btn theme-btn-small me-2">
                                                    <i class="la la-edit"></i>
                                                </a>
                                                <form action="{{ route('seller.coupons.destroy', $coupon->coupon_id) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="theme-btn theme-btn-small bg-danger">
                                                        <i class="la la-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">لا توجد كوبونات بعد</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Pagination --}}
                    <div class="row">
                        <div class="col-lg-12">
                            <nav aria-label="Page navigation">
                                {{ $coupons->links('pagination::bootstrap-5') }}
                            </nav>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
