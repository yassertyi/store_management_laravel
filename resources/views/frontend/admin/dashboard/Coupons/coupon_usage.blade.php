@extends('frontend.admin.dashboard.index')

@section('title')
استخدامات الكوبونات
@endsection

@section('page_title')
إدارة استخدامات الكوبونات
@endsection

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
                            <h3 class="title">قائمة استخدامات الكوبونات</h3>
                            <p class="font-size-14">
                                إظهار {{ $usages->firstItem() }} إلى {{ $usages->lastItem() }} من أصل {{ $usages->total() }} مُدخل
                            </p>
                        </div>
                    </div>

                    <div class="form-content">
                        <div class="table-form table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الكوبون</th>
                                        <th>المستخدم</th>
                                        <th>رقم الطلب</th>
                                        <th>تاريخ الاستخدام</th>
                                        <th>عمليات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($usages as $usage)
                                    <tr>
                                        <td>{{ $usage->usage_id }}</td>
                                        <td>{{ $usage->coupon->code ?? 'غير محدد' }}</td>
                                        <td>{{ $usage->user->name ?? 'ضيف' }}</td>
                                        <td>{{ $usage->order->order_number ?? 'غير محدد' }}</td>
                                        <td>{{ $usage->used_at ? $usage->used_at->format('Y-m-d H:i') : '-' }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('admin.coupon-usage.show', $usage->usage_id) }}" 
                                                   class="theme-btn theme-btn-small me-2" title="التفاصيل">
                                                    <i class="la la-eye"></i>
                                                </a>
                                                <form action="{{ route('admin.coupon-usage.destroy', $usage->usage_id) }}" method="POST" 
                                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا الاستخدام؟');">
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
                                <li class="page-item {{ $usages->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $usages->previousPageUrl() }}">
                                        <i class="la la-angle-left"></i>
                                    </a>
                                </li>
                                @for ($i = 1; $i <= $usages->lastPage(); $i++)
                                    <li class="page-item {{ $usages->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $usages->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                <li class="page-item {{ !$usages->hasMorePages() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $usages->nextPageUrl() }}">
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
