@extends('frontend.customers.dashboard.index')

@section('title', isset($address) ? 'تعديل عنوان' : 'إضافة عنوان جديد')
@section('page_title', 'إدارة العناوين - ' . (isset($address) ? 'تعديل' : 'إضافة'))

@section('contects')
    <section class="listing-form section--padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 mx-auto">

                    <div class="listing-header pb-4">
                        <h3 class="title font-size-28 pb-2">{{ isset($address) ? 'تعديل العنوان' : 'إضافة عنوان جديد' }}</h3>
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

                    <form
                        action="{{ isset($address) ? route('customer.addresses.update', $address) : route('customer.addresses.store') }}"
                        method="POST">
                        @csrf
                        @if (isset($address))
                            @method('PUT')
                        @endif

                        <div class="form-box">
                            <div class="form-title-wrap">
                                <h3 class="title"><i class="la la-map me-2 text-gray"></i>معلومات العنوان</h3>
                            </div>
                            <div class="form-content contact-form-action">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>اسم العنوان *</label>
                                        <input type="text" name="title" class="form-control"
                                            value="{{ old('title', $address->title ?? '') }}" required>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>رقم الهاتف *</label>
                                        <input type="text" name="phone" class="form-control"
                                            value="{{ old('phone', $address->phone ?? '') }}" required>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>الاسم الأول *</label>
                                        <input type="text" name="first_name" class="form-control"
                                            value="{{ old('first_name', $address->first_name ?? '') }}" required>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>اسم العائلة *</label>
                                        <input type="text" name="last_name" class="form-control"
                                            value="{{ old('last_name', $address->last_name ?? '') }}" required>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>المدينة *</label>
                                        <input type="text" name="city" class="form-control"
                                            value="{{ old('city', $address->city ?? '') }}" required>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>الدولة *</label>
                                        <input type="text" name="country" class="form-control"
                                            value="{{ old('country', $address->country ?? '') }}" required>
                                    </div>
                                    <div class="col-lg-12">
                                        <label>الشارع *</label>
                                        <input type="text" name="street" class="form-control"
                                            value="{{ old('street', $address->street ?? '') }}" required>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>شقة</label>
                                        <input type="text" name="apartment" class="form-control"
                                            value="{{ old('apartment', $address->apartment ?? '') }}">
                                    </div>
                                    <div class="col-lg-6">
                                        <label>الرمز البريدي</label>
                                        <input type="text" name="zip_code" class="form-control"
                                            value="{{ old('zip_code', $address->zip_code ?? '') }}">
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="submit-box mt-3">
                            <button type="submit" class="theme-btn">{{ isset($address) ? 'تحديث' : 'حفظ' }}</button>
                            <a href="{{ route('customer.addresses.index') }}"
                                class="theme-btn theme-btn-small bg-secondary">إلغاء</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
