@extends('frontend.admin.dashboard.index')
@section('title')
تعديل بيانات المستخدم
@endsection
@section('page_title')
تعديل بيانات المستخدم
@endsection
@section('contects')
<br><br><br>
<div class="dashboard-main-content">
            @if (session('success'))
            <div id="flash-message" class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div id="flash-message" class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    <div class="container-fluid">
        <div class="row">

            <!-- تعديل البيانات الأساسية -->
            <div class="col-lg-6">
                <div class="form-box">
                    <div class="form-title-wrap">
                        <h3 class="title">إعداد الملف الشخصي</h3>
                    </div>
                    <div class="form-content">
                        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="user-profile-action d-flex align-items-center pb-4">
                                <div class="user-pro-img">
                                    <img src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('static/images/users/default.png') }}" alt="user-image" />
                                </div>
                                <div class="upload-btn-box">
                                    <input type="file" name="profile_photo" class="filer_input" />
                                    @error('profile_photo')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الاسم</label>
                                        <div class="form-group">
                                            <span class="la la-user form-icon"></span>
                                            <input class="form-control" type="text" name="name" value="{{ old('name', $user->name) }}" />
                                            @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">البريد الإلكتروني</label>
                                        <div class="form-group">
                                            <span class="la la-envelope form-icon"></span>
                                            <input class="form-control" type="email" name="email" value="{{ old('email', $user->email) }}" />
                                            @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الهاتف</label>
                                        <div class="form-group">
                                            <span class="la la-phone form-icon"></span>
                                            <input class="form-control" type="text" name="phone" value="{{ old('phone', $user->phone) }}" />
                                            @error('phone')<span class="text-danger">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- الجنس -->
                                <div class="col-lg-6 responsive-column mb-3">
                                    <div class="input-box">
                                        <label class="label-text">الجنس</label>
                                        <select name="gender" class="form-control">
                                            <option value="">اختر الجنس</option>
                                            <option value="0" {{ old('gender', $user->gender) === 0 ? 'selected' : '' }}>ذكر</option>
                                            <option value="1" {{ old('gender', $user->gender) === 1 ? 'selected' : '' }}>أنثى</option>
                                        </select>
                                        @error('gender')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="btn-box">
                                        <button class="theme-btn" type="submit">حفظ التغييرات</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- تغيير كلمة المرور -->
            <div class="col-lg-6">
                <div class="form-box">
                    <div class="form-title-wrap">
                        <h3 class="title">تغيير كلمة المرور</h3>
                    </div>
                    <div class="form-content">
                        <form action="{{ route('admin.profile.updatePassword') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">كلمة السر الجديدة</label>
                                        <div class="form-group">
                                            <span class="la la-lock form-icon"></span>
                                            <input class="form-control" type="password" name="password" placeholder="كلمة المرور الجديدة" />
                                            @error('password')<span class="text-danger">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">تأكيد كلمة السر</label>
                                        <div class="form-group">
                                            <span class="la la-lock form-icon"></span>
                                            <input class="form-control" type="password" name="password_confirmation" placeholder="تأكيد كلمة المرور" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="btn-box">
                                        <button class="theme-btn" type="submit">تغيير كلمة السر</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('script_sdebar')
<script>
    setTimeout(function() {
        const flash = document.getElementById('flash-message');
        if(flash){
            flash.style.transition = "opacity 0.5s ease";
            flash.style.opacity = '0';
            setTimeout(()=> flash.remove(), 500);
        }
    }, 3000);
</script>
@endsection