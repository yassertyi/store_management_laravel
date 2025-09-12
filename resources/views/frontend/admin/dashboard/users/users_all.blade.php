@extends('frontend.admin.dashboard.index')
@section('title')
المستخدمين
@endsection
@section('page_title')
ادارة المستخدمين
@endsection
@section('contects')
<br><br><br>
<div class="dashboard-main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-box">
                    <div class="form-title-wrap d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="title">قائمة المستخدمين</h3>
                            <p class="font-size-14">
                                إظهار {{ $users->firstItem() }} إلى {{ $users->lastItem() }} من أصل {{ $users->total() }} مُدخل
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('admin.users.create') }}" class="theme-btn theme-btn-small">
                                <i class="la la-plus"></i> مستخدم جديد
                            </a>
                        </div>
                    </div>

                    <div class="form-content">
                        <div class="table-form table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">رقم</th>
                                        <th scope="col">الاسم</th>
                                        <th scope="col">البريد الإلكتروني</th>
                                        <th scope="col">رقم الجوال</th>
                                        <th scope="col">نوع المستخدم</th>
                                        <th scope="col">الحالة</th>
                                        <th scope="col">عمل</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <th scope="row">{{ $user->user_id }}</th>
                                        
                                        {{-- الاسم + الصورة + الجنس --}}
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($user->profile_photo)
                                                    <img src="{{ asset($user->profile_photo) }}" 
                                                         alt="صورة {{ $user->name }}" 
                                                         class="rounded-circle me-2" width="40" height="40">
                                                @else
                                                    <img src="{{ asset('static/images/Default_pfp.jpg') }}" 
                                                         alt="صورة افتراضية" 
                                                         class="rounded-circle me-2" width="40" height="40">
                                                @endif
                                                <div>
                                                    <h3 class="title mb-0">{{ $user->name }}</h3>
                                                    @if($user->gender === 0)
                                                        <small class="text-muted">ذكر</small>
                                                    @elseif($user->gender === 1)
                                                        <small class="text-muted">أنثى</small>
                                                    @else
                                                        <small class="text-muted">غير محدد</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        {{-- البريد --}}
                                        <td>{{ $user->email }}</td>

                                        {{-- رقم الجوال --}}
                                        <td>{{ $user->phone ?? 'غير محدد' }}</td>

                                        {{-- نوع المستخدم --}}
                                        <td>
                                            @if($user->user_type === 0)
                                                <span class="badge text-bg-info py-1 px-2">عميل</span>
                                            @elseif($user->user_type === 1)
                                                <span class="badge text-bg-primary py-1 px-2">بائع</span>
                                            @elseif($user->user_type === 2)
                                                <span class="badge text-bg-success py-1 px-2">مسؤول</span>
                                            @endif
                                        </td>

                                        {{-- الحالة --}}
                                        <td>
                                            @if($user->active)
                                                <span class="badge text-bg-success py-1 px-2">نشيط</span>
                                            @else
                                                <span class="badge text-bg-danger py-1 px-2">غير نشط</span>
                                            @endif
                                        </td>

                                        {{-- الأكشن --}}
                                        <td>
                                            <div class="table-content d-flex">
                                                <a href="#" class="theme-btn theme-btn-small me-2" 
                                                   data-bs-toggle="tooltip" title="عرض">
                                                    <i class="la la-eye"></i>
                                                </a>

                                                <a href="{{ route('admin.users.edit', $user->user_id) }}" 
                                                   class="theme-btn theme-btn-small me-2" 
                                                   data-bs-toggle="tooltip" title="تعديل">
                                                    <i class="la la-edit"></i>
                                                </a>

                                                <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST" 
                                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="theme-btn theme-btn-small bg-danger" 
                                                            data-bs-toggle="tooltip" title="حذف">
                                                        <i class="la la-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- end form-box -->
            </div>
        </div>

        {{-- Pagination --}}
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        {{-- الصفحة السابقة --}}
                        <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $users->previousPageUrl() }}">
                                <i class="la la-angle-left"></i>
                            </a>
                        </li>

                        {{-- أرقام الصفحات --}}
                        @for ($i = 1; $i <= $users->lastPage(); $i++)
                            <li class="page-item {{ $users->currentPage() == $i ? 'active' : '' }}">
                                <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        {{-- الصفحة التالية --}}
                        <li class="page-item {{ !$users->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $users->nextPageUrl() }}">
                                <i class="la la-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection
