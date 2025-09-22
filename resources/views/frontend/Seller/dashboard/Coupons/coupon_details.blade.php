@extends('frontend.admin.dashboard.index')

@section('title', 'تفاصيل الكوبون')
@section('page_title', 'تفاصيل الكوبون')

@section('contects')
<section class="section--padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="card mb-4">
                    <div class="card-header">
                        <h4>بيانات الكوبون</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr><th>الكود</th><td>{{ $coupon->code }}</td></tr>
                            <tr><th>النوع</th>
                                <td>{{ $coupon->discount_type == 'percentage' ? 'نسبة %' : 'مبلغ ثابت' }}</td>
                            </tr>
                            <tr><th>قيمة الخصم</th><td>{{ $coupon->discount_value }}</td></tr>
                            <tr><th>تاريخ البداية</th><td>{{ $coupon->start_date }}</td></tr>
                            <tr><th>تاريخ الانتهاء</th><td>{{ $coupon->expiry_date }}</td></tr>
                            <tr><th>عدد الاستخدامات</th><td>{{ $coupon->usages->count() }}</td></tr>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>سجل استخدامات الكوبون</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>المستخدم</th>
                                    <th>البريد</th>
                                    <th>رقم الطلب</th>
                                    <th>تاريخ الاستخدام</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coupon->usages as $usage)
                                    <tr>
                                        <td>{{ $usage->usage_id }}</td>
                                        <td>{{ $usage->user->name ?? 'ضيف' }}</td>
                                        <td>{{ $usage->user->email ?? '-' }}</td>
                                        <td>{{ $usage->order->order_number ?? '-' }}</td>
                                        <td>{{ $usage->used_at ? $usage->used_at->format('Y-m-d H:i') : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">لم يتم استخدام هذا الكوبون بعد</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary mt-3">عودة للقائمة</a>

            </div>
        </div>
    </div>
</section>
@endsection
