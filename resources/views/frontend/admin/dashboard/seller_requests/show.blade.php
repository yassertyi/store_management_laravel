@extends('frontend.admin.dashboard.index')

@section('page_title', 'تفاصيل طلب فتح حساب متجر')
@section('title', 'تفاصيل الطلب')

@section('contects')
    <br><br><br>

    {{-- CSS لتصغير صور المتجر فقط --}}
    <style>
        .store-images img {
            max-height: 70px;
            max-width: 70px;
            object-fit: cover;
            border: 1px solid #ddd;
            padding: 2px;
            border-radius: 6px;
            background: #fff;
        }

        /* صورة الملف الشخصي تبقى كبيرة */
        .card-img img {
            max-height: 350px;
            max-width: 250px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #ddd;
        }
    </style>

    <div class="dashboard-main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-box">
                        <div class="form-title-wrap d-flex justify-content-between align-items-center">
                            <h3 class="title">تفاصيل الطلب</h3>
                            <a href="{{ route('admin.seller-requests.index') }}" class="theme-btn theme-btn-small">
                                <i class="la la-arrow-left me-1"></i>العودة لقائمة الطلبات
                            </a>
                        </div>

                        <div class="form-content pb-2">
                            <div class="card-item card-item-list">
                                {{-- صورة الملف الشخصي --}}
                                <div class="card-img text-center mb-3">
                                    <img src="{{ $sellerRequest->profile_photo ? asset($sellerRequest->profile_photo) : asset('static/images/thrifty.png') }}"
                                        alt="{{ $sellerRequest->name }}" class="img-fluid" />
                                </div>

                                <div class="card-body">
                                    <h3 class="card-title">{{ $sellerRequest->name }}</h3>

                                    {{-- بيانات الطلب --}}
                                    <ul class="list-items list-items-2 pt-2 pb-3">
                                        <li><span>البريد الإلكتروني:</span> {{ $sellerRequest->email }}</li>
                                        <li><span>الهاتف:</span> {{ $sellerRequest->phone_number ?? $sellerRequest->phone }}
                                        </li>
                                        <li><span>جنس العميل:</span> {{ $sellerRequest->gender }}</li>
                                        <li><span>اسم المتجر:</span> {{ $sellerRequest->store_name }}</li>
                                        <li><span>وصف المتجر:</span> {{ $sellerRequest->store_description }}</li>
                                        <li><span>رقم الرخصة التجارية:</span> {{ $sellerRequest->business_license_number }}
                                        </li>

                                        {{-- الوثيقة --}}
                                        @if ($sellerRequest->document_path)
                                            <div class="mb-2">
                                                <strong>صورة الرخصة التجارية:</strong><br>
                                                <a href="{{ asset($sellerRequest->document_path) }}"
                                                    target="_blank" class="btn btn-sm btn-info">
                                                    عرض الصورة
                                                </a>
                                            </div>
                                        @endif

                                        <li><span>الدولة:</span> {{ $sellerRequest->country }}</li>
                                        <li><span>المدينة:</span> {{ $sellerRequest->city }}</li>
                                        <li><span>الشارع:</span> {{ $sellerRequest->street }}</li>
                                        <li><span>الرمز البريدي:</span> {{ $sellerRequest->zip_code }}</li>
                                        <li><span>حالة الطلب:</span>
                                            @if ($sellerRequest->status == 'pending')
                                                <span class="badge bg-warning">قيد الانتظار</span>
                                            @elseif($sellerRequest->status == 'approved')
                                                <span class="badge bg-success">موافق</span>
                                            @elseif($sellerRequest->status == 'rejected')
                                                <span class="badge bg-danger">مرفوض</span>
                                            @endif
                                        </li>
                                        @if ($sellerRequest->status == 'rejected')
                                            <li><span>سبب الرفض:</span> {{ $sellerRequest->rejection_reason }}</li>
                                        @endif
                                    </ul>

                                    {{-- صور المتجر --}}
                                    <div class="store-images mb-3">
                                        @if ($sellerRequest->logo)
                                            <div class="mb-2">
                                                <strong>شعار المتجر:</strong><br>
                                                <img src="{{ asset($sellerRequest->logo) }}" alt="شعار المتجر"
                                                    class="img-fluid me-2 mb-2">
                                            </div>
                                        @endif

                                        @if ($sellerRequest->banner)
                                            <div class="mb-2">
                                                <strong>بانر المتجر:</strong><br>
                                                <img src="{{ asset($sellerRequest->banner) }}" alt="بانر المتجر"
                                                    class="img-fluid me-2 mb-2">

                                            </div>
                                        @endif

                                        @if (!empty($sellerRequest->additional_images) && is_array($sellerRequest->additional_images))
                                            <div class="mb-2">
                                                <strong>صور إضافية:</strong><br>
                                                @foreach ($sellerRequest->additional_images as $img)
                                                    <img src="{{ asset($img) }}" alt="صورة إضافية"
                                                        class="img-fluid me-2 mb-2">
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted">لا توجد صور إضافية</p>
                                        @endif
                                    </div>

                                    {{-- أزرار الموافقة والرفض --}}
                                    <div class="btn-box mt-3 d-flex gap-2">
                                        @if ($sellerRequest->status !== 'approved')
                                            <form action="{{ route('admin.seller-requests.approve', $sellerRequest) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="theme-btn theme-btn-small bg-success">
                                                    <i class="la la-check me-1"></i> موافقة
                                                </button>
                                            </form>
                                        @endif

                                        @if ($sellerRequest->status !== 'rejected')
                                            <form action="{{ route('admin.seller-requests.reject', $sellerRequest) }}"
                                                method="POST" onsubmit="return confirmRejection(this)">
                                                @csrf
                                                <input type="hidden" name="reason" id="rejectionReason">
                                                <button type="submit" class="theme-btn theme-btn-small bg-danger">
                                                    <i class="la la-times me-1"></i> رفض
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                </div>
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
        function confirmRejection(form) {
            let reason = prompt("ادخل سبب رفض الطلب:");
            if (reason === null || reason.trim() === "") {
                alert("يجب إدخال سبب الرفض!");
                return false; // يمنع الإرسال
            }
            form.querySelector('#rejectionReason').value = reason;
            return true; // يسمح بالإرسال
        }
    </script>
@endsection
