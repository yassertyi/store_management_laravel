@extends('frontend.Seller.dashboard.index')

@section('title', isset($product) ? 'تعديل منتج' : 'إضافة منتج جديد')
@section('page_title', 'إدارة المنتجات - ' . (isset($product) ? 'تعديل' : 'إضافة جديد'))

@section('content')
<section class="listing-form section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">

                <div class="listing-header pb-4">
                    <h3 class="title font-size-28 pb-2">{{ isset($product) ? 'تعديل المنتج' : 'إضافة منتج جديد' }}</h3>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                    </div>
                @endif

                <form action="{{ isset($product) ? route('seller.products.update', $product->product_id) : route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($product)) @method('PUT') @endif

                    <!-- معلومات المنتج -->
                    <div class="form-box">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-cube me-2 text-gray"></i>معلومات المنتج</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div class="row">

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">اسم المنتج *</label>
                                        <input type="text" name="title" class="form-control" value="{{ old('title', $product->title ?? '') }}" required>
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">التصنيف *</label>
                                        <select name="category_id" class="form-control" required>
                                            <option value="">اختر التصنيف</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->category_id }}" {{ (old('category_id', $product->category_id ?? '') == $category->category_id) ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">حالة المنتج *</label>
                                        <select name="status" class="form-control" required>
                                            <option value="">اختر الحالة</option>
                                            <option value="active" {{ old('status', $product->status ?? '') == 'active' ? 'selected' : '' }}>نشط</option>
                                            <option value="inactive" {{ old('status', $product->status ?? '') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                            <option value="draft" {{ old('status', $product->status ?? '') == 'draft' ? 'selected' : '' }}>مسودة</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">تمييز المنتج كـ مميز</label>
                                        <select name="is_featured" class="form-control">
                                            <option value="0" {{ old('is_featured', $product->is_featured ?? 0) == 0 ? 'selected' : '' }}>لا</option>
                                            <option value="1" {{ old('is_featured', $product->is_featured ?? 0) == 1 ? 'selected' : '' }}>نعم</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-12 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">وصف المنتج</label>
                                        <textarea name="description" class="form-control">{{ old('description', $product->description ?? '') }}</textarea>
                                    </div>
                                </div>

                                <div class="col-lg-3 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">السعر *</label>
                                        <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $product->price ?? '') }}" required>
                                    </div>
                                </div>

                                <div class="col-lg-3 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">السعر المقارن</label>
                                        <input type="number" step="0.01" name="compare_price" class="form-control" value="{{ old('compare_price', $product->compare_price ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-lg-3 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">SKU</label>
                                        <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku ?? '') }}">
                                    </div>
                                </div>

                                <div class="col-lg-3 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الكمية</label>
                                        <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock ?? 0) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- صور المنتج -->
                    <div class="form-box mt-3">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-image me-2 text-gray"></i>صور المنتج</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div id="images-wrapper">
                                @php
                                    $images = old('images', isset($product) ? $product->images->map(function($img){
                                        return [
                                            'image_id' => $img->image_id,
                                            'image_path' => $img->image_path,
                                            'is_primary' => $img->is_primary
                                        ];
                                    })->toArray() : [['image_path'=>'','is_primary'=>0]]);
                                @endphp
                                @foreach($images as $index => $img)
                                    <div class="row image-row mb-2">
                                        <div class="col-lg-8">
                                            <input type="file" name="images[{{ $index }}][image_path]" class="form-control">
                                            @if(!empty($img['image_path']))
                                                <input type="hidden" name="images[{{ $index }}][image_id]" value="{{ $img['image_id'] ?? '' }}">
                                                <img src="{{ asset($img['image_path']) }}" width="80" class="mt-2 rounded">
                                            @endif
                                        </div>
                                        <div class="col-lg-3">
                                            <select name="images[{{ $index }}][is_primary]" class="form-control">
                                                <option value="0" {{ (!empty($img['is_primary']) && $img['is_primary']==0) ? 'selected' : '' }}>ثانوية</option>
                                                <option value="1" {{ (!empty($img['is_primary']) && $img['is_primary']==1) ? 'selected' : '' }}>رئيسية</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-1">
                                            <button type="button" class="btn {{ $loop->first ? 'btn-success add-image' : 'btn-danger remove-image' }}">
                                                {{ $loop->first ? '+' : '-' }}
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- متغيرات المنتج -->
                    <div class="form-box mt-3">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-cogs me-2 text-gray"></i>متغيرات المنتج</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div id="variants-wrapper">
                                @php
                                    $variants = old('variants', isset($product) ? $product->variants->toArray() : [['name'=>'','price'=>'','stock'=>'']]);
                                @endphp
                                @foreach($variants as $i => $variant)
                                    <div class="row variant-row mb-2">
                                        <div class="col-lg-4">
                                            <input type="text" name="variants[{{ $i }}][name]" class="form-control" placeholder="اسم المتغير" value="{{ $variant['name'] ?? '' }}">
                                        </div>
                                        <div class="col-lg-4">
                                            <input type="number" step="0.01" name="variants[{{ $i }}][price]" class="form-control" placeholder="السعر" value="{{ $variant['price'] ?? '' }}">
                                        </div>
                                        <div class="col-lg-3">
                                            <input type="number" name="variants[{{ $i }}][stock]" class="form-control" placeholder="الكمية" value="{{ $variant['stock'] ?? '' }}">
                                        </div>
                                        <div class="col-lg-1">
                                            <button type="button" class="btn {{ $loop->first ? 'btn-success add-variant' : 'btn-danger remove-variant' }}">{{ $loop->first ? '+' : '-' }}</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="redirect_to" value="{{ request('redirect_to', url()->previous()) }}">

                    <div class="submit-box mt-3">
                        <button type="submit" class="theme-btn">{{ isset($product) ? 'تحديث المنتج' : 'حفظ المنتج' }}</button>
                        <a href="{{ route('seller.products.index') }}" class="theme-btn theme-btn-small bg-secondary">إلغاء</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function(){

    // الصور
    document.getElementById('images-wrapper').addEventListener('click', function(e){
        if(e.target.classList.contains('add-image')){
            const wrapper = document.getElementById('images-wrapper');
            const index = wrapper.querySelectorAll('.image-row').length;
            const div = document.createElement('div');
            div.classList.add('row','image-row','mb-2');
            div.innerHTML = `
                <div class="col-lg-8">
                    <input type="file" name="images[${index}][image_path]" class="form-control">
                </div>
                <div class="col-lg-3">
                    <select name="images[${index}][is_primary]" class="form-control">
                        <option value="0">ثانوية</option>
                        <option value="1">رئيسية</option>
                    </select>
                </div>
                <div class="col-lg-1">
                    <button type="button" class="btn btn-danger remove-image">-</button>
                </div>`;
            wrapper.appendChild(div);
        }
        if(e.target.classList.contains('remove-image')){
            e.target.closest('.image-row').remove();
        }
    });

    // المتغيرات
    document.getElementById('variants-wrapper').addEventListener('click', function(e){
        if(e.target.classList.contains('add-variant')){
            const wrapper = document.getElementById('variants-wrapper');
            const index = wrapper.querySelectorAll('.variant-row').length;
            const div = document.createElement('div');
            div.classList.add('row','variant-row','mb-2');
            div.innerHTML = `
                <div class="col-lg-4">
                    <input type="text" name="variants[${index}][name]" class="form-control" placeholder="اسم المتغير">
                </div>
                <div class="col-lg-4">
                    <input type="number" step="0.01" name="variants[${index}][price]" class="form-control" placeholder="السعر">
                </div>
                <div class="col-lg-3">
                    <input type="number" name="variants[${index}][stock]" class="form-control" placeholder="الكمية">
                </div>
                <div class="col-lg-1">
                    <button type="button" class="btn btn-danger remove-variant">-</button>
                </div>`;
            wrapper.appendChild(div);
        }
        if(e.target.classList.contains('remove-variant')){
            e.target.closest('.variant-row').remove();
        }
    });

});
document.addEventListener('DOMContentLoaded', function() {
    // تحقق إذا كانت صفحة إضافة وليس تعديل
    @if(!isset($product))
        const skuInput = document.querySelector('input[name="sku"]');
        if(skuInput) {
            // توليد رقم SKU تلقائي
            skuInput.value = 'SKU-' + Math.random().toString(36).substring(2,10).toUpperCase();
        }
    @endif
});

</script>
@endsection
