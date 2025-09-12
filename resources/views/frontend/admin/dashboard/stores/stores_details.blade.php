@extends('frontend.admin.dashboard.index')

@section('title', 'تفاصيل المتجر')
@section('page_title', 'تفاصيل المتجر')

@section('contects')
    <br><br><br>
    <div class="dashboard-main-content">
        <div class="container-fluid">
            <div class="container-fluid">
                @if (session('success'))
                    <div id="flash-message" class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div id="flash-message" class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                    </div>
                @endif

                <div class="form-box">
                    <div class="form-title-wrap d-flex justify-content-between align-items-center">
                        <h3 class="title">تفاصيل المتجر</h3>
                        <div>
                            <a href="{{ url()->previous() }}" class="theme-btn theme-btn-small bg-secondary me-2">
                                <i class="la la-arrow-right"></i> رجوع
                            </a>

                            <a href="{{ route('admin.stores.edit', $store->store_id) }}"
                                class="theme-btn theme-btn-small me-2">
                                <i class="la la-edit"></i> تعديل
                            </a>

                            <form action="{{ route('admin.stores.destroy', $store->store_id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('هل أنت متأكد من الحذف؟')"
                                    class="theme-btn theme-btn-small bg-danger">
                                    <i class="la la-trash"></i> حذف
                                </button>
                            </form>
                        </div>

                    </div>

                    <div class="form-content">
                        <div class="row">


                            <div class="col-lg-9">
                                <div class="section-tab section-tab-3 traveler-tab">
                                    <ul class="nav nav-tabs ms-0" id="storeTabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="store-detail-tab" data-bs-toggle="tab"
                                                href="#store-detail" role="tab" aria-controls="store-detail"
                                                aria-selected="true">
                                                تفاصيل المتجر
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="addresses-tab" data-bs-toggle="tab" href="#addresses"
                                                role="tab" aria-controls="addresses" aria-selected="false">
                                                العناوين
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="phones-tab" data-bs-toggle="tab" href="#phones"
                                                role="tab" aria-controls="phones" aria-selected="false">
                                                أرقام الهواتف
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="owner-tab" data-bs-toggle="tab" href="#owner"
                                                role="tab" aria-controls="owner" aria-selected="false">
                                                مالك المتجر
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="tab-content pt-4" id="storeTabContent">
                                    <!-- تفاصيل المتجر -->
                                    <div class="tab-pane fade show active" id="store-detail" role="tabpanel"
                                        aria-labelledby="store-detail-tab">
                                        <div class="profile-item mb-4">
                                            <!-- صورة شعار المتجر -->
                                            <div class="col-lg-3 text-center mb-4">
                                                @if ($store->logo && file_exists(public_path('static/images/stors/' . $store->logo)))
                                                    <img src="{{ asset('static/images/stors/' . $store->logo) }}"
                                                        alt="شعار المتجر" class="img-fluid rounded shadow-sm"
                                                        style="max-height: 180px;">
                                                @else
                                                    <img src="{{ asset('static/images/air-france.png') }}"
                                                        alt="شعار افتراضي" class="img-fluid rounded shadow-sm"
                                                        style="max-height: 180px;">
                                                @endif
                                            </div>
                                            <h3 class="title">معلومات المتجر</h3>
                                            <div class="row pt-3">
                                                <div class="col-lg-6">

                                                    <ul class="list-items list-items-2 list-items-3">
                                                        <li><span>اسم المتجر:</span> {{ $store->store_name }}</li>
                                                        <li><span>صاحب المتجر:</span> {{ $store->user->name }}</li>
                                                        <li><span>البريد الإلكتروني:</span> {{ $store->user->email }}</li>
                                                        <li><span>تاريخ الإنشاء:</span>
                                                            {{ $store->created_at->format('Y/m/d') }}</li>
                                                    </ul>
                                                </div>
                                                <div class="col-lg-6">
                                                    <ul class="list-items list-items-2 list-items-3">
                                                        <li><span>تاريخ آخر تحديث:</span>
                                                            {{ $store->updated_at->format('Y/m/d') }}</li>
                                                        <li>
                                                            <span>الحالة:</span>
                                                            @if ($store->status == 'active')
                                                                <span class="badge text-bg-success py-1 px-2">نشط</span>
                                                            @elseif($store->status == 'inactive')
                                                                <span class="badge text-bg-warning py-1 px-2">غير نشط</span>
                                                            @else
                                                                <span class="badge text-bg-danger py-1 px-2">موقوف</span>
                                                            @endif
                                                        </li>
                                                        <li><span>عدد العناوين:</span> {{ $store->addresses->count() }}
                                                        </li>
                                                        <li><span>عدد أرقام الهواتف:</span> {{ $store->phones->count() }}
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="profile-item">
                                            <h3 class="title">وصف المتجر</h3>
                                            <div class="pt-3">
                                                <p>{{ $store->description ?? 'لا يوجد وصف للمتجر' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- تبويب العناوين -->
                                    <div class="tab-pane fade" id="addresses" role="tabpanel"
                                        aria-labelledby="addresses-tab">
                                        <div class="profile-item">
                                            <h3 class="title mb-3">عناوين المتجر</h3>
                                            <div class="table-form table-responsive">
                                                @if ($store->addresses->count() > 0)
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>البلد</th>
                                                                <th>المدينة</th>
                                                                <th>الشارع</th>
                                                                <th>الرمز البريدي</th>
                                                                <th>العنوان الرئيسي</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($store->addresses as $address)
                                                                <tr>
                                                                    <td>{{ $address->country }}</td>
                                                                    <td>{{ $address->city }}</td>
                                                                    <td>{{ $address->street ?? 'غير محدد' }}</td>
                                                                    <td>{{ $address->zip_code ?? 'غير محدد' }}</td>
                                                                    <td>
                                                                        @if ($address->is_primary)
                                                                            <span class="badge text-bg-success">نعم</span>
                                                                        @else
                                                                            <span class="badge text-bg-secondary">لا</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @else
                                                    <div class="alert alert-info">لا توجد عناوين لهذا المتجر</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- تبويب الهواتف -->
                                    <div class="tab-pane fade" id="phones" role="tabpanel"
                                        aria-labelledby="phones-tab">
                                        <div class="profile-item">
                                            <h3 class="title mb-3">أرقام هواتف المتجر</h3>
                                            <div class="table-form table-responsive">
                                                @if ($store->phones->count() > 0)
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>رقم الهاتف</th>
                                                                <th>رئيسي</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($store->phones as $phone)
                                                                <tr>
                                                                    <td>{{ $phone->phone }}</td>
                                                                    <td>
                                                                        @if ($phone->is_primary)
                                                                            <span class="badge text-bg-success">نعم</span>
                                                                        @else
                                                                            <span class="badge text-bg-secondary">لا</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @else
                                                    <div class="alert alert-info">لا توجد أرقام هواتف لهذا المتجر</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- تبويب مالك المتجر -->
                                    <div class="tab-pane fade" id="owner" role="tabpanel"
                                        aria-labelledby="owner-tab">
                                        <div class="profile-item">
                                            <h3 class="title">معلومات مالك المتجر</h3>
                                            <div class="row pt-3">
                                                <div class="col-lg-3 text-center">
                                                    @if ($store->user->profile_photo)
                                                        <img src="{{ asset($store->user->profile_photo) }}"
                                                            alt="صورة {{ $store->user->name }}"
                                                            class="img-fluid rounded-circle shadow-sm mb-3"
                                                            style="max-height: 150px;">
                                                    @else
                                                        <img src="{{ asset('static/images/avatar.jpeg') }}"
                                                            alt="صورة افتراضية"
                                                            class="img-fluid rounded-circle shadow-sm mb-3"
                                                            style="max-height: 150px;">
                                                    @endif


                                                </div>
                                                <div class="col-lg-9">
                                                    <ul class="list-items list-items-2 list-items-3">
                                                        <li><span>الاسم:</span> {{ $store->user->name }}</li>
                                                        <li><span>البريد الإلكتروني:</span> {{ $store->user->email }}</li>
                                                        <li><span>رقم الهاتف:</span>
                                                            {{ $store->user->phone ?? 'غير محدد' }}</li>
                                                        <li><span>نوع المستخدم:</span>
                                                            @if ($store->user->user_type == 'customer')
                                                                عميل
                                                            @elseif($store->user->user_type == 'owner')
                                                                بائع
                                                            @else
                                                                مسؤول
                                                            @endif
                                                        </li>
                                                        <li><span>حالة الحساب:</span>
                                                            @if ($store->user->active)
                                                                <span class="badge text-bg-success">نشط</span>
                                                            @else
                                                                <span class="badge text-bg-danger">غير نشط</span>
                                                            @endif
                                                        </li>
                                                        <li><span>تاريخ التسجيل:</span>
                                                            {{ $store->user->created_at->format('Y/m/d') }}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- tab-content -->
                            </div>
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
            if (flash) {
                flash.style.transition = "opacity 0.5s ease";
                flash.style.opacity = '0';
                setTimeout(() => flash.remove(), 500);
            }
        }, 3000);
    </script>
@endsection
