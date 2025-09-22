@extends('frontend.Seller.dashboard.index')

@section('title')
{{ isset($storePaymentMethod) ? 'تعديل طريقة الدفع' : 'إضافة طريقة دفع جديدة' }}
@endsection

@section('page_title')
إدارة طرق دفع المتاجر - {{ isset($storePaymentMethod) ? 'تعديل' : 'إضافة جديد' }}
@endsection

@section('contects')
<section class="listing-form section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">
                <div class="listing-header pb-4">
                    <h3 class="title font-size-28 pb-2">{{ isset($storePaymentMethod) ? 'تعديل طريقة الدفع' : 'إضافة طريقة دفع جديدة' }}</h3>
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

                <form action="{{ isset($storePaymentMethod) ? route('seller.storePaymentMethods.update', $storePaymentMethod->spm_id) : route('seller.storePaymentMethods.store') }}" 
                      method="POST">
                    @csrf
                    @if(isset($storePaymentMethod))
                        @method('PUT')
                    @endif

                    <div class="form-box">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-credit-card me-2 text-gray"></i>معلومات طريقة الدفع</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div class="row">

                                

                                <!-- طريقة الدفع -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">طريقة الدفع *</label>
                                        <div class="form-group select2-container-wrapper select-contain w-100">
                                            <select class="select-contain-select" name="payment_option_id" required>
                                                <option value="">اختر طريقة الدفع</option>
                                                @foreach($options as $option)
                                                    <option value="{{ $option->option_id }}" {{ isset($storePaymentMethod) && $storePaymentMethod->payment_option_id == $option->option_id ? 'selected' : '' }}>
                                                        {{ $option->method_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- اسم الحساب -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">اسم الحساب *</label>
                                        <div class="form-group">
                                            <span class="la la-user form-icon"></span>
                                            <input class="form-control" type="text" name="account_name" 
                                                   value="{{ old('account_name', $storePaymentMethod->account_name ?? '') }}" 
                                                   required placeholder="اسم الحساب">
                                        </div>
                                    </div>
                                </div>

                                <!-- رقم الحساب -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">رقم الحساب *</label>
                                        <div class="form-group">
                                            <span class="la la-credit-card form-icon"></span>
                                            <input class="form-control" type="text" name="account_number" 
                                                   value="{{ old('account_number', $storePaymentMethod->account_number ?? '') }}" 
                                                   required placeholder="رقم الحساب">
                                        </div>
                                    </div>
                                </div>

                                <!-- IBAN -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">IBAN</label>
                                        <div class="form-group">
                                            <span class="la la-file-text form-icon"></span>
                                            <input class="form-control" type="text" name="iban" 
                                                   value="{{ old('iban', $storePaymentMethod->iban ?? '') }}" 
                                                   placeholder="رقم IBAN">
                                        </div>
                                    </div>
                                </div>

                                <!-- الوصف -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الوصف</label>
                                        <div class="form-group">
                                            <span class="la la-info form-icon"></span>
                                            <input class="form-control" type="text" name="description" 
                                                   value="{{ old('description', $storePaymentMethod->description ?? '') }}" 
                                                   placeholder="الوصف">
                                        </div>
                                    </div>
                                </div>

                                <!-- الحالة -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الحالة</label>
                                        <div class="form-group d-flex align-items-center pt-2">
                                            <label for="is_active" class="radio-trigger mb-0 font-size-14 me-3">
                                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                                                       {{ old('is_active', $storePaymentMethod->is_active ?? 1) ? 'checked' : '' }}>
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
                    <div class="submit-box">
                        <div class="btn-box pt-3">
                            <button type="submit" class="theme-btn">{{ isset($storePaymentMethod) ? 'تحديث طريقة الدفع' : 'حفظ طريقة الدفع' }} <i class="la la-arrow-right ms-1"></i></button>
                            <a href="{{ route('seller.storePaymentMethods.index') }}" class="theme-btn theme-btn-small bg-secondary">إلغاء</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
