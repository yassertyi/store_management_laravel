@extends('frontend.Seller.dashboard.index')

@section('title', 'طرق دفع المتجر')
@section('page_title', 'إدارة طرق الدفع الخاصة بالمتجر')

@section('contects')
<br><br><br>
<div class="dashboard-main-content">
    @if(session('success'))
        <div id="flash-message" class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-box">
                    <div class="form-title-wrap d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="title">قائمة طرق الدفع</h3>
                            @if($methods->total() > 0)
                                <p class="font-size-14">
                                    إظهار {{ $methods->firstItem() }} إلى {{ $methods->lastItem() }} من أصل {{ $methods->total() }} مُدخل
                                </p>
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('seller.storePaymentMethods.create') }}" class="theme-btn theme-btn-small">
                                <i class="la la-plus"></i> إضافة طريقة دفع
                            </a>
                        </div>
                    </div>

                    <div class="form-content">
                        <div class="table-form table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>رقم</th>
                                        <th>طريقة الدفع</th>
                                        <th>الشعار</th>
                                        <th>الحساب</th>
                                        <th>الحالة</th>
                                        <th>عمل</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($methods as $method)
                                        <tr>
                                            <td>{{ $method->spm_id }}</td>
                                            <td>{{ $method->paymentOption->method_name ?? 'غير محدد' }}</td>
                                            <td>
                                                @if($method->paymentOption && $method->paymentOption->logo)
                                                    <img src="{{ asset('storage/'.$method->paymentOption->logo) }}" width="50" class="rounded">
                                                @else
                                                    <span>بدون شعار</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $method->account_name }}<br>
                                                {{ $method->account_number }}<br>
                                                @if($method->iban)
                                                    IBAN: {{ $method->iban }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($method->is_active)
                                                    <span class="badge text-bg-success py-1 px-2">مفعل</span>
                                                @else
                                                    <span class="badge text-bg-danger py-1 px-2">غير مفعل</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="table-content d-flex">
                                                    <a href="{{ route('seller.storePaymentMethods.edit', $method->spm_id) }}" 
                                                       class="theme-btn theme-btn-small me-2" title="تعديل">
                                                        <i class="la la-edit"></i>
                                                    </a>
                                                    <form action="{{ route('seller.storePaymentMethods.destroy', $method->spm_id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
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

                        {{-- Pagination --}}
                        <div class="mt-3">
                            {{ $methods->links('pagination::bootstrap-5') }}
                        </div>
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
