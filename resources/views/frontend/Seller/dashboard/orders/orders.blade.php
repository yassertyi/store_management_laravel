@extends('frontend.Seller.dashboard.index')

@section('title', 'الطلبات')
@section('page_title', 'طلباتك')

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
                    <h3 class="title">قائمة الطلبات</h3>
                    <div>
                        @if ($orders->total() > 0)
                            <p class="font-size-14 d-inline-block me-3">
                                إظهار {{ $orders->firstItem() }} إلى {{ $orders->lastItem() }} من أصل {{ $orders->total() }}
                                طلب
                            </p>
                        @else
                            <p class="font-size-14">لا توجد طلبات حالياً</p>
                        @endif
                    </div>
                </div>

                {{-- فلترة الطلبات --}}
                <form method="GET" action="{{ route('seller.orders.index') }}" class="mb-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label">الحالة</label>
                            <select name="status" class="form-select">
                                <option value="">الكل</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار
                                </option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>جارٍ
                                    التنفيذ</option>
                                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>تم الشحن
                                </option>
                                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>مكتمل
                                </option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغى
                                </option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">حالة الدفع</label>
                            <select name="payment_status" class="form-select">
                                <option value="">الكل</option>
                                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>غير
                                    مدفوع</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>مدفوع
                                </option>
                                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>فشل
                                    الدفع</option>
                                <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>تم
                                    الاسترداد</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">اسم العميل</label>
                            <input type="text" name="customer_name" value="{{ request('customer_name') }}"
                                class="form-control">
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
                            <a href="{{ route('seller.orders.index') }}" class="btn btn-secondary w-100">
                                <i class="la la-times"></i> مسح
                            </a>
                        </div>
                    </div>
                </form>


                <div class="form-content table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>رقم الطلب</th>
                                <th>العميل</th>
                                <th>المنتج</th>
                                <th>الكمية</th>
                                <th>السعر الإجمالي</th>
                                <th>الحالة</th>
                                <th>حالة الدفع</th>
                                <th>تاريخ الإنشاء</th>
                                <th>التحكم</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
    <tr>
        <td>{{ $order->order_number }}</td>
        <td>{{ $order->customer->user->name ?? 'غير محدد' }}</td>
        <td>
            <ul class="mb-0">
                @foreach($order->items as $item)
                    @if($item->product)
                        <li>{{ $item->product->title }} ({{ $item->quantity }})</li>
                    @endif
                @endforeach
            </ul>
        </td>
        <td>
            {{ $order->items->sum('quantity') }}
        </td>
        <td>
            {{ $order->items->sum('total_price') }}
        </td>
        <td>
            @switch($order->status)
                @case('pending') <span class="badge text-bg-warning">قيد الانتظار</span> @break
                @case('processing') <span class="badge text-bg-info">جارٍ التنفيذ</span> @break
                @case('shipped') <span class="badge text-bg-primary">تم الشحن</span> @break
                @case('delivered') <span class="badge text-bg-success">مكتمل</span> @break
                @case('cancelled') <span class="badge text-bg-danger">ملغى</span> @break
            @endswitch
        </td>
        <td>
            @switch($order->payment_status)
                @case('pending') <span class="badge text-bg-warning">غير مدفوع</span> @break
                @case('paid') <span class="badge text-bg-success">مدفوع</span> @break
                @case('failed') <span class="badge text-bg-danger">فشل الدفع</span> @break
                @case('refunded') <span class="badge text-bg-secondary">تم استرداده</span> @break
            @endswitch
        </td>
        <td>{{ $order->created_at->format('Y-m-d') }}</td>
        <td>
            <a href="{{ route('seller.orders.show', $order->order_id) }}" 
               class="theme-btn theme-btn-small me-2" title="عرض">
                <i class="la la-eye"></i>
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="9" class="text-center">لا توجد طلبات حالياً</td>
    </tr>
@endforelse

                                </tbody>
                            </table>

                            {{-- Pagination --}}
                            <div class="mt-3">
                                {{ $orders->appends(request()->query())->links() }}
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
