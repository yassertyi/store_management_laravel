@extends('frontend.admin.dashboard.index')

@section('title')
{{ isset($shipping) ? 'تعديل شحنة' : 'إضافة شحنة جديدة' }}
@endsection

@section('page_title')
ادارة الشحنات - {{ isset($shipping) ? 'تعديل' : 'إضافة جديد' }}
@endsection

@section('contects')
<section class="listing-form section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">
                <div class="listing-header pb-4">
                    <h3 class="title font-size-28 pb-2">{{ isset($shipping) ? 'تعديل الشحنة' : 'إضافة شحنة جديدة' }}</h3>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ isset($shipping) ? route('admin.shippings.update', $shipping->shipping_id) : route('admin.shippings.store') }}" method="POST">
                    @csrf
                    @if(isset($shipping))
                        @method('PUT')
                    @endif

                    <div class="form-box">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-truck me-2 text-gray"></i>معلومات الشحنة</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div class="row">
                                <!-- رقم الطلب -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">رقم الطلب *</label>
                                        <div class="form-group">
                                            <select class="form-control" name="order_id" required>
                                                <option value="">اختر الطلب</option>
                                                @foreach($orders as $order)
                                                    <option value="{{ $order->order_id }}" 
                                                        {{ old('order_id', $shipping->order_id ?? '') == $order->order_id ? 'selected' : '' }}>
                                                        {{ $order->order_number }} - {{ $order->customer->name ?? 'عميل غير محدد' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- شركة النقل -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">شركة النقل *</label>
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="carrier" 
                                                   value="{{ old('carrier', $shipping->carrier ?? '') }}" required placeholder="شركة النقل">
                                        </div>
                                    </div>
                                </div>

                                <!-- رقم التتبع -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">رقم التتبع *</label>
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="tracking_number" 
                                                   value="{{ old('tracking_number', $shipping->tracking_number ?? '') }}" required placeholder="رقم التتبع">
                                        </div>
                                    </div>
                                </div>

                                <!-- الحالة -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الحالة *</label>
                                        <div class="form-group">
                                            <select class="form-control" name="status" required>
                                                <option value="">اختر الحالة</option>
                                                <option value="pending" {{ old('status', $shipping->status ?? '') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                                <option value="shipped" {{ old('status', $shipping->status ?? '') == 'shipped' ? 'selected' : '' }}>تم الشحن</option>
                                                <option value="delivered" {{ old('status', $shipping->status ?? '') == 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                                                <option value="cancelled" {{ old('status', $shipping->status ?? '') == 'cancelled' ? 'selected' : '' }}>ملغاة</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- تكلفة الشحن -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">تكلفة الشحن *</label>
                                        <div class="form-group">
                                            <input class="form-control" type="number" step="0.01" name="shipping_cost" 
                                                   value="{{ old('shipping_cost', $shipping->shipping_cost ?? '') }}" required placeholder="تكلفة الشحن">
                                        </div>
                                    </div>
                                </div>

                                <!-- التاريخ المتوقع للتسليم -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">التاريخ المتوقع للتسليم</label>
                                        <div class="form-group">
                                            <input class="form-control" type="date" name="estimated_delivery" 
                                                   value="{{ old('estimated_delivery', isset($shipping->estimated_delivery) ? $shipping->estimated_delivery->format('Y-m-d') : '') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="submit-box">
                        <div class="btn-box pt-3">
                            <button type="submit" class="theme-btn">{{ isset($shipping) ? 'تحديث الشحنة' : 'حفظ الشحنة' }}</button>
                            <a href="{{ route('admin.shippings.index') }}" class="theme-btn theme-btn-small bg-secondary">إلغاء</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
