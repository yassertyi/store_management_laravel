@extends('frontend.admin.dashboard.index')

@section('title')
العملاء
@endsection
@section('page_title')
إدارة العملاء
@endsection
@section('contects')
<br><br><br>
<div class="dashboard-main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-box">
                    <div class="form-title-wrap d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="title">قائمة العملاء</h3>
                            <p class="font-size-14">
                                إظهار {{ $customers->firstItem() }} إلى {{ $customers->lastItem() }} من أصل {{ $customers->total() }} مُدخل
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('admin.customers.create') }}" class="theme-btn theme-btn-small">
                                <i class="la la-plus"></i> عميل جديد
                            </a>
                        </div>
                    </div>
                    <div class="form-content">
                        <div class="table-form table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>العميل</th>
                                        <th>البريد الإلكتروني</th>
                                        <th>رقم الجوال</th>
                                        <th>نقاط الولاء</th>
                                        <th>عدد الطلبات</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customers as $customer)
                                    <tr>
                                        <td>{{ $customer->customer_id }}</td>
                                        <td>{{ $customer->user->name }}</td>
                                        <td>{{ $customer->user->email }}</td>
                                        <td>{{ $customer->user->phone ?? 'غير محدد' }}</td>
                                        <td>{{ $customer->loyalty_points }}</td>
                                        <td>{{ $customer->total_orders }}</td>
                                        <td>
                                            @if($customer->user->active)
                                                <span class="badge text-bg-success py-1 px-2">نشيط</span>
                                            @else
                                                <span class="badge text-bg-danger py-1 px-2">غير نشط</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="table-content d-flex">
                                                <a href="{{ route('admin.customers.show',$customer->customer_id) }}" class="theme-btn theme-btn-small me-2" title="عرض">
                                                    <i class="la la-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.customers.edit',$customer->customer_id) }}" class="theme-btn theme-btn-small me-2" title="تعديل">
                                                    <i class="la la-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.customers.destroy',$customer->customer_id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                                    @csrf @method('DELETE')
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
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
