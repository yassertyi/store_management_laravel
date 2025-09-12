@extends('frontend.admin.dashboard.index')

@section('title')
{{ isset($paymentOption) ? 'تعديل خيار الدفع' : 'إضافة خيار دفع' }}
@endsection

@section('page_title')
إدارة خيارات الدفع - {{ isset($paymentOption) ? 'تعديل' : 'إضافة جديد' }}
@endsection

@section('contects')
<section class="listing-form section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 mx-auto">
                <div class="listing-header pb-4">
                    <h3 class="title font-size-28 pb-2">{{ isset($paymentOption) ? 'تعديل خيار الدفع' : 'إضافة خيار دفع' }}</h3>
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

                <form action="{{ isset($paymentOption) ? route('admin.payment-options.update', $paymentOption->option_id) : route('admin.payment-options.store') }}" 
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($paymentOption))
                        @method('PUT')
                    @endif

                    <div class="form-box">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-credit-card me-2 text-gray"></i>معلومات خيار الدفع</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div class="row">
                                <!-- الاسم -->
                                <div class="col-lg-12 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">اسم طريقة الدفع *</label>
                                        <input class="form-control" type="text" name="method_name" 
                                               value="{{ old('method_name', $paymentOption->method_name ?? '') }}" required>
                                    </div>
                                </div>

                                <!-- الشعار -->
                                <div class="col-lg-12 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الشعار</label>
                                        <input type="file" name="logo" class="form-control-file">
                                        @if(isset($paymentOption) && $paymentOption->logo)
                                            <img src="{{ asset('storage/'.$paymentOption->logo) }}" width="80" class="mt-2 rounded">
                                        @endif
                                    </div>
                                </div>

                                <!-- العملة -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">العملة *</label>
                                        <input class="form-control" type="text" name="currency" 
                                               value="{{ old('currency', $paymentOption->currency ?? '') }}" required>
                                    </div>
                                </div>

                                <!-- الحالة -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الحالة</label>
                                        <div class="form-group d-flex align-items-center pt-2">
                                            <label class="radio-trigger mb-0 font-size-14 me-3">
                                                <input type="checkbox" class="form-check-input" name="is_active" value="1" 
                                                       {{ old('is_active', $paymentOption->is_active ?? 1) ? 'checked' : '' }}>
                                                <span class="checkmark"></span>
                                                <span>مفعل</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- أزرار الحفظ والإلغاء -->
                    <div class="submit-box pt-3">
                        <button type="submit" class="theme-btn">{{ isset($paymentOption) ? 'تحديث' : 'حفظ' }} <i class="la la-arrow-right ms-1"></i></button>
                        <a href="{{ route('admin.payment-options.index') }}" class="theme-btn theme-btn-small bg-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
