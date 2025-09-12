@extends('frontend.admin.dashboard.index')

@section('title', 'تفاصيل استخدام الكوبون')
@section('page_title', 'تفاصيل استخدام الكوبون')

@section('contects')
    <section class="section--padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">

                    <div class="card">
                        <div class="card-header">
                            <h4>تفاصيل استخدام الكوبون</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>الكوبون</th>
                                    <td>{{ $usage->coupon->code ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>نوع الخصم</th>
                                    <td>
                                        @if (isset($usage->coupon->discount_type))
                                            @if ($usage->coupon->discount_type === 'percentage')
                                                نسبة مئوية %
                                            @elseif($usage->coupon->discount_type === 'fixed')
                                                مبلغ ثابت
                                            @else
                                                -
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>

                                </tr>
                                <tr>
                                    <th>قيمة الخصم</th>
                                    <td>{{ $usage->coupon->discount_value ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>المستخدم</th>
                                    <td>{{ $usage->user->name ?? 'ضيف' }}</td>
                                </tr>
                                <tr>
                                    <th>رقم الطلب</th>
                                    <td>{{ $usage->order->order_number ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>تاريخ الاستخدام</th>
                                    <td>{{ $usage->used_at ? $usage->used_at->format('Y-m-d H:i') : '-' }}</td>
                                </tr>
                            </table>

                            <a href="{{ route('admin.coupon-usage.index') }}" class="btn btn-secondary mt-3">عودة للقائمة</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
