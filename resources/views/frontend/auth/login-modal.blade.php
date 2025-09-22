@extends('frontend.home.layouts.master')

@section('title', 'تسجيل الدخول')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8">
            <div class="card shadow-lg rounded-3">
                <div class="card-header text-center bg-primary text-white">
                    <h4>تسجيل الدخول</h4>
                    <p class="mb-0">مرحبا! ادخل إلى حسابك</p>
                </div>
                <div class="card-body">

                    {{-- رسالة خطأ من السيشن --}}
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    {{-- الأخطاء --}}
                    @if($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">البريد الإلكتروني</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="la la-user"></i></span>
                                <input type="email" name="email" class="form-control"
                                       placeholder="اكتب بريدك الإلكتروني" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">كلمة المرور</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="la la-lock"></i></span>
                                <input type="password" name="password" class="form-control"
                                       placeholder="اكتب كلمة المرور" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rememberchb" name="remember">
                                <label class="form-check-label" for="rememberchb">تذكرني</label>
                            </div>
                            <a href="#" class="text-decoration-none">هل نسيت كلمة المرور؟</a>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">تسجيل الدخول</button>
                    </form>

                    <hr>
                    <div class="text-center">
                        <p class="mb-2">ليس لديك حساب؟</p>
                        <a href="" class="btn btn-outline-success w-100">سجل الآن</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
