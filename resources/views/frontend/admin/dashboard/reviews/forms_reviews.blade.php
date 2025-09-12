@extends('frontend.admin.dashboard.index')

@section('title')
{{ isset($review) ? 'تعديل التقييم' : 'إضافة تقييم جديد' }}
@endsection

@section('page_title')
إدارة التقييمات - {{ isset($review) ? 'تعديل' : 'إضافة جديد' }}
@endsection

@section('contects')
<section class="listing-form section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">
                <div class="listing-header pb-4">
                    <h3 class="title font-size-28 pb-2">{{ isset($review) ? 'تعديل التقييم' : 'إضافة تقييم جديد' }}</h3>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ isset($review) ? route('admin.reviews.update', $review->review_id) : route('admin.reviews.store') }}" 
                      method="POST">
                    @csrf
                    @if(isset($review)) @method('PUT') @endif

                    <div class="form-box">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-star me-2 text-gray"></i>بيانات التقييم</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div class="row">

                                <!-- المنتج -->
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <label class="label-text">المنتج *</label>
                                        <select class="form-control" name="product_id" required>
    <option value="">اختر المنتج</option>
    @foreach($products as $product)
        <option value="{{ $product->product_id }}"
            {{ (old('product_id') == $product->product_id) || (isset($review) && $review->product_id == $product->product_id) ? 'selected' : '' }}>
            {{ $product->title ?? 'اسم المنتج غير موجود' }}
        </option>
    @endforeach
</select>

                                    </div>
                                </div>

                                <!-- رقم الطلب اختياري -->
<div class="col-lg-6">
    <div class="input-box">
        <label class="label-text">رقم الطلب (اختياري)</label>
        <select class="form-control" name="order_id">
            <option value="">اختر الطلب (اختياري)</option>
            @foreach($orders as $order)
                <option value="{{ $order->order_id }}"
                    {{ (old('order_id') == $order->order_id) || (isset($review) && $review->order_id == $order->order_id) ? 'selected' : '' }}>
                    {{ $order->order_number ?? $order->order_id }}
                </option>
            @endforeach
        </select>
    </div>
</div>


                                <!-- المستخدم -->
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <label class="label-text">المستخدم *</label>
                                        <select class="form-control" name="user_id" required>
                                            <option value="">اختر المستخدم</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->user_id ?? $user->id }}"
                                                    {{ (old('user_id') == ($user->user_id ?? $user->id)) || (isset($review) && $review->user_id == ($user->user_id ?? $user->id)) ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- التقييم -->
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <label class="label-text">التقييم (1-5)</label>
                                        <input type="number" name="rating" class="form-control" min="1" max="5" required
                                               value="{{ old('rating', $review->rating ?? '') }}">
                                    </div>
                                </div>

                                <!-- العنوان -->
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <label class="label-text">العنوان</label>
                                        <input type="text" name="title" class="form-control"
                                               value="{{ old('title', $review->title ?? '') }}">
                                    </div>
                                </div>

                                <!-- التعليق -->
                                <div class="col-lg-12">
                                    <div class="input-box">
                                        <label class="label-text">التعليق</label>
                                        <textarea name="comment" rows="4" class="form-control">{{ old('comment', $review->comment ?? '') }}</textarea>
                                    </div>
                                </div>

                                <!-- الحالة -->
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <label class="label-text">الحالة</label>
                                        <div class="form-group">
                                            <input type="checkbox" name="is_approved" value="1"
                                                {{ old('is_approved', $review->is_approved ?? false) ? 'checked' : '' }}>
                                            مقبول
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- أزرار -->
                    <div class="submit-box">
                        <div class="btn-box pt-3">
                            <button type="submit" class="theme-btn">
                                {{ isset($review) ? 'تحديث' : 'حفظ' }} <i class="la la-arrow-right ms-1"></i>
                            </button>
                            <a href="{{ route('admin.reviews.index') }}" class="theme-btn theme-btn-small bg-secondary">إلغاء</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
