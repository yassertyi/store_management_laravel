@extends('frontend.admin.dashboard.index')

@section('title')
    المدفوعات
@endsection

@section('page_title')
    إدارة المدفوعات
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
                                <h3 class="title">قائمة المدفوعات</h3>
                                <p class="font-size-14">
                                    إظهار {{ $payments->firstItem() }} إلى {{ $payments->lastItem() }} من أصل
                                    {{ $payments->total() }} مُدخل
                                </p>
                            </div>
                            <div>
                                <a href="{{ route('admin.payments.create') }}" class="theme-btn theme-btn-small">
                                    <i class="la la-plus"></i> دفع جديد
                                </a>
                            </div>
                        </div>

                        <div class="form-content">
                            <div class="table-form table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">رقم الدفع</th>
                                            <th scope="col">الطلب</th>
                                            <th scope="col">طريقة الدفع</th>
                                            <th scope="col">المبلغ</th>
                                            <th scope="col">الخصم</th>
                                            <th scope="col">المبلغ الكلي</th>
                                            <th scope="col">الحالة</th>
                                            <th scope="col">تاريخ الدفع</th>
                                            <th scope="col">عمل</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($payments as $payment)
                                            <tr>
                                                <th scope="row">{{ $payment->payment_id }}</th>
                                                <td>{{ $payment->order->order_number ?? 'غير محدد' }}</td>
                                                <td>
                                                    {{ $payment->storePaymentMethod->account_name ?? ($payment->method ?? 'غير محدد') }}
                                                </td>

                                                <td>{{ $payment->amount }} {{ $payment->currency }}</td>
                                                <td>{{ $payment->discount ?? 0 }} {{ $payment->currency }}</td>
                                                <td>{{ $payment->total_amount }} {{ $payment->currency }}</td>
                                                <td>
                                                    @if ($payment->status === 'completed')
                                                        <span class="badge text-bg-success py-1 px-2">مدفوع</span>
                                                    @elseif($payment->status === 'pending')
                                                        <span class="badge text-bg-warning py-1 px-2">قيد الانتظار</span>
                                                    @elseif($payment->status === 'failed')
                                                        <span class="badge text-bg-danger py-1 px-2">ملغي</span>
                                                    @elseif($payment->status === 'refunded')
                                                        <span class="badge text-bg-info py-1 px-2">مسترد</span>
                                                    @endif
                                                </td>

                                                <td>{{ $payment->payment_date }}</td>
                                                <td>
                                                    <div class="table-content d-flex">
                                                        <a href="{{ route('admin.payments.edit', $payment->payment_id) }}"
                                                            class="theme-btn theme-btn-small me-2" title="تعديل">
                                                            <i class="la la-edit"></i>
                                                        </a>

                                                        <form
                                                            action="{{ route('admin.payments.destroy', $payment->payment_id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('هل أنت متأكد من حذف هذا الدفع؟');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="theme-btn theme-btn-small bg-danger" title="حذف">
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
                            <li class="page-item {{ $payments->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $payments->previousPageUrl() }}">
                                    <i class="la la-angle-left"></i>
                                </a>
                            </li>

                            @for ($i = 1; $i <= $payments->lastPage(); $i++)
                                <li class="page-item {{ $payments->currentPage() == $i ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $payments->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            <li class="page-item {{ !$payments->hasMorePages() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $payments->nextPageUrl() }}">
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

