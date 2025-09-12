@extends('frontend.admin.dashboard.index')

@section('title', isset($address) ? 'تعديل عنوان' : 'إضافة عنوان جديد')
@section('page_title', 'عناوين العملاء - ' . (isset($address) ? 'تعديل' : 'إضافة'))

@section('contects')
<section class="listing-form section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="listing-header pb-4">
                    <h3 class="title font-size-22">{{ isset($address) ? 'تعديل عنوان' : 'إضافة عنوان جديد' }}</h3>
                </div>

                @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ isset($address) ? route('admin.customer-addresses.update', $address->address_id) : route('admin.customer-addresses.store') }}" method="POST">
                    @csrf
                    @if(isset($address))
                        @method('PUT')
                    @endif

                    <div class="form-box">
                        <div class="form-title-wrap">
                            <h3 class="title">بيانات العنوان</h3>
                        </div>
                        <div class="form-content">
                            <div class="row">
                                <!-- العميل -->
                                <div class="col-lg-12">
                                    <div class="input-box">
                                        <label class="label-text">العميل *</label>
                                        <select name="user_id" class="form-control" required>
                                            <option value="">اختر العميل</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->user_id }}" {{ (old('user_id', $address->user_id ?? '') == $customer->user_id) ? 'selected' : '' }}>
                                                    {{ $customer->user->name ?? '---' }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>

                                <!-- باقي الحقول -->
                                <div class="col-lg-6 mt-3">
                                    <div class="input-box">
                                        <label class="label-text">العنوان الرئيسي</label>
                                        <input type="text" name="title" class="form-control" value="{{ old('title', $address->title ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 mt-3">
                                    <div class="input-box">
                                        <label class="label-text">الاسم الأول</label>
                                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $address->first_name ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 mt-3">
                                    <div class="input-box">
                                        <label class="label-text">الاسم الأخير</label>
                                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $address->last_name ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 mt-3">
                                    <div class="input-box">
                                        <label class="label-text">رقم الهاتف</label>
                                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $address->phone ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 mt-3">
                                    <div class="input-box">
                                        <label class="label-text">البلد</label>
                                        <input type="text" name="country" class="form-control" value="{{ old('country', $address->country ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 mt-3">
                                    <div class="input-box">
                                        <label class="label-text">المدينة</label>
                                        <input type="text" name="city" class="form-control" value="{{ old('city', $address->city ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 mt-3">
                                    <div class="input-box">
                                        <label class="label-text">الشارع</label>
                                        <input type="text" name="street" class="form-control" value="{{ old('street', $address->street ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 mt-3">
                                    <div class="input-box">
                                        <label class="label-text">الشقة / الوحدة</label>
                                        <input type="text" name="apartment" class="form-control" value="{{ old('apartment', $address->apartment ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 mt-3">
                                    <div class="input-box">
                                        <label class="label-text">الرمز البريدي</label>
                                        <input type="text" name="zip_code" class="form-control" value="{{ old('zip_code', $address->zip_code ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 mt-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_default" value="1" 
                                            {{ old('is_default', $address->is_default ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label">تعيين كعنوان افتراضي</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="submit-box mt-3">
                        <button type="submit" class="btn btn-success">{{ isset($address) ? 'تحديث العنوان' : 'حفظ العنوان' }}</button>
                        <a href="{{ route('admin.customer-addresses.index') }}" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
