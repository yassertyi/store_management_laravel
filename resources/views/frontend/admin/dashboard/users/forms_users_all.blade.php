@extends('frontend.admin.dashboard.index')

@section('title')
{{ isset($user) ? 'تعديل مستخدم' : 'إضافة مستخدم جديد' }}
@endsection

@section('page_title')
إدارة المستخدمين - {{ isset($user) ? 'تعديل' : 'إضافة جديد' }}
@endsection

@section('content')
<section class="listing-form section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">
                <div class="listing-header pb-4">
                    <h3 class="title font-size-28 pb-2">{{ isset($user) ? 'تعديل المستخدم' : 'إضافة مستخدم جديد' }}</h3>
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

                <form action="{{ isset($user) ? route('admin.users.update', $user->user_id) : route('admin.users.store') }}" 
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($user))
                        @method('PUT')
                    @endif

                    <div class="form-box">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-user me-2 text-gray"></i>معلومات المستخدم</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div class="row">
                                <!-- الاسم -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الاسم الكامل *</label>
                                        <div class="form-group">
                                            <span class="la la-user form-icon"></span>
                                            <input class="form-control" type="text" name="name" 
                                                   value="{{ old('name', $user->name ?? '') }}" required placeholder="الاسم الكامل">
                                        </div>
                                    </div>
                                </div>
                                <!-- البريد الإلكتروني -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">البريد الإلكتروني *</label>
                                        <div class="form-group">
                                            <span class="la la-envelope-o form-icon"></span>
                                            <input class="form-control" type="email" name="email" 
                                                   value="{{ old('email', $user->email ?? '') }}" required placeholder="البريد الإلكتروني">
                                        </div>
                                    </div>
                                </div>
                                <!-- كلمة المرور -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">{{ isset($user) ? 'كلمة المرور (تغيير فقط)' : 'كلمة المرور *' }}</label>
                                        <div class="form-group">
                                            <span class="la la-lock form-icon"></span>
                                            <input class="form-control" type="password" name="password" 
                                                   {{ isset($user) ? '' : 'required' }} placeholder="كلمة المرور">
                                        </div>
                                    </div>
                                </div>
                                <!-- تأكيد كلمة المرور -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">{{ isset($user) ? 'تأكيد كلمة المرور (تغيير فقط)' : 'تأكيد كلمة المرور *' }}</label>
                                        <div class="form-group">
                                            <span class="la la-lock form-icon"></span>
                                            <input class="form-control" type="password" name="password_confirmation" 
                                                   {{ isset($user) ? '' : 'required' }} placeholder="تأكيد كلمة المرور">
                                        </div>
                                    </div>
                                </div>
                                <!-- الهاتف -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">رقم الجوال</label>
                                        <div class="form-group">
                                            <span class="la la-phone form-icon"></span>
                                            <input class="form-control" type="text" name="phone" 
                                                   value="{{ old('phone', $user->phone ?? '') }}" placeholder="رقم الجوال">
                                        </div>
                                    </div>
                                </div>
                                <!-- نوع المستخدم -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">نوع المستخدم *</label>
                                        <div class="form-group select2-container-wrapper select-contain w-100">
                                            <select class="select-contain-select" name="user_type" required>
                                                <option value="">اختر نوع المستخدم</option>
                                                <option value="0" {{ old('user_type', $user->user_type ?? '') == 0 ? 'selected' : '' }}>عميل</option>
                                                <option value="1" {{ old('user_type', $user->user_type ?? '') == 1 ? 'selected' : '' }}>بائع</option>
                                                <option value="2" {{ old('user_type', $user->user_type ?? '') == 2 ? 'selected' : '' }}>مسؤول</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- الجنس -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الجنس</label>
                                        <div class="form-group select2-container-wrapper select-contain w-100">
                                            <select class="select-contain-select" name="gender">
                                                <option value="">اختر الجنس</option>
                                                <option value="0" {{ old('gender', $user->gender ?? '') == 0 ? 'selected' : '' }}>ذكر</option>
                                                <option value="1" {{ old('gender', $user->gender ?? '') == 1 ? 'selected' : '' }}>أنثى</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- صورة الملف الشخصي -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">صورة الملف الشخصي</label>
                                        <div class="form-group">
                                            <input type="file" name="profile_photo" class="form-control-file">
                                            @if(isset($user) && $user->profile_photo)
                                                <img src="{{ asset($user->profile_photo) }}" width="80" class="mt-2 rounded">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- الحالة -->
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الحالة</label>
                                        <div class="form-group d-flex align-items-center pt-2">
                                            <label for="active" class="radio-trigger mb-0 font-size-14 me-3">
                                                <input type="checkbox" class="form-check-input" id="active" name="active" value="1" 
                                                       {{ old('active', $user->active ?? 1) ? 'checked' : '' }}>
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
                            <button type="submit" class="theme-btn">{{ isset($user) ? 'تحديث المستخدم' : 'حفظ المستخدم' }} <i class="la la-arrow-right ms-1"></i></button>
                            <a href="{{ route('admin.users.index') }}" class="theme-btn theme-btn-small bg-secondary">إلغاء</a>
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
    .radio-trigger {
        display: flex;
        align-items: center;
        cursor: pointer;
    }
    .radio-trigger input[type="checkbox"],
    .radio-trigger input[type="radio"] {
        display: none;
    }
    .radio-trigger .checkmark {
        width: 18px;
        height: 18px;
        border: 1px solid #e4e4e4;
        border-radius: 3px;
        margin-right: 8px;
        position: relative;
    }
    .radio-trigger input:checked + .checkmark:after {
        content: "";
        position: absolute;
        width: 10px;
        height: 10px;
        background: #3db565;
        border-radius: 2px;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
</style>
@endsection