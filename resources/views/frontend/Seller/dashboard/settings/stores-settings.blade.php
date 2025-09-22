@extends('frontend.Seller.dashboard.index')
@section('title', 'تعديل بيانات المتجر')
@section('page_title', 'تعديل بيانات المتجر')

@section('contects')
<br><br><br>
<div class="dashboard-main-content">

    @if(session('success'))
        <div id="flash-message" class="alert alert-success alert-dismissible fade show">{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <form action="{{ route('seller.seller.store.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- بيانات المتجر الأساسية -->
                        <div class="col-lg-6">
                            <div class="form-box">
                                <div class="form-title-wrap">
                                    <h3 class="title">إعداد المتجر</h3>
                                </div>
                                <div class="form-content">
                                    <div class="col-lg-12 mb-3">
                                        <label class="label-text">اسم المتجر</label>
                                        <input class="form-control" type="text" name="store_name" value="{{ old('store_name', $store->store_name ?? '') }}">
                                        @error('store_name')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>

                                    <div class="col-lg-12 mb-3">
                                        <label class="label-text">الوصف</label>
                                        <textarea class="form-control" name="description">{{ old('description', $store->description ?? '') }}</textarea>
                                        @error('description')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <label class="label-text">شعار المتجر</label>
                                            <input type="file" name="logo" class="form-control">
                                            @if(isset($store) && $store->logo)
                                                <img src="{{ asset('static/images/stors/'.$store->logo) }}" width="80" class="mt-2 rounded">
                                            @endif
                                            @error('logo')<span class="text-danger">{{ $message }}</span>@enderror
                                        </div>

                                        <div class="col-lg-6 mb-3">
                                            <label class="label-text">صورة البانر</label>
                                            <input type="file" name="banner" class="form-control">
                                            @if(isset($store) && $store->banner)
                                                <img src="{{ asset('static/images/stors/'.$store->banner) }}" width="80" class="mt-2 rounded">
                                            @endif
                                            @error('banner')<span class="text-danger">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- الهواتف -->
                        <div class="col-lg-6">
                            <div class="form-box">
                                <div class="form-title-wrap">
                                    <h3 class="title">أرقام الهواتف</h3>
                                </div>
                                <div class="form-content" id="phones-wrapper">
                                    @php
                                        $phones = old('phones', $store->phones->toArray() ?? [['number'=>'','is_primary'=>0]]);
                                    @endphp
                                    @foreach($phones as $index => $phone)
                                        <div class="row phone-row mb-2">
                                            <div class="col-lg-8">
                                                <input type="text" name="phones[{{ $index }}][number]" class="form-control" value="{{ $phone['phone'] ?? $phone['number'] }}" placeholder="رقم الهاتف">
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
                        <div class="col-lg-12 mt-4">
                            <div class="form-box">
                                <div class="form-title-wrap">
                                    <h3 class="title">العناوين</h3>
                                </div>
                                <div class="form-content" id="addresses-wrapper">
                                    @php
                                        $addresses = old('addresses', $store->addresses->toArray() ?? [['country'=>'','city'=>'','street'=>'','zip_code'=>'','is_primary'=>0]]);
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

                        <!-- زر الحفظ -->
                        <div class="col-lg-12 mt-4 text-center">
                            <button class="theme-btn" type="submit">💾 حفظ التغييرات</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script_sdebar')
<script>
    setTimeout(() => {
        const flash = document.getElementById('flash-message');
        if(flash){
            flash.style.transition = "opacity 0.5s ease";
            flash.style.opacity = '0';
            setTimeout(()=> flash.remove(), 500);
        }
    }, 3000);

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
                    <div class="col-lg-2"><input type="text" name="addresses[${index}][country]" class="form-control" placeholder="الدولة"></div>
                    <div class="col-lg-2"><input type="text" name="addresses[${index}][city]" class="form-control" placeholder="المدينة"></div>
                    <div class="col-lg-3"><input type="text" name="addresses[${index}][street]" class="form-control" placeholder="الشارع"></div>
                    <div class="col-lg-2"><input type="text" name="addresses[${index}][zip_code]" class="form-control" placeholder="الرمز البريدي"></div>
                    <div class="col-lg-2">
                        <select name="addresses[${index}][is_primary]" class="form-control">
                            <option value="0">ثانوي</option>
                            <option value="1">أساسي</option>
                        </select>
                    </div>
                    <div class="col-lg-1"><button type="button" class="btn btn-danger remove-address">-</button></div>`;
                wrapper.appendChild(div);
            }
            if(e.target.classList.contains('remove-address')){
                e.target.closest('.address-row').remove();
            }
        });
    });
</script>
@endsection
