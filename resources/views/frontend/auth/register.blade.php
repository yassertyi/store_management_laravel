@extends('frontend.home.layouts.master')

@section('title', 'إنشاء حساب')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8">
            <div class="card shadow-lg rounded-3">
                <div class="card-header text-center bg-primary text-white">
                    <h4>سجل</h4>
                    <p class="mb-0">مرحبا! قم بإنشاء حساب جديد</p>
                </div>

                <div class="card-body">

                    {{-- الأخطاء --}}
                    @if($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('register.submit') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- اسم المستخدم -->
                        <div class="mb-3">
                            <label class="form-label">اسم المستخدم</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="la la-user"></i></span>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="اكتب اسم المستخدم الخاص بك" required>
                            </div>
                        </div>

                        <!-- البريد الالكتروني -->
                        <div class="mb-3">
                            <label class="form-label">البريد الإلكتروني</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="la la-envelope"></i></span>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="اكتب بريدك الإلكتروني" required>
                            </div>
                        </div>

                        <!-- كلمة المرور -->
                        <div class="mb-3">
                            <label class="form-label">كلمة المرور</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="la la-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="أدخل كلمة المرور" required>
                            </div>
                        </div>

                        <!-- تأكيد كلمة المرور -->
                        <div class="mb-3">
                            <label class="form-label">اعد كلمة المرور</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="la la-lock"></i></span>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="أعد كتابة كلمة المرور" required>
                            </div>
                        </div>

                        <!-- رقم الهاتف -->
                        <div class="mb-3">
                            <label class="form-label">رقم الهاتف</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="la la-phone"></i></span>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="اكتب رقم هاتفك">
                            </div>
                        </div>

                        <!-- الجنس -->
                        <div class="mb-3">
                            <label class="form-label">الجنس</label>
                            <select class="form-control" name="gender">
                                <option value="">اختر الجنس</option>
                                <option value="0" {{ old('gender')==='0'?'selected':'' }}>ذكر</option>
                                <option value="1" {{ old('gender')==='1'?'selected':'' }}>أنثى</option>
                            </select>
                        </div>

                        <!-- صورة البروفايل -->
                        <div class="mb-3">
                            <label class="form-label">صورة البروفايل</label>
                            <input type="file" class="form-control" name="profile_photo" accept="image/*" />
                        </div>

                        <!-- نقاط الولاء -->
                        <div class="mb-3">
                            <label class="form-label">نقاط الولاء (Loyalty Points)</label>
                            <input type="number" name="loyalty_points" class="form-control" value="{{ old('loyalty_points',0) }}" />
                            <small class="text-muted">هذه النقاط تمنحك مزايا وعروض خاصة عند كل عملية شراء.</small>
                        </div>

                        <!-- إجمالي الطلبات -->
                        <div class="mb-3">
                            <label class="form-label">إجمالي الطلبات</label>
                            <input type="number" name="total_orders" class="form-control" value="{{ old('total_orders',0) }}" />
                            <small class="text-muted">عدد الطلبات التي قمت بها سابقًا، لمتابعة تاريخ مشترياتك.</small>
                        </div>

                        <!-- زر التسجيل -->
                        <button type="submit" class="btn btn-primary w-100 mb-3">تسجيل حساب</button>
                    </form>

                    <div class="text-center">
                        <p class="mb-2">لدي حساب؟</p>
                        <a href="{{ route('login.form') }}" class="btn btn-outline-success w-100">تسجيل الدخول</a>
                    </div>

                    <!-- التسجيل باستخدام حسابات التواصل -->
                    <div class="action-box text-center mt-3">
                        <p class="font-size-14">أو قم بالتسجيل باستخدام</p>
                        <ul class="social-profile py-3">
                            <li><a href="#" class="bg-5 text-white"><i class="lab la-facebook-f"></i></a></li>
                            <li><a href="#" class="bg-6 text-white"><i class="lab la-twitter"></i></a></li>
                            <li><a href="#" class="bg-7 text-white"><i class="lab la-instagram"></i></a></li>
                            <li><a href="#" class="bg-5 text-white"><i class="lab la-linkedin-in"></i></a></li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
