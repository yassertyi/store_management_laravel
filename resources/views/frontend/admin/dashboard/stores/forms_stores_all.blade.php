@extends('frontend.admin.dashboard.index')

@section('title', isset($store) ? 'تعديل متجر' : 'إضافة متجر جديد')
@section('page_title', 'إدارة المتاجر - ' . (isset($store) ? 'تعديل' : 'إضافة جديد'))

@section('content')
<section class="listing-form section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">
                <div class="listing-header pb-4">
                    <h3 class="title font-size-28 pb-2">{{ isset($store) ? 'تعديل المتجر' : 'إضافة متجر جديد' }}</h3>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                    </div>
                @endif

                <form action="{{ isset($store) ? route('admin.stores.update', $store->store_id) : route('admin.stores.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($store)) @method('PUT') @endif

                    <!-- حقل إعادة التوجيه -->
                    <input type="hidden" name="redirect_to" value="{{ url()->previous() }}">

                    <!-- معلومات المتجر -->
                    <div class="form-box">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-store me-2 text-gray"></i>معلومات المتجر</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div class="row">
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">اسم المتجر *</label>
                                        <input type="text" name="store_name" class="form-control" value="{{ old('store_name', $store->store_name ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">صاحب المتجر *</label>
                                        <select name="user_id" class="form-control" required>
                                            <option value="">اختر البائع</option>
                                            @foreach($sellers as $seller)
                                                <option value="{{ $seller->user_id }}" {{ (old('user_id', $store->user_id ?? '') == $seller->user_id) ? 'selected' : '' }}>
                                                    {{ $seller->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">وصف المتجر</label>
                                        <textarea name="description" class="form-control">{{ old('description', $store->description ?? '') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">شعار المتجر</label>
                                        <input type="file" name="logo" class="form-control-file">
                                        @if(isset($store) && $store->logo)
                                            <img src="{{ asset('static/images/stors/'.$store->logo) }}" width="80" class="mt-2 rounded">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">بانر المتجر</label>
                                        <input type="file" name="banner" class="form-control-file">
                                        @if(isset($store) && $store->banner)
                                            <img src="{{ asset('static/images/stors/'.$store->banner) }}" width="80" class="mt-2 rounded">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الحالة</label>
                                        <select name="status" class="select-contain-select" required>
                                            <option value="active" {{ (old('status', $store->status ?? '') == 'active') ? 'selected' : '' }}>نشط</option>
                                            <option value="inactive" {{ (old('status', $store->status ?? '') == 'inactive') ? 'selected' : '' }}>غير نشط</option>
                                            <option value="suspended" {{ (old('status', $store->status ?? '') == 'suspended') ? 'selected' : '' }}>معلق</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الهواتف -->
                    <div class="form-box mt-3">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-phone me-2 text-gray"></i>أرقام الهاتف</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div id="phones-wrapper">
                                @php
                                    $phones = old('phones', isset($store) ? $store->phones->toArray() : [['number'=>'','is_primary'=>0]]);
                                @endphp
                                @foreach($phones as $index => $phone)
                                    <div class="row phone-row mb-2">
                                        <div class="col-lg-8">
                                            <input type="text" name="phones[{{ $index }}][number]" class="form-control" value="{{ $phone['number'] ?? '' }}" placeholder="رقم الهاتف">
                                        </div>
                                        <div class="col-lg-3">
                                            <select name="phones[{{ $index }}][is_primary]" class="form-control">
                                                <option value="0" {{ (isset($phone['is_primary']) && $phone['is_primary']==0) ? 'selected' : '' }}>ثانوي</option>
                                                <option value="1" {{ (isset($phone['is_primary']) && $phone['is_primary']==1) ? 'selected' : '' }}>أساسي</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-1">
                                            <button type="button" class="btn {{ $loop->first ? 'btn-success add-phone' : 'btn-danger remove-phone' }}">{{ $loop->first ? '+' : '-' }}</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- العناوين -->
                    <div class="form-box mt-3">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-map-marker me-2 text-gray"></i>العناوين</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div id="addresses-wrapper">
                                @php
                                    $addresses = old('addresses', isset($store) ? $store->addresses->toArray() : [['country'=>'','city'=>'','street'=>'','zip_code'=>'','is_primary'=>0]]);
                                @endphp
                                @foreach($addresses as $i => $address)
                                    <div class="row address-row mb-2">
                                        <div class="col-lg-2">
                                            <input type="text" name="addresses[{{ $i }}][country]" class="form-control" placeholder="الدولة" value="{{ $address['country'] ?? '' }}">
                                        </div>
                                        <div class="col-lg-2">
                                            <input type="text" name="addresses[{{ $i }}][city]" class="form-control" placeholder="المدينة" value="{{ $address['city'] ?? '' }}">
                                        </div>
                                        <div class="col-lg-3">
                                            <input type="text" name="addresses[{{ $i }}][street]" class="form-control" placeholder="الشارع" value="{{ $address['street'] ?? '' }}">
                                        </div>
                                        <div class="col-lg-2">
                                            <input type="text" name="addresses[{{ $i }}][zip_code]" class="form-control" placeholder="الرمز البريدي" value="{{ $address['zip_code'] ?? '' }}">
                                        </div>
                                        <div class="col-lg-2">
                                            <select name="addresses[{{ $i }}][is_primary]" class="form-control">
                                                <option value="0" {{ (isset($address['is_primary']) && $address['is_primary']==0) ? 'selected' : '' }}>ثانوي</option>
                                                <option value="1" {{ (isset($address['is_primary']) && $address['is_primary']==1) ? 'selected' : '' }}>أساسي</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-1">
                                            <button type="button" class="btn {{ $loop->first ? 'btn-success add-address' : 'btn-danger remove-address' }}">{{ $loop->first ? '+' : '-' }}</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="submit-box mt-3">
                        <button type="submit" class="theme-btn">{{ isset($store) ? 'تحديث المتجر' : 'حفظ المتجر' }}</button>
                        <a href="{{ route('admin.stores.index') }}" class="theme-btn theme-btn-small bg-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // الهواتف
    document.getElementById('phones-wrapper').addEventListener('click', function(e){
        if(e.target.classList.contains('add-phone')){
            const wrapper = document.getElementById('phones-wrapper');
            const index = wrapper.querySelectorAll('.phone-row').length;
            const div = document.createElement('div');
            div.classList.add('row','phone-row','mb-2');
            div.innerHTML = `
                <div class="col-lg-8">
                    <input type="text" name="phones[${index}][number]" class="form-control" placeholder="رقم الهاتف">
                </div>
                <div class="col-lg-3">
                    <select name="phones[${index}][is_primary]" class="form-control">
                        <option value="0">ثانوي</option>
                        <option value="1">أساسي</option>
                    </select>
                </div>
                <div class="col-lg-1">
                    <button type="button" class="btn btn-danger remove-phone">-</button>
                </div>`;
            wrapper.appendChild(div);
        }
        if(e.target.classList.contains('remove-phone')){
            e.target.closest('.phone-row').remove();
        }
    });

    // العناوين
    document.getElementById('addresses-wrapper').addEventListener('click', function(e){
        if(e.target.classList.contains('add-address')){
            const wrapper = document.getElementById('addresses-wrapper');
            const index = wrapper.querySelectorAll('.address-row').length;
            const div = document.createElement('div');
            div.classList.add('row','address-row','mb-2');
            div.innerHTML = `
                <div class="col-lg-2">
                    <input type="text" name="addresses[${index}][country]" class="form-control" placeholder="الدولة">
                </div>
                <div class="col-lg-2">
                    <input type="text" name="addresses[${index}][city]" class="form-control" placeholder="المدينة">
                </div>
                <div class="col-lg-3">
                    <input type="text" name="addresses[${index}][street]" class="form-control" placeholder="الشارع">
                </div>
                <div class="col-lg-2">
                    <input type="text" name="addresses[${index}][zip_code]" class="form-control" placeholder="الرمز البريدي">
                </div>
                <div class="col-lg-2">
                    <select name="addresses[${index}][is_primary]" class="form-control">
                        <option value="0">ثانوي</option>
                        <option value="1">أساسي</option>
                    </select>
                </div>
                <div class="col-lg-1">
                    <button type="button" class="btn btn-danger remove-address">-</button>
                </div>`;
            wrapper.appendChild(div);
        }
        if(e.target.classList.contains('remove-address')){
            e.target.closest('.address-row').remove();
        }
    });
});
</script>
@endsection
