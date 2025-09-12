@extends('frontend.admin.dashboard.index')

@section('title', 'تفاصيل الطلب')
@section('page_title', 'تفاصيل الطلب')

@section('contects')
<br><br><br>
<div class="dashboard-main-content">
    <div class="container-fluid">
        @if (session('success'))
            <div id="flash-message" class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
            </div>
        @endif

        @if (session('error'))
            <div id="flash-message" class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
            </div>
        @endif

        <div class="form-box">
            <div class="form-title-wrap d-flex justify-content-between align-items-center">
                <h3 class="title">تفاصيل الطلب</h3>
                <div>
                    <a href="{{ url()->previous() }}" class="theme-btn theme-btn-small bg-secondary me-2">
                        <i class="la la-arrow-right"></i> رجوع
                    </a>

                    <a href="{{ route('admin.orders.edit', $order->order_id) }}" class="theme-btn theme-btn-small me-2">
                        <i class="la la-edit"></i> تعديل
                    </a>

                    <form action="{{ route('admin.orders.destroy', $order->order_id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('هل أنت متأكد من الحذف؟')" class="theme-btn theme-btn-small bg-danger">
                            <i class="la la-trash"></i> حذف
                        </button>
                    </form>
                </div>
            </div>

            <div class="form-content">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="section-tab section-tab-3 traveler-tab">
                            <ul class="nav nav-tabs ms-0" id="orderTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="order-detail-tab" data-bs-toggle="tab"
                                        href="#order-detail" role="tab" aria-controls="order-detail"
                                        aria-selected="true">
                                        تفاصيل الطلب
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="customer-tab" data-bs-toggle="tab" href="#customer"
                                        role="tab" aria-controls="customer" aria-selected="false">
                                        العميل
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="products-tab" data-bs-toggle="tab" href="#products"
                                        role="tab" aria-controls="products" aria-selected="false">
                                        المنتجات
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="payment-tab" data-bs-toggle="tab" href="#payment"
                                        role="tab" aria-controls="payment" aria-selected="false">
                                        حالة الدفع
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content pt-4" id="orderTabContent">
                            <!-- تفاصيل الطلب -->
                            <div class="tab-pane fade show active" id="order-detail" role="tabpanel"
                                aria-labelledby="order-detail-tab">
                                <div class="profile-item mb-4">
                                    <h3 class="title">معلومات الطلب</h3>
                                    <div class="row pt-3">
                                        <div class="col-lg-6">
                                            <ul class="list-items list-items-2 list-items-3">
                                                <li><span>رقم الطلب:</span> {{ $order->order_number }}</li>
                                                <li><span>المجموع:</span> {{ $order->total_amount }}</li>
                                                <li><span>الحالة:</span>
                                                    @if ($order->status == 'pending')
                                                        <span class="badge text-bg-warning py-1 px-2">قيد الانتظار</span>
                                                    @elseif($order->status == 'processing')
                                                        <span class="badge text-bg-info py-1 px-2">جارٍ التنفيذ</span>
                                                    @elseif($order->status == 'completed')
                                                        <span class="badge text-bg-success py-1 px-2">مكتمل</span>
                                                    @elseif($order->status == 'cancelled')
                                                        <span class="badge text-bg-danger py-1 px-2">ملغى</span>
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-6">
                                            <ul class="list-items list-items-2 list-items-3">
                                                <li><span>تاريخ الإنشاء:</span> {{ $order->created_at->format('Y/m/d') }}</li>
                                                <li><span>آخر تحديث:</span> {{ $order->updated_at->format('Y/m/d') }}</li>
                                                <li><span>حالة الدفع:</span>
                                                    @if ($order->payment_status == 'pending')
                                                        <span class="badge text-bg-warning py-1 px-2">غير مدفوع</span>
                                                    @elseif($order->payment_status == 'paid')
                                                        <span class="badge text-bg-success py-1 px-2">مدفوع</span>
                                                    @elseif($order->payment_status == 'failed')
                                                        <span class="badge text-bg-danger py-1 px-2">فشل الدفع</span>
                                                    @elseif($order->payment_status == 'refunded')
                                                        <span class="badge text-bg-secondary py-1 px-2">تم استرداده</span>
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- تبويب العميل -->
                            <div class="tab-pane fade" id="customer" role="tabpanel" aria-labelledby="customer-tab">
                                <div class="profile-item">
                                    <h3 class="title">معلومات العميل</h3>
                                    <div class="row pt-3">
                                        <div class="col-lg-3 text-center">
                                            @if ($order->customer->photo)
                                                <img src="{{ asset($order->customer->photo) }}" alt="{{ $order->customer->name }}"
                                                    class="img-fluid rounded-circle shadow-sm mb-3" style="max-height: 150px;">
                                            @else
                                                <img src="{{ asset('static/images/avatar.jpeg') }}" alt="صورة افتراضية"
                                                    class="img-fluid rounded-circle shadow-sm mb-3" style="max-height: 150px;">
                                            @endif
                                        </div>
                                        <div class="col-lg-9">
                                            <ul class="list-items list-items-2 list-items-3">
                                                <li><span>الاسم:</span> {{ $order->customer->name }}</li>
                                                <li><span>البريد الإلكتروني:</span> {{ $order->customer->email }}</li>
                                                <li><span>رقم الهاتف:</span> {{ $order->customer->phone }}</li>
                                                <li><span>تاريخ التسجيل:</span> {{ $order->customer->created_at->format('Y/m/d') }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- تبويب المنتجات -->
<div class="tab-pane fade" id="products" role="tabpanel" aria-labelledby="products-tab">
    <div class="profile-item">
        <h3 class="title mb-3">المنتجات في الطلب</h3>
        <div class="table-form table-responsive">
            @if ($order->items->count() > 0)
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>المنتج</th>
                            <th>الكمية</th>
                            <th>السعر</th>
                            <th>الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>{{ $item->product->name ?? 'منتج محذوف' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->unit_price }}</td>
                                <td>{{ $item->total_price }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info">لا توجد منتجات في هذا الطلب</div>
            @endif
        </div>
    </div>
</div>


                            <!-- تبويب حالة الدفع -->
                            <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                                <div class="profile-item">
                                    <h3 class="title">تفاصيل الدفع</h3>
                                    <ul class="list-items list-items-2 list-items-3 pt-3">
                                        <li><span>طريقة الدفع:</span> {{ $order->payment_method ?? 'غير محدد' }}</li>
                                        <li><span>المبلغ المدفوع:</span> {{ $order->paid_amount ?? '0' }}</li>
                                        <li><span>تاريخ الدفع:</span> {{ $order->payment_date ? $order->payment_date->format('Y/m/d') : 'غير محدد' }}</li>
                                    </ul>
                                </div>
                            </div>

                        </div> <!-- tab-content -->
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
