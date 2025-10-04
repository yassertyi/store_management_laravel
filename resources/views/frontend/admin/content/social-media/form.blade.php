{{-- resources/views/frontend/admin/content/social-media/form.blade.php --}}
@extends('frontend.admin.dashboard.index')

@section('title', isset($socialMedia) ? 'تعديل وسيلة تواصل' : 'إضافة وسيلة تواصل جديدة')
@section('page_title', 'إدارة وسائل التواصل - ' . (isset($socialMedia) ? 'تعديل' : 'إضافة جديد'))

@section('content')
<section class="listing-form section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">

                <div class="listing-header pb-4">
                    <h3 class="title font-size-28 pb-2">{{ isset($socialMedia) ? 'تعديل وسيلة التواصل' : 'إضافة وسيلة تواصل جديدة' }}</h3>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                    </div>
                @endif

                <form action="{{ isset($socialMedia) ? route('admin.content.social-media.update', $socialMedia) : route('admin.content.social-media.store') }}" method="POST">
                    @csrf
                    @if(isset($socialMedia)) @method('PUT') @endif

                    <div class="form-box">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-share-alt me-2 text-gray"></i>معلومات وسيلة التواصل</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div class="row">
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">المنصة *</label>
                                        <select name="platform" class="form-control" required id="platform-select">
                                            <option value="">اختر المنصة</option>
                                            <option value="facebook" {{ old('platform', $socialMedia->platform ?? '') == 'facebook' ? 'selected' : '' }}>Facebook</option>
                                            <option value="twitter" {{ old('platform', $socialMedia->platform ?? '') == 'twitter' ? 'selected' : '' }}>Twitter</option>
                                            <option value="instagram" {{ old('platform', $socialMedia->platform ?? '') == 'instagram' ? 'selected' : '' }}>Instagram</option>
                                            <option value="youtube" {{ old('platform', $socialMedia->platform ?? '') == 'youtube' ? 'selected' : '' }}>YouTube</option>
                                            <option value="linkedin" {{ old('platform', $socialMedia->platform ?? '') == 'linkedin' ? 'selected' : '' }}>LinkedIn</option>
                                            <option value="snapchat" {{ old('platform', $socialMedia->platform ?? '') == 'snapchat' ? 'selected' : '' }}>Snapchat</option>
                                            <option value="tiktok" {{ old('platform', $socialMedia->platform ?? '') == 'tiktok' ? 'selected' : '' }}>TikTok</option>
                                            <option value="whatsapp" {{ old('platform', $socialMedia->platform ?? '') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                                            <option value="telegram" {{ old('platform', $socialMedia->platform ?? '') == 'telegram' ? 'selected' : '' }}>Telegram</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الرابط *</label>
                                        <input type="url" name="url" class="form-control" value="{{ old('url', $socialMedia->url ?? '') }}" required placeholder="https://example.com/username">
                                    </div>
                                </div>

                                {{-- إخفاء حقل الأيقونة --}}
                                <input type="hidden" name="icon_class" id="icon-class-input" value="{{ old('icon_class', $socialMedia->icon_class ?? '') }}">

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">ترتيب العرض</label>
                                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $socialMedia->sort_order ?? 0) }}">
                                    </div>
                                </div>

                                {{-- عرض الأيقونة المختارة تلقائياً --}}
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الأيقونة المختارة</label>
                                        <div id="icon-preview" class="border p-3 text-center rounded">
                                            @if(isset($socialMedia) && $socialMedia->icon_class)
                                                <i class="{{ $socialMedia->icon_class }} fs-2"></i>
                                                <div class="mt-1 text-muted">{{ $socialMedia->icon_class }}</div>
                                            @else
                                                <span class="text-muted">سيتم اختيار الأيقونة تلقائياً</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="submit-box mt-3">
                        <button type="submit" class="theme-btn">{{ isset($socialMedia) ? 'تحديث وسيلة التواصل' : 'حفظ وسيلة التواصل' }}</button>
                        <a href="{{ route('admin.content.social-media.index') }}" class="theme-btn theme-btn-small bg-secondary">إلغاء</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const platformSelect = document.getElementById('platform-select');
    const iconClassInput = document.getElementById('icon-class-input');
    const iconPreview = document.getElementById('icon-preview');
    
    // تعريف الأيقونات لكل منصة
    const platformIcons = {
        'facebook': 'lab la-facebook-f',
        'twitter': 'lab la-twitter',
        'instagram': 'lab la-instagram',
        'youtube': 'lab la-youtube',
        'linkedin': 'lab la-linkedin-in',
        'snapchat': 'lab la-snapchat',
        'tiktok': 'lab la-tiktok',
        'whatsapp': 'lab la-whatsapp',
        'telegram': 'lab la-telegram'
    };
    
    // تحديث الأيقونة عند تغيير المنصة
    platformSelect.addEventListener('change', function() {
        const selectedPlatform = this.value;
        const iconClass = platformIcons[selectedPlatform] || 'lab la-link';
        
        // تحديث الحقل المخفي
        iconClassInput.value = iconClass;
        
        // تحديث المعاينة
        if (selectedPlatform) {
            iconPreview.innerHTML = `
                <i class="${iconClass} fs-2"></i>
                <div class="mt-1 text-muted">${iconClass}</div>
            `;
        } else {
            iconPreview.innerHTML = '<span class="text-muted">سيتم اختيار الأيقونة تلقائياً</span>';
        }
    });
});
</script>
@endsection