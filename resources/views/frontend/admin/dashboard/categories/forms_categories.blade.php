@extends('frontend.admin.dashboard.index')

@section('title', isset($category) ? 'تعديل تصنيف' : 'إضافة تصنيف جديد')
@section('page_title', 'إدارة التصنيفات - ' . (isset($category) ? 'تعديل' : 'إضافة جديد'))

@section('content')
<section class="listing-form section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">
                <div class="listing-header pb-4">
                    <h3 class="title font-size-28 pb-2">{{ isset($category) ? 'تعديل التصنيف' : 'إضافة تصنيف جديد' }}</h3>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                    </div>
                @endif

                <form action="{{ isset($category) ? route('admin.categories.update', $category->category_id) : route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($category)) @method('PUT') @endif

                    <div class="form-box">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-tags me-2 text-gray"></i>معلومات التصنيف</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div class="row">
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">اسم التصنيف *</label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">التصنيف الأب</label>
                                        <select name="parent_id" class="form-control">
                                            <option value="">--- بدون أب ---</option>
                                            @foreach($parents as $parent)
                                                <option value="{{ $parent->category_id }}" {{ (old('parent_id', $category->parent_id ?? '') == $parent->category_id) ? 'selected' : '' }}>
                                                    {{ $parent->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الوصف</label>
                                        <textarea name="description" class="form-control">{{ old('description', $category->description ?? '') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الصورة</label>
                                        <input type="file" name="image" class="form-control-file">
                                        @if(isset($category) && $category->image)
                                            <img src="{{ asset('storage/'.$category->image) }}" width="80" class="mt-2 rounded">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الترتيب</label>
                                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $category->sort_order ?? 0) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="submit-box mt-3">
                        <button type="submit" class="theme-btn">{{ isset($category) ? 'تحديث التصنيف' : 'حفظ التصنيف' }}</button>
                        <a href="{{ route('admin.categories.index') }}" class="theme-btn theme-btn-small bg-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
