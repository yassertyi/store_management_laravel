{{-- resources/views/frontend/admin/content/brands/form.blade.php --}}
@extends('frontend.admin.dashboard.index')

@section('title', isset($brand) ? 'تعديل علامة تجارية' : 'إضافة علامة تجارية جديدة')
@section('page_title', 'إدارة العلامات التجارية - ' . (isset($brand) ? 'تعديل' : 'إضافة جديد'))

@section('content')
<section class="listing-form section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">

                <div class="listing-header pb-4">
                    <h3 class="title font-size-28 pb-2">{{ isset($brand) ? 'تعديل العلامة التجارية' : 'إضافة علامة تجارية جديدة' }}</h3>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                    </div>
                @endif

                <form action="{{ isset($brand) ? route('admin.content.brands.update', $brand) : route('admin.content.brands.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($brand)) @method('PUT') @endif

                    <div class="form-box">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-tag me-2 text-gray"></i>معلومات العلامة التجارية</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div class="row">
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">اسم العلامة التجارية *</label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $brand->name ?? '') }}" required>
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">شعار العلامة التجارية</label>
                                        <input type="file" name="logo" class="form-control" accept="image/*">
                                        <small class="form-text text-muted">الصيغ المسموحة: JPEG, PNG, JPG, GIF - الحجم الأقصى: 2MB</small>
                                        
                                        @if(isset($brand) && $brand->logo)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="img-thumbnail" style="max-height: 100px;">
                                                <div class="form-check mt-1">
                                                    <input class="form-check-input" type="checkbox" name="remove_logo" id="remove_logo">
                                                    <label class="form-check-label text-danger" for="remove_logo">
                                                        حذف الشعار الحالي
                                                    </label>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الموقع الإلكتروني</label>
                                        <input type="url" name="website" class="form-control" value="{{ old('website', $brand->website ?? '') }}" placeholder="https://example.com">
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">ترتيب العرض</label>
                                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $brand->sort_order ?? 0) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="submit-box mt-3">
                        <button type="submit" class="theme-btn">{{ isset($brand) ? 'تحديث العلامة التجارية' : 'حفظ العلامة التجارية' }}</button>
                        <a href="{{ route('admin.content.brands.index') }}" class="theme-btn theme-btn-small bg-secondary">إلغاء</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>
@endsection