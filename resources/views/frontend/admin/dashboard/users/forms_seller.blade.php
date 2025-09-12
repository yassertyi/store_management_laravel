@extends('frontend.admin.dashboard.index')
@section('title')
{{ isset($seller) ? 'تعديل بائع' : 'إضافة بائع جديد' }}
@endsection
@section('page_title')
إدارة البائعين - {{ isset($seller) ? 'تعديل' : 'إضافة جديد' }}
@endsection
@section('contects')
<section class="listing-form section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">
                <div class="listing-header pb-4">
                    <h3 class="title font-size-28 pb-2">{{ isset($seller) ? 'تعديل البائع' : 'إضافة بائع جديد' }}</h3>
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

                <form action="{{ isset($seller) ? route('admin.sellers.update', $seller->seller_id) : route('admin.sellers.store') }}" 
                      method="POST">
                    @csrf
                    @if(isset($seller))
                        @method('PUT')
                    @endif
                    
                    <div class="form-box">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-store me-2 text-gray"></i>معلومات البائع</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div class="row">
                                {{-- اختيار المستخدم --}}
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">اختر المستخدم *</label>
                                        <div class="form-group select2-container-wrapper select-contain w-100">
                                            <select class="select-contain-select" name="user_id" required>
                                                <option value="">اختر المستخدم</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->user_id }}" 
                                                        {{ old('user_id', $seller->user_id ?? '') == $user->user_id ? 'selected' : '' }}>
                                                        {{ $user->name }} ({{ $user->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- اختيار المتجر --}}
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">اختر المتجر *</label>
                                        <div class="form-group select2-container-wrapper select-contain w-100">
                                            <select class="select-contain-select" name="store_id" required>
                                                <option value="">اختر المتجر</option>
                                                @foreach($stores as $store)
                                                    <option value="{{ $store->store_id }}" 
                                                        {{ old('store_id', $seller->store_id ?? '') == $store->store_id ? 'selected' : '' }}>
                                                        {{ $store->store_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- نسبة العمولة --}}
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">نسبة العمولة (%)</label>
                                        <div class="form-group">
                                            <span class="la la-percent form-icon"></span>
                                            <input class="form-control" type="number" step="0.01" name="commission_rate" 
                                                   value="{{ old('commission_rate', $seller->commission_rate ?? 0) }}" 
                                                   placeholder="مثال: 5">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="submit-box">
                        <div class="btn-box pt-3">
                            <button type="submit" class="theme-btn">{{ isset($seller) ? 'تحديث البائع' : 'حفظ البائع' }} <i class="la la-arrow-right ms-1"></i></button>
                            <a href="{{ route('admin.sellers.index') }}" class="theme-btn theme-btn-small bg-secondary">إلغاء</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    .select-contain {
        position: relative;
    }
    .select-contain-select {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #e4e4e4;
        border-radius: 5px;
        appearance: none;
        background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M8 12L2 6h12L8 12z'/%3E%3C/svg%3E") no-repeat right 15px center;
    }
</style>
@endsection
