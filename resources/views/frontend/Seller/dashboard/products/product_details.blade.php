@extends('frontend.Seller.dashboard.index')

@section('title', 'تفاصيل المنتج')
@section('page_title', 'تفاصيل المنتج')

@section('contects')
    <br><br><br>
    <div class="dashboard-main-content">
        <div class="container-fluid">

            @if (session('success'))
                <div id="flash-message" class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                </div>
            @endif

            <div class="form-box">
                <div class="form-title-wrap d-flex justify-content-between align-items-center">
                    <h3 class="title">تفاصيل المنتج</h3>
                    <div>
                        <a href="{{ route('seller.products.index') }}" class="theme-btn theme-btn-small bg-secondary me-2">
                            <i class="la la-arrow-right"></i> رجوع
                        </a>

                        <form action="{{ route('seller.products.destroy', $product->product_id) }}" method="POST"
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
                                <ul class="nav nav-tabs ms-0" id="productTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="product-detail-tab" data-bs-toggle="tab"
                                            href="#product-detail" role="tab" aria-controls="product-detail"
                                            aria-selected="true">
                                            تفاصيل المنتج
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="images-tab" data-bs-toggle="tab" href="#images"
                                            role="tab" aria-controls="images" aria-selected="false">
                                            الصور
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="variants-tab" data-bs-toggle="tab" href="#variants"
                                            role="tab" aria-controls="variants" aria-selected="false">
                                            المتغيرات
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="tab-content pt-4" id="productTabContent">
                                <!-- تفاصيل المنتج -->
                                <div class="tab-pane fade show active" id="product-detail" role="tabpanel"
                                    aria-labelledby="product-detail-tab">
                                    <div class="profile-item mb-4">
                                        <h3 class="title">معلومات المنتج</h3>
                                        <ul class="list-items list-items-2 list-items-3">
                                            <li><span>اسم المنتج:</span> {{ $product->title }}</li>
                                            <li><span>المتجر:</span> {{ $product->store->store_name ?? auth()->user()->store->store_name }}</li>
                                            <li><span>الفئة:</span> {{ $product->category->name ?? 'غير محدد' }}</li>
                                            <li><span>السعر:</span> {{ number_format($product->price, 2) }}</li>
                                            <li><span>SKU:</span> {{ $product->sku }}</li>
                                            <li><span>الباركود:</span> {{ $product->barcode ?? 'غير محدد' }}</li>
                                            <li><span>الحالة:</span>
                                                @if ($product->status === 'active')
                                                    <span class="badge text-bg-success">نشط</span>
                                                @elseif($product->status === 'inactive')
                                                    <span class="badge text-bg-warning">غير نشط</span>
                                                @else
                                                    <span class="badge text-bg-danger">موقوف</span>
                                                @endif
                                            </li>
                                            <li><span>الكمية في المخزن:</span> {{ $product->stock ?? 0 }}</li>
                                            <li><span>الوزن:</span> {{ $product->weight ?? 0 }}</li>
                                            <li><span>تم التمييز كمنتج مميز:</span>
                                                {{ $product->is_featured ? 'نعم' : 'لا' }}</li>
                                            <li><span>تاريخ الإنشاء:</span> {{ $product->created_at->format('Y/m/d') }}
                                            </li>
                                            <li><span>تاريخ آخر تحديث:</span> {{ $product->updated_at->format('Y/m/d') }}
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="profile-item">
                                        <h3 class="title">وصف المنتج</h3>
                                        <div class="pt-3">
                                            <p>{{ $product->description ?? 'لا يوجد وصف للمنتج' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- الصور -->
                                <div class="tab-pane fade" id="images" role="tabpanel" aria-labelledby="images-tab">
                                    <div class="profile-item">
                                        <h3 class="title mb-3">صور المنتج</h3>
                                        @if ($product->images->count() > 0)
                                            <div class="row">
                                                @foreach ($product->images as $img)
                                                    <div class="col-lg-3 mb-3 text-center">
                                                        <img src="{{ asset($img->image_path) }}" alt="صورة المنتج" class="img-fluid rounded shadow-sm">

                                                        @if ($img->is_primary)
                                                            <div class="badge text-bg-success mt-1">رئيسية</div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="alert alert-info">لا توجد صور لهذا المنتج</div>
                                        @endif
                                    </div>
                                </div>

                                <!-- المتغيرات -->
                                <div class="tab-pane fade" id="variants" role="tabpanel" aria-labelledby="variants-tab">
                                    <div class="profile-item">
                                        <h3 class="title mb-3">متغيرات المنتج</h3>
                                        @if ($product->variants->count() > 0)
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>اسم المتغير</th>
                                                        <th>السعر</th>
                                                        <th>الكمية</th>
                                                        <th>SKU</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($product->variants as $var)
                                                        <tr>
                                                            <td>{{ $var->name }}</td>
                                                            <td>{{ number_format($var->price, 2) }}</td>
                                                            <td>{{ $var->stock }}</td>
                                                            <td>{{ $var->sku }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <div class="alert alert-info">لا توجد متغيرات لهذا المنتج</div>
                                        @endif
                                    </div>
                                </div>

                            </div> <!-- tab-content -->
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
