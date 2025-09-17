@extends('frontend.home.layouts.master')

@section('title', 'طلب فتح حساب متجر')

@section('content')
<section class="add-hotel-area padding-top-100px padding-bottom-100px">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-box">
                    <div class="form-title-wrap">
                        <h3 class="title font-size-22">طلب فتح حساب متجر</h3>
                        <p class="font-size-14">سيتم مراجعة طلبك من قبل الإدارة</p>
                    </div>

                    <div class="form-content">
                        <div class="contact-form-action">

                            {{-- رسائل النجاح/الخطأ --}}
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <h5>يوجد أخطاء، الرجاء التصحيح:</h5>
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $err)
                                            <li>{{ $err }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('seller.registerStore.submit') }}" enctype="multipart/form-data">
                                @csrf

                                <!-- معلومات المستخدم -->
                                <div class="form-group mb-4">
                                    <h5 class="title font-size-16 mb-3">معلوماتك الشخصية</h5>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <label class="label-text">الاسم <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                        </div>

                                        <div class="col-lg-4">
                                            <label class="label-text">البريد الإلكتروني <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                                        </div>

                                        <div class="col-lg-4">
                                            <label class="label-text">الهاتف</label>
                                            <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}">
                                        </div>

                                        <div class="col-lg-4 mt-3">
                                            <label class="label-text">كلمة المرور <span class="text-danger">*</span></label>
                                            <input type="password" name="password" class="form-control" required>
                                        </div>

                                        <div class="col-lg-4 mt-3">
                                            <label class="label-text">تأكيد كلمة المرور</label>
                                            <input type="password" name="password_confirmation" class="form-control">
                                        </div>

                                        <div class="col-lg-4 mt-3">
                                            <label class="label-text">الجنس</label>
                                            <select name="gender" class="form-control">
                                                <option value="">-- اختر --</option>
                                                <option value="0" {{ old('gender') === '0' ? 'selected' : '' }}>ذكر</option>
                                                <option value="1" {{ old('gender') === '1' ? 'selected' : '' }}>أنثى</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-4 mt-3">
                                            <label class="label-text">صورتك الشخصية</label>
                                            <input type="file" name="profile_photo" class="form-control" accept="image/*">
                                        </div>
                                    </div>
                                </div>

                                <!-- معلومات المتجر -->
                                <div class="form-group mb-4">
                                    <h5 class="title font-size-16 mb-3">معلومات المتجر</h5>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label class="label-text">اسم المتجر <span class="text-danger">*</span></label>
                                            <input type="text" name="store_name" class="form-control" value="{{ old('store_name') }}" required>
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="label-text">وصف المتجر</label>
                                            <textarea name="store_description" class="form-control" rows="3">{{ old('store_description') }}</textarea>
                                        </div>

                                        <div class="col-lg-6 mt-3">
                                            <label class="label-text">شعار المتجر (Logo)</label>
                                            <input type="file" name="logo" class="form-control" accept="image/*">
                                        </div>

                                        <div class="col-lg-6 mt-3">
                                            <label class="label-text">بانر المتجر</label>
                                            <input type="file" name="banner" class="form-control" accept="image/*">
                                        </div>
                                    </div>
                                </div>

                                <!-- الرخصة والمستندات -->
                                <div class="form-group mb-4">
                                    <h5 class="title font-size-16 mb-3">الرخصة والمستندات</h5>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label class="label-text">رقم الرخصة التجارية</label>
                                            <input type="text" name="business_license_number" class="form-control" value="{{ old('business_license_number') }}">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="label-text">مستند رسمي</label>
                                            <input type="file" name="document_path" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                        </div>
                                    </div>
                                </div>

                                <!-- العنوان -->
                                <div class="form-group mb-4">
                                    <h5 class="title font-size-16 mb-3">عنوان المتجر</h5>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <label class="label-text">الدولة</label>
                                            <input type="text" name="country" class="form-control" value="{{ old('country') }}">
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="label-text">المحافظة / الولاية</label>
                                            <input type="text" name="state" class="form-control" value="{{ old('state') }}">
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="label-text">المدينة</label>
                                            <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                                        </div>
                                        <div class="col-lg-12 mt-3">
                                            <label class="label-text">الشارع / العنوان التفصيلي</label>
                                            <input type="text" name="street" class="form-control" value="{{ old('street') }}">
                                        </div>
                                        <div class="col-lg-4 mt-3">
                                            <label class="label-text">الرمز البريدي</label>
                                            <input type="text" name="zip_code" class="form-control" value="{{ old('zip_code') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- معلومات الاتصال -->
                                <div class="form-group mb-4">
                                    <h5 class="title font-size-16 mb-3">معلومات الاتصال</h5>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <label class="label-text">رمز الدولة</label>
                                            <input type="text" name="country_code" class="form-control" value="{{ old('country_code') }}">
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="label-text">رقم الهاتف</label>
                                            <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- صور إضافية -->
                                <div class="form-group mb-4">
                                    <h5 class="title font-size-16 mb-3">صور إضافية</h5>
                                    <input type="file" name="additional_images[]" class="form-control" multiple accept="image/*">
                                </div>

                                <div class="btn-box pt-3">
                                    <button type="submit" class="theme-btn">إرسال الطلب</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
