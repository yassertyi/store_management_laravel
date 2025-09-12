@extends('frontend.admin.dashboard.index')

@section('title')
البائعين
@endsection
@section('page_title')
إدارة البائعين
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
                            <h3 class="title">قائمة البائعين</h3>
                            <p class="font-size-14">
                                إظهار {{ $sellers->firstItem() }} إلى {{ $sellers->lastItem() }} من أصل {{ $sellers->total() }} مُدخل
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('admin.sellers.create') }}" class="theme-btn theme-btn-small">
                                <i class="la la-plus"></i> بائع جديد
                            </a>
                        </div>
                    </div>
                    <div class="form-content">
                        <div class="table-form table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">البائع</th>
                                        <th scope="col">البريد الإلكتروني</th>
                                        <th scope="col">رقم الجوال</th>
                                        <th scope="col">المتجر</th>
                                        <th scope="col">نسبة العمولة</th>
                                        <th scope="col">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sellers as $seller)
                                    <tr>
                                        <th scope="row">{{ $seller->seller_id }}</th>
                                        
                                        {{-- الاسم + الصورة --}}
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($seller->user->profile_photo)
                                                    <img src="{{ asset($seller->user->profile_photo) }}" 
                                                         alt="صورة {{ $seller->user->name }}" 
                                                         class="rounded-circle me-2" 
                                                         width="40" height="40">
                                                @else
                                                    <img src="{{ asset('static/images/Default_pfp.jpg') }}" 
                                                         alt="صورة افتراضية" 
                                                         class="rounded-circle me-2" 
                                                         width="40" height="40">
                                                @endif
                                                <div>
                                                    <h3 class="title mb-0">{{ $seller->user->name }}</h3>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- البريد --}}
                                        <td>{{ $seller->user->email }}</td>

                                        {{-- رقم الجوال --}}
                                        <td>{{ $seller->user->phone ?? 'غير محدد' }}</td>

                                        {{-- المتجر --}}
                                        <td>{{ $seller->store->store_name ?? 'غير محدد' }}</td>

                                        {{-- نسبة العمولة --}}
                                        <td>{{ $seller->commission_rate ?? 0 }}%</td>

                                        {{-- الأكشن --}}
                                        <td>
                                            <div class="table-content d-flex">
                                                {{-- زر تعديل --}}
                                                <a href="{{ route('admin.sellers.edit',$seller->seller_id) }}" 
                                                   class="theme-btn theme-btn-small me-2" 
                                                   data-bs-toggle="tooltip" title="تعديل">
                                                    <i class="la la-edit"></i>
                                                </a>

                                                {{-- زر حذف --}}
                                                <form action="{{ route('admin.sellers.destroy',$seller->seller_id) }}" method="POST" 
                                                      onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                                    @csrf @method('DELETE')
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
                        {{ $sellers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
