{{-- resources/views/frontend/admin/content/settings/form.blade.php --}}
@extends('frontend.admin.dashboard.index')

@section('title', isset($setting) ? 'تعديل إعداد' : 'إضافة إعداد جديد')
@section('page_title', 'إدارة الإعدادات - ' . (isset($setting) ? 'تعديل' : 'إضافة جديد'))

@section('content')
<section class="listing-form section--padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">

                <div class="listing-header pb-4">
                    <h3 class="title font-size-28 pb-2">{{ isset($setting) ? 'تعديل الإعداد' : 'إضافة إعداد جديد' }}</h3>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                    </div>
                @endif

                <form action="{{ isset($setting) ? route('admin.content.settings.update', $setting) : route('admin.content.settings.store') }}" method="POST">
                    @csrf
                    @if(isset($setting)) @method('PUT') @endif

                    <div class="form-box">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-cog me-2 text-gray"></i>معلومات الإعداد</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div class="row">
                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">المفتاح (Key) *</label>
                                        <input type="text" name="key" class="form-control" value="{{ old('key', $setting->key ?? '') }}" required placeholder="مثال: site_name, phone_number">
                                        <small class="form-text text-muted">يجب أن يكون المفتاح فريداً ولا يحتوي على مسافات</small>
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">المجموعة *</label>
                                        <select name="group_name" class="form-control" required>
                                            <option value="">اختر المجموعة</option>
                                            @foreach($groups as $value => $label)
                                                <option value="{{ $value }}" {{ old('group_name', $setting->group_name ?? '') == $value ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">نوع الحقل *</label>
                                        <select name="type" class="form-control" required id="type-select">
                                            <option value="">اختر النوع</option>
                                            @foreach($types as $value => $label)
                                                <option value="{{ $value }}" {{ old('type', $setting->type ?? '') == $value ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">الوصف</label>
                                        <input type="text" name="description" class="form-control" value="{{ old('description', $setting->description ?? '') }}" placeholder="وصف مختصر للإعداد">
                                    </div>
                                </div>

                                <div class="col-lg-12 responsive-column">
                                    <div class="input-box">
                                        <label class="label-text">القيمة</label>
                                        
                                        {{-- حقل النص العادي --}}
                                        <div id="text-field" class="field-type">
                                            <input type="text" name="value" class="form-control" value="{{ old('value', $setting->value ?? '') }}" placeholder="أدخل قيمة النص">
                                        </div>

                                        {{-- حقل النص الطويل --}}
                                        <div id="textarea-field" class="field-type d-none">
                                            <textarea name="value" class="form-control" rows="4" placeholder="أدخل النص الطويل">{{ old('value', $setting->value ?? '') }}</textarea>
                                        </div>

                                        {{-- حقل الرقم --}}
                                        <div id="number-field" class="field-type d-none">
                                            <input type="number" name="value" class="form-control" value="{{ old('value', $setting->value ?? '') }}" placeholder="أدخل رقم">
                                        </div>

                                        {{-- حقل البريد الإلكتروني --}}
                                        <div id="email-field" class="field-type d-none">
                                            <input type="email" name="value" class="form-control" value="{{ old('value', $setting->value ?? '') }}" placeholder="example@email.com">
                                        </div>

                                        {{-- حقل الرابط --}}
                                        <div id="url-field" class="field-type d-none">
                                            <input type="url" name="value" class="form-control" value="{{ old('value', $setting->value ?? '') }}" placeholder="https://example.com">
                                        </div>

                                        {{-- حقل الصورة --}}
                                        <div id="image-field" class="field-type d-none">
                                            <div class="input-group">
                                                <input type="text" name="value" class="form-control" value="{{ old('value', $setting->value ?? '') }}" placeholder="رابط الصورة أو المسار">
                                                <button type="button" class="btn btn-outline-secondary" onclick="openMediaManager()">
                                                    <i class="la la-image"></i>
                                                </button>
                                            </div>
                                            @if(isset($setting) && $setting->value)
                                                <div class="mt-2">
                                                    <img src="{{ $setting->value }}" alt="Preview" class="img-thumbnail" style="max-height: 100px;">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-box mt-3">
                        <div class="form-title-wrap">
                            <h3 class="title"><i class="la la-info-circle me-2 text-gray"></i>معلومات إضافية</h3>
                        </div>
                        <div class="form-content contact-form-action">
                            <div class="row">
                                <div class="col-lg-12 responsive-column">
                                    <div class="alert alert-info">
                                        <h6 class="alert-heading"><i class="la la-lightbulb me-2"></i>نصائح للإعدادات:</h6>
                                        <ul class="mb-0">
                                            <li>المفتاح (Key) يجب أن يكون فريداً ويمثل الإعداد بشكل واضح</li>
                                            <li>استخدم المجموعات لتنظيم الإعدادات المتشابهة</li>
                                            <li>اختر نوع الحقل المناسب لنوع البيانات</li>
                                            <li>أضف وصفاً واضحاً لتسهيل فهم الغرض من الإعداد</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="submit-box mt-3">
                        <button type="submit" class="theme-btn">{{ isset($setting) ? 'تحديث الإعداد' : 'حفظ الإعداد' }}</button>
                        <a href="{{ route('admin.content.settings.index') }}" class="theme-btn theme-btn-small bg-secondary">إلغاء</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type-select');
    const fieldTypes = document.querySelectorAll('.field-type');
    
    // إظهار/إخفاء الحقول بناءً على النوع المحدد
    function toggleFields() {
        const selectedType = typeSelect.value;
        
        // إخفاء جميع الحقول أولاً
        fieldTypes.forEach(field => {
            field.classList.add('d-none');
        });
        
        // إظهار الحقل المناسب
        if (selectedType) {
            const targetField = document.getElementById(selectedType + '-field');
            if (targetField) {
                targetField.classList.remove('d-none');
            }
        }
    }
    
    // استدعاء الدالة عند التحميل وعند تغيير النوع
    toggleFields();
    typeSelect.addEventListener('change', toggleFields);
    
    // إذا كان هناك قيمة محفوظة مسبقاً، تأكد من إظهار الحقل المناسب
    @if(isset($setting))
        setTimeout(() => {
            toggleFields();
        }, 100);
    @endif
});

function openMediaManager() {
    // هنا يمكنك إضافة كود مدير الملفات
    alert('سيتم فتح مدير الملفات هنا - يمكنك ربطه بمكتبة خارجية');
}
</script>

<style>
.field-type {
    transition: all 0.3s ease;
}

.input-group .btn {
    border: 1px solid #ddd;
}

.alert-info {
    background-color: #f8f9fa;
    border-color: #e9ecef;
    color: #495057;
}
</style>
@endsection