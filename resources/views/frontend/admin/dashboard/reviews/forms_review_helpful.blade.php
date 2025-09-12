@extends('frontend.admin.dashboard.index')

@section('title')
{{ isset($helpful) ? 'تعديل التصويت' : 'إضافة تصويت جديد' }}
@endsection

@section('page_title')
إدارة تصويتات التقييمات - {{ isset($helpful) ? 'تعديل' : 'إضافة جديد' }}
@endsection

@section('contects')
<section class="listing-form section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">
                <div class="listing-header pb-4">
                    <h3 class="title font-size-28 pb-2">{{ isset($helpful) ? 'تعديل التصويت' : 'إضافة تصويت جديد' }}</h3>
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

                <form action="{{ isset($helpful) ? route('admin.review-helpful.update', $helpful->helpful_id) : route('admin.review-helpful.store') }}" 
                      method="POST">
                    @csrf
                    @if(isset($helpful)) @method('PUT') @endif

                    <div class="form-box">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-thumbs-up me-2 text-gray"></i>بيانات التصويت</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div class="row">

                                <!-- التقييم -->
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <label class="label-text">التقييم *</label>
                                        <select class="form-control" name="review_id" required>
                                            <option value="">اختر التقييم</option>
                                            @foreach($reviews as $review)
                                                <option value="{{ $review->review_id }}"
                                                    {{ (old('review_id') == $review->review_id) || (isset($helpful) && $helpful->review_id == $review->review_id) ? 'selected' : '' }}>
                                                    {{ $review->title ?? 'عنوان غير موجود' }}
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
                                                    {{ (old('user_id') == ($user->user_id ?? $user->id)) || (isset($helpful) && $helpful->user_id == ($user->user_id ?? $user->id)) ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- مفيد؟ -->
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <label class="label-text">مفيد؟</label>
                                        <div class="form-group">
                                            <input type="checkbox" name="is_helpful" value="1"
                                                {{ old('is_helpful', $helpful->is_helpful ?? false) ? 'checked' : '' }}>
                                            نعم
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
                                {{ isset($helpful) ? 'تحديث' : 'حفظ' }} <i class="la la-arrow-right ms-1"></i>
                            </button>
                            <a href="{{ route('admin.review-helpful.index') }}" class="theme-btn theme-btn-small bg-secondary">إلغاء</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
