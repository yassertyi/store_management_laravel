@extends('frontend.admin.dashboard.index')

@section('title')
{{ isset($coupon) ? 'تعديل كوبون' : 'إضافة كوبون جديد' }}
@endsection

@section('page_title')
إدارة الكوبونات - {{ isset($coupon) ? 'تعديل' : 'إضافة جديد' }}
@endsection

@section('contects')
<section class="listing-form section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">
                <div class="listing-header pb-4">
                    <h3 class="title font-size-28 pb-2">{{ isset($coupon) ? 'تعديل الكوبون' : 'إضافة كوبون جديد' }}</h3>
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

                <form action="{{ isset($coupon) ? route('admin.coupons.update', $coupon->coupon_id) : route('admin.coupons.store') }}" 
                      method="POST">
                    @csrf
                    @if(isset($coupon)) @method('PUT') @endif

                    <div class="form-box">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-ticket me-2"></i> بيانات الكوبون</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div class="row">
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الكود</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="code"
                                                   value="{{ old('code', $coupon->code ?? $code) }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">نوع الخصم *</label>
                                        <div class="form-group">
                                            <select name="discount_type" class="form-control" required>
                                                <option value="">اختر النوع</option>
                                                <option value="percentage" {{ old('discount_type', $coupon->discount_type ?? '')=='percentage' ? 'selected' : '' }}>نسبة %</option>
                                                <option value="fixed" {{ old('discount_type', $coupon->discount_type ?? '')=='fixed' ? 'selected' : '' }}>مبلغ ثابت</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">قيمة الخصم *</label>
                                        <input type="number" step="0.01" class="form-control" name="discount_value"
                                               value="{{ old('discount_value', $coupon->discount_value ?? '') }}" required>
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الحد الأدنى للطلب</label>
                                        <input type="number" step="0.01" class="form-control" name="min_order_amount"
                                               value="{{ old('min_order_amount', $coupon->min_order_amount ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الحد الأقصى للخصم</label>
                                        <input type="number" step="0.01" class="form-control" name="max_discount_amount"
                                               value="{{ old('max_discount_amount', $coupon->max_discount_amount ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">تاريخ البداية *</label>
                                        <input type="date" class="form-control" name="start_date"
                                               value="{{ old('start_date', isset($coupon) ? $coupon->start_date->format('Y-m-d') : '') }}" required>
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">تاريخ الانتهاء *</label>
                                        <input type="date" class="form-control" name="expiry_date"
                                               value="{{ old('expiry_date', isset($coupon) ? $coupon->expiry_date->format('Y-m-d') : '') }}" required>
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الحالة</label>
                                        <div class="form-group">
                                            <input type="checkbox" name="is_active" value="1"
                                                   {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}>
                                            نشط
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="submit-box">
                        <div class="btn-box pt-3">
                            <button type="submit" class="theme-btn">
                                {{ isset($coupon) ? 'تحديث الكوبون' : 'حفظ الكوبون' }}
                            </button>
                            <a href="{{ route('admin.coupons.index') }}" class="theme-btn theme-btn-small bg-secondary">إلغاء</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>
@endsection
