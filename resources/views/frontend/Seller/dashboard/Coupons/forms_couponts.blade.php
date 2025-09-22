@extends('frontend.Seller.dashboard.index')

@section('title', isset($coupon) ? 'تعديل كوبون' : 'إضافة كوبون جديد')
@section('page_title', isset($coupon) ? 'تعديل كوبون' : 'إضافة كوبون')

@section('contects')
<section class="listing-form section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">

                <form action="{{ isset($coupon) ? route('seller.coupons.update', $coupon->coupon_id) : route('seller.coupons.store') }}" method="POST">
                    @csrf
                    @if(isset($coupon)) @method('PUT') @endif

                    <div class="form-box">
                        <div class="form-title-wrap">
                            <h3 class="title">بيانات الكوبون</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="label-text">الكود *</label>
                                    <input type="text" name="code" class="form-control"
                                           value="{{ old('code', $coupon->code ?? '') }}" required>
                                </div>

                                <div class="col-lg-6">
                                    <label class="label-text">نوع الخصم *</label>
                                    <select name="discount_type" class="form-control" required>
                                        <option value="percentage" {{ old('discount_type', $coupon->discount_type ?? '') == 'percentage' ? 'selected' : '' }}>نسبة %</option>
                                        <option value="fixed" {{ old('discount_type', $coupon->discount_type ?? '') == 'fixed' ? 'selected' : '' }}>مبلغ ثابت</option>
                                    </select>
                                </div>

                                <div class="col-lg-6">
                                    <label class="label-text">قيمة الخصم *</label>
                                    <input type="number" step="0.01" name="discount_value" class="form-control"
                                           value="{{ old('discount_value', $coupon->discount_value ?? '') }}" required>
                                </div>

                                <div class="col-lg-6">
                                    <label class="label-text">تاريخ البداية *</label>
                                    <input type="date" name="start_date" class="form-control"
                                           value="{{ old('start_date', isset($coupon) ? $coupon->start_date->format('Y-m-d') : '') }}" required>
                                </div>

                                <div class="col-lg-6">
                                    <label class="label-text">تاريخ الانتهاء *</label>
                                    <input type="date" name="expiry_date" class="form-control"
                                           value="{{ old('expiry_date', isset($coupon) ? $coupon->expiry_date->format('Y-m-d') : '') }}" required>
                                </div>

                                <div class="col-lg-6">
                                    <label class="label-text">الحالة</label><br>
                                    <input type="checkbox" name="is_active" value="1" 
                                           {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}> نشط
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="btn-box pt-3">
                        <button type="submit" class="theme-btn">
                            {{ isset($coupon) ? 'تحديث' : 'حفظ' }}
                        </button>
                        <a href="{{ route('seller.coupons.index') }}" class="theme-btn theme-btn-small bg-secondary">إلغاء</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>
@endsection
