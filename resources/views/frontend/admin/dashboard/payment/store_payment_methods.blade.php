@extends('frontend.admin.dashboard.index')

@section('title')
طرق دفع المتاجر
@endsection

@section('page_title')
إدارة طرق دفع المتاجر
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
                            <h3 class="title">قائمة طرق دفع المتاجر</h3>
                            <p class="font-size-14">
                                إظهار {{ $methods->firstItem() }} إلى {{ $methods->lastItem() }} من أصل {{ $methods->total() }} مُدخل
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('admin.store-payment-methods.create') }}" class="theme-btn theme-btn-small">
                                <i class="la la-plus"></i> إضافة طريقة دفع
                            </a>
                        </div>
                    </div>

                    <div class="form-content">
                        <div class="table-form table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">رقم</th>
                                        <th scope="col">المتجر</th>
                                        <th scope="col">طريقة الدفع</th>
                                        <th scope="col">الشعار</th>
                                        <th scope="col">الحساب</th>
                                        <th scope="col">الحالة</th>
                                        <th scope="col">عمل</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($methods as $method)
                                    <tr>
                                        <th scope="row">{{ $method->spm_id }}</th>
                                        <td>{{ $method->store->store_name ?? 'غير محدد' }}</td>
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
                                                <a href="{{ route('admin.store-payment-methods.edit', $method->spm_id) }}" 
                                                   class="theme-btn theme-btn-small me-2" title="تعديل">
                                                    <i class="la la-edit"></i>
                                                </a>

                                                <form action="{{ route('admin.store-payment-methods.destroy', $method->spm_id) }}" method="POST" 
                                                      onsubmit="return confirm('هل أنت متأكد من حذف طريقة الدفع؟');">
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
                <!-- end form-box -->
            </div>
        </div>

        {{-- Pagination --}}
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item {{ $methods->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $methods->previousPageUrl() }}">
                                <i class="la la-angle-left"></i>
                            </a>
                        </li>

                        @for ($i = 1; $i <= $methods->lastPage(); $i++)
                            <li class="page-item {{ $methods->currentPage() == $i ? 'active' : '' }}">
                                <a class="page-link" href="{{ $methods->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        <li class="page-item {{ !$methods->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $methods->nextPageUrl() }}">
                                <i class="la la-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
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
