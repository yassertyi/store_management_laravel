{{-- resources/views/frontend/admin/content/testimonials/form.blade.php --}}
@extends('frontend.admin.dashboard.index')

@section('title', isset($testimonial) ? 'تعديل رأي عميل' : 'إضافة رأي عميل جديد')
@section('page_title', 'إدارة آراء العملاء - ' . (isset($testimonial) ? 'تعديل' : 'إضافة جديد'))

@section('content')
<section class="listing-form section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">

                <div class="listing-header pb-4">
                    <h3 class="title font-size-28 pb-2">{{ isset($testimonial) ? 'تعديل رأي العميل' : 'إضافة رأي عميل جديد' }}</h3>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                    </div>
                @endif

                <form action="{{ isset($testimonial) ? route('admin.content.testimonials.update', $testimonial) : route('admin.content.testimonials.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($testimonial)) @method('PUT') @endif

                    <div class="form-box">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-comment me-2 text-gray"></i>معلومات الرأي</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div class="row">
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">اسم العميل *</label>
                                        <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', $testimonial->customer_name ?? '') }}" required>
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">صورة العميل</label>
                                        <input type="file" name="customer_image" class="form-control" accept="image/*">
                                        <small class="form-text text-muted">الصيغ المسموحة: JPEG, PNG, JPG, GIF - الحجم الأقصى: 2MB</small>
                                        
                                        @if(isset($testimonial) && $testimonial->customer_image)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $testimonial->customer_image) }}" alt="{{ $testimonial->customer_name }}" class="img-thumbnail rounded-circle" style="max-height: 100px;">
                                                <div class="form-check mt-1">
                                                    <input class="form-check-input" type="checkbox" name="remove_customer_image" id="remove_customer_image">
                                                    <label class="form-check-label text-danger" for="remove_customer_image">
                                                        حذف الصورة الحالية
                                                    </label>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">المكان</label>
                                        <input type="text" name="location" class="form-control" value="{{ old('location', $testimonial->location ?? '') }}" placeholder="الرياض، السعودية">
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">التقييم *</label>
                                        <select name="rating" class="form-control" required>
                                            <option value="">اختر التقييم</option>
                                            @for($i = 1; $i <= 5; $i++)
                                                <option value="{{ $i }}" {{ old('rating', $testimonial->rating ?? '') == $i ? 'selected' : '' }}>
                                                    {{ $i }} نجوم
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-12 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">المحتوى *</label>
                                        <textarea name="content" class="form-control" rows="4" required>{{ old('content', $testimonial->content ?? '') }}</textarea>
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">ترتيب العرض</label>
                                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $testimonial->sort_order ?? 0) }}">
                                    </div>
                                </div>

                                <!-- إضافة حقل الحالة -->
                                @if(isset($testimonial))
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الحالة</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                                   {{ $testimonial->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                {{ $testimonial->is_active ? 'مفعل' : 'معطل' }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="submit-box mt-3">
                        <button type="submit" class="theme-btn">{{ isset($testimonial) ? 'تحديث الرأي' : 'حفظ الرأي' }}</button>
                        <a href="{{ route('admin.content.testimonials.index') }}" class="theme-btn theme-btn-small bg-secondary">إلغاء</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>
@endsection