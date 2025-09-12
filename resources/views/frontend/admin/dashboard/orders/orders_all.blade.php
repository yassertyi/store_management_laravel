@extends('frontend.admin.dashboard.index')

@section('title', 'الطلبات')
@section('page_title', 'جميع الطلبات')

@section('contects')
<br><br><br>
<div class="dashboard-main-content">
    <div class="container-fluid">
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

        <div class="form-box">
            <div class="form-title-wrap d-flex justify-content-between align-items-center">
                <h3 class="title">قائمة الطلبات</h3>
                <div>
                    <p class="font-size-14 d-inline-block me-3">
                        إظهار {{ $orders->firstItem() }} إلى {{ $orders->lastItem() }} من أصل {{ $orders->total() }} طلب
                    </p>
                    <a href="{{ route('admin.orders.create') }}" class="theme-btn theme-btn-small btn-success">
                        <i class="la la-plus"></i> إضافة طلب جديد
                    </a>
                </div>
            </div>

            <div class="form-content table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>رقم الطلب</th>
                            <th>العميل</th>
                            <th>المجموع</th>
                            <th>الحالة</th>
                            <th>حالة الدفع</th>
                            <th>تاريخ الإنشاء</th>
                            <th>التحكم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->customer->user->name ?? 'غير محدد' }}</td>
                                <td>{{ $order->total_amount }}</td>
                                <td>
                                    @if ($order->status === 'pending')
                                        <span class="badge text-bg-warning">قيد الانتظار</span>
                                    @elseif($order->status === 'processing')
                                        <span class="badge text-bg-info">جارٍ التنفيذ</span>
                                    @elseif($order->status === 'completed')
                                        <span class="badge text-bg-success">مكتمل</span>
                                    @elseif($order->status === 'cancelled')
                                        <span class="badge text-bg-danger">ملغى</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($order->payment_status === 'pending')
                                        <span class="badge text-bg-warning">غير مدفوع</span>
                                    @elseif($order->payment_status === 'paid')
                                        <span class="badge text-bg-success">مدفوع</span>
                                    @elseif($order->payment_status === 'failed')
                                        <span class="badge text-bg-danger">فشل الدفع</span>
                                    @elseif($order->payment_status === 'refunded')
                                        <span class="badge text-bg-secondary">تم استرداده</span>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="theme-btn theme-btn-small me-2" title="عرض">
                                        <i class="la la-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.orders.edit', $order->order_id) }}" class="theme-btn theme-btn-small me-2" title="تعديل">
                                        <i class="la la-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.orders.destroy', $order->order_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('هل أنت متأكد من الحذف؟')" class="theme-btn theme-btn-small bg-danger">
                                            <i class="la la-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('script_sdebar')
<script>
    setTimeout(function() {
        const flash = document.getElementById('flash-message');
        if(flash){
            flash.style.transition = "opacity 0.5s ease";
            flash.style.opacity = '0';
            setTimeout(()=> flash.remove(), 500);
        }
    }, 3000);
</script>
@endsection
