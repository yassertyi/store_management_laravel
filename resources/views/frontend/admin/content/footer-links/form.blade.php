@extends('frontend.admin.dashboard.index')

@section('title', isset($footerLink) ? 'تعديل رابط الفوتر' : 'إضافة رابط الفوتر جديد')
@section('page_title', 'إدارة روابط الفوتر - ' . (isset($footerLink) ? 'تعديل' : 'إضافة جديد'))

@section('content')
<section class="listing-form section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">

                <div class="listing-header pb-4">
                    <h3 class="title font-size-28 pb-2">{{ isset($footerLink) ? 'تعديل رابط الفوتر' : 'إضافة رابط الفوتر جديد' }}</h3>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                    </div>
                @endif

                <form action="{{ isset($footerLink) ? route('admin.content.footer-links.update', $footerLink) : route('admin.content.footer-links.store') }}" method="POST">
                    @csrf
                    @if(isset($footerLink)) @method('PUT') @endif

                    <div class="form-box">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-link me-2 text-gray"></i>معلومات الرابط</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div class="row">
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">العنوان *</label>
                                        <input type="text" name="title" class="form-control" value="{{ old('title', $footerLink->title ?? '') }}" required>
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الرابط *</label>
                                        <input type="text" name="url" class="form-control" value="{{ old('url', $footerLink->url ?? '') }}" required placeholder="/about أو https://example.com">
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">القسم *</label>
                                        <select name="section" class="form-control" required>
                                            <option value="">اختر القسم</option>
                                            <option value="store" {{ old('section', $footerLink->section ?? '') == 'store' ? 'selected' : '' }}>المتجر</option>
                                            <option value="customer_service" {{ old('section', $footerLink->section ?? '') == 'customer_service' ? 'selected' : '' }}>خدمة العملاء</option>
                                            <option value="information" {{ old('section', $footerLink->section ?? '') == 'information' ? 'selected' : '' }}>معلومات</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">ترتيب العرض</label>
                                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $footerLink->sort_order ?? 0) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="submit-box mt-3">
                        <button type="submit" class="theme-btn">{{ isset($footerLink) ? 'تحديث الرابط' : 'حفظ الرابط' }}</button>
                        <a href="{{ route('admin.content.footer-links.index') }}" class="theme-btn theme-btn-small bg-secondary">إلغاء</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>
@endsection