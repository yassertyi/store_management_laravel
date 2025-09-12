@extends('frontend.admin.dashboard.index')

@section('title')
الشحنات
@endsection

@section('page_title')
ادارة الشحنات
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
                            <h3 class="title">قائمة الشحنات</h3>
                            <p class="font-size-14">
                                إظهار {{ $shippings->firstItem() }} إلى {{ $shippings->lastItem() }} من أصل {{ $shippings->total() }} مُدخل
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('admin.shippings.create') }}" class="theme-btn theme-btn-small">
                                <i class="la la-plus"></i> شحنة جديدة
                            </a>
                        </div>
                    </div>

                    <div class="form-content">
                        <div class="table-form table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>رقم الشحنة</th>
                                        <th>رقم الطلب</th>
                                        <th>شركة النقل</th>
                                        <th>رقم التتبع</th>
                                        <th>الحالة</th>
                                        <th>تكلفة الشحن</th>
                                        <th>العمليات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shippings as $shipping)
                                    <tr>
                                        <td>{{ $shipping->shipping_id }}</td>
                                        <td>{{ $shipping->order_id ?? 'غير محدد' }}</td>
                                        <td>{{ $shipping->carrier }}</td>
                                        <td>{{ $shipping->tracking_number }}</td>
                                        <td>
                                            @if($shipping->status === 'pending')
                                                <span class="badge text-bg-warning">قيد الانتظار</span>
                                            @elseif($shipping->status === 'shipped')
                                                <span class="badge text-bg-info">تم الشحن</span>
                                            @elseif($shipping->status === 'delivered')
                                                <span class="badge text-bg-success">تم التسليم</span>
                                            @elseif($shipping->status === 'cancelled')
                                                <span class="badge text-bg-danger">ملغاة</span>
                                            @endif
                                        </td>
                                        <td>{{ $shipping->shipping_cost }} ريال</td>
                                        <td>
                                            <div class="table-content d-flex">
                                                <a href="{{ route('admin.shippings.edit', $shipping->shipping_id) }}" 
                                                   class="theme-btn theme-btn-small me-2" title="تعديل">
                                                    <i class="la la-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.shippings.destroy', $shipping->shipping_id) }}" method="POST" 
                                                      onsubmit="return confirm('هل أنت متأكد من حذف هذه الشحنة؟');">
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
            </div>
        </div>

        {{-- Pagination --}}
        <div class="row">
            <div class="col-lg-12">
                {{ $shippings->links('pagination::bootstrap-5') }}
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

