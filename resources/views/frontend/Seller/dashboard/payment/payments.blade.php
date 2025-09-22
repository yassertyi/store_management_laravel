@extends('frontend.Seller.dashboard.index')

@section('title', 'المدفوعات')
@section('page_title', 'المدفوعات')

@section('contects')
<br><br><br>
<div class="dashboard-main-content">
    <div class="container-fluid">

        {{-- رسائل النجاح والخطأ --}}
        @if (session('success'))
            <div id="flash-message" class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div id="flash-message" class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="form-box">
            <div class="form-title-wrap d-flex justify-content-between align-items-center">
                <h3 class="title">قائمة المدفوعات</h3>
                <div>
                    @if ($payments->total() > 0)
                        <p class="font-size-14 d-inline-block me-3">
                            إظهار {{ $payments->firstItem() }} إلى {{ $payments->lastItem() }} من أصل {{ $payments->total() }} مدفوعات
                        </p>
                    @else
                        <p class="font-size-14">لا توجد مدفوعات حالياً</p>
                    @endif
                </div>
            </div>

            {{-- فلترة المدفوعات --}}
            <form method="GET" action="{{ route('seller.payments.index') }}" class="mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label">الحالة</label>
                        <select name="status" class="form-select">
                            <option value="">الكل</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مدفوع</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>ملغي</option>
                            <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>مسترد</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">من تاريخ</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">إلى تاريخ</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                    </div>

                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="la la-filter"></i> تصفية
                        </button>
                        <a href="{{ route('seller.payments.index') }}" class="btn btn-secondary w-100">
                            <i class="la la-times"></i> مسح
                        </a>
                    </div>
                </div>
            </form>

            <div class="form-content table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>رقم الدفع</th>
                            <th>الطلب</th>
                            <th>طريقة الدفع</th>
                            <th>المبلغ</th>
                            <th>الخصم</th>
                            <th>المبلغ الكلي</th>
                            <th>الحالة</th>
                            <th>تاريخ الدفع</th>
                            <th>التحكم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_id }}</td>
                                <td>{{ $payment->order->order_number ?? 'غير محدد' }}</td>
                                <td>{{ $payment->storePaymentMethod->account_name ?? $payment->method ?? 'غير محدد' }}</td>
                                <td>{{ $payment->amount }} {{ $payment->currency }}</td>
                                <td>{{ $payment->discount ?? 0 }} {{ $payment->currency }}</td>
                                <td>{{ $payment->total_amount }} {{ $payment->currency }}</td>
                                <td>
                                    @switch($payment->status)
                                        @case('completed')
                                            <span class="badge text-bg-success">مدفوع</span>
                                        @break
                                        @case('pending')
                                            <span class="badge text-bg-warning">قيد الانتظار</span>
                                        @break
                                        @case('failed')
                                            <span class="badge text-bg-danger">ملغي</span>
                                        @break
                                        @case('refunded')
                                            <span class="badge text-bg-secondary">مسترد</span>
                                        @break
                                    @endswitch
                                </td>
                                <td>{{ $payment->payment_date }}</td>
                                <td>
                                    <a href="{{ route('seller.payments.show', $payment->payment_id) }}"
                                        class="theme-btn theme-btn-small me-2" title="عرض">
                                        <i class="la la-eye"></i> فاتورة
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">لا توجد مدفوعات حالياً</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $payments->appends(request()->query())->links() }}
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
