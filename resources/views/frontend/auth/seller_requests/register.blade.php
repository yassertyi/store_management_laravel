@extends('frontend.home.layouts.master')

@section('title', 'طلب فتح حساب متجر')

@section('content')
<section class="seller-register-area padding-top-80px padding-bottom-80px">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card custom-card shadow-sm border-0">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h2 class="mb-1">طلب فتح حساب متجر</h2>
                        <p class="mb-0 opacity-75">سيتم مراجعة طلبك من قبل الإدارة خلال 24-48 ساعة</p>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        {{-- رسائل النجاح/الخطأ --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="la la-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div class="d-flex">
                                    <i class="la la-exclamation-triangle me-3 fs-5"></i>
                                    <div>
                                        <h6 class="mb-2">يوجد أخطاء في البيانات المدخلة:</h6>
                                        <ul class="mb-0 ps-3">
                                            @foreach($errors->all() as $err)
                                                <li>{{ $err }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('seller.registerStore.submit') }}" enctype="multipart/form-data" id="sellerRegisterForm">
                            @csrf

                            <!-- خطوات التسجيل -->
                            <div class="registration-steps mb-5">
                                <div class="steps-progress">
                                    <div class="step active" data-step="1">
                                        <div class="step-number">1</div>
                                        <span class="step-label">المعلومات الشخصية</span>
                                    </div>
                                    <div class="step" data-step="2">
                                        <div class="step-number">2</div>
                                        <span class="step-label">معلومات المتجر</span>
                                    </div>
                                    <div class="step" data-step="3">
                                        <div class="step-number">3</div>
                                        <span class="step-label">المستندات والعنوان</span>
                                    </div>
                                </div>
                            </div>

                            <!-- الخطوة 1: المعلومات الشخصية -->
                            <div class="form-step step-1 active">
                                <div class="step-header mb-4">
                                    <h4 class="step-title text-primary">
                                        <i class="la la-user me-2"></i>المعلومات الشخصية
                                    </h4>
                                    <p class="step-subtitle text-muted">أدخل معلوماتك الشخصية الأساسية</p>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">الاسم الكامل <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="la la-user text-muted"></i>
                                            </span>
                                            <input type="text" name="name" class="form-control border-start-0 ps-0" 
                                                   value="{{ old('name') }}" placeholder="أدخل اسمك الكامل" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">البريد الإلكتروني <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="la la-envelope text-muted"></i>
                                            </span>
                                            <input type="email" name="email" class="form-control border-start-0 ps-0" 
                                                   value="{{ old('email') }}" placeholder="example@email.com" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">رقم الهاتف</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="la la-phone text-muted"></i>
                                            </span>
                                            <input type="tel" name="phone" class="form-control border-start-0 ps-0" 
                                                   value="{{ old('phone') }}" placeholder="05XXXXXXXX">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">الجنس</label>
                                        <select name="gender" class="form-select">
                                            <option value="">-- اختر الجنس --</option>
                                            <option value="0" {{ old('gender') === '0' ? 'selected' : '' }}>ذكر</option>
                                            <option value="1" {{ old('gender') === '1' ? 'selected' : '' }}>أنثى</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">كلمة المرور <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="la la-lock text-muted"></i>
                                            </span>
                                            <input type="password" name="password" class="form-control border-start-0 ps-0" 
                                                   placeholder="كلمة المرور" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="la la-lock text-muted"></i>
                                            </span>
                                            <input type="password" name="password_confirmation" class="form-control border-start-0 ps-0" 
                                                   placeholder="أعد إدخال كلمة المرور" required>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">الصورة الشخصية</label>
                                        <div class="file-upload-wrapper">
                                            <input type="file" name="profile_photo" class="form-control" 
                                                   accept="image/*" onchange="previewImage(this, 'profilePreview')">
                                            <div class="form-text">الصيغ المسموحة: JPG, PNG, GIF - الحد الأقصى: 2MB</div>
                                            <div id="profilePreview" class="image-preview mt-2"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="step-actions mt-4">
                                    <button type="button" class="btn btn-primary next-step" data-next="2">
                                        التالي <i class="la la-arrow-left ms-1"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- الخطوة 2: معلومات المتجر -->
                            <div class="form-step step-2">
                                <div class="step-header mb-4">
                                    <h4 class="step-title text-primary">
                                        <i class="la la-store me-2"></i>معلومات المتجر
                                    </h4>
                                    <p class="step-subtitle text-muted">أدخل معلومات متجرك</p>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">اسم المتجر <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="la la-store text-muted"></i>
                                            </span>
                                            <input type="text" name="store_name" class="form-control border-start-0 ps-0" 
                                                   value="{{ old('store_name') }}" placeholder="اسم المتجر" required>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">وصف المتجر</label>
                                        <textarea name="store_description" class="form-control" rows="4" 
                                                  placeholder="وصف مختصر عن متجرك وأنواع المنتجات التي تقدمها...">{{ old('store_description') }}</textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">شعار المتجر</label>
                                        <div class="file-upload-wrapper">
                                            <input type="file" name="logo" class="form-control" 
                                                   accept="image/*" onchange="previewImage(this, 'logoPreview')">
                                            <div class="form-text">الحجم الموصى به: 200x200 بكسل</div>
                                            <div id="logoPreview" class="image-preview mt-2"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">بانر المتجر</label>
                                        <div class="file-upload-wrapper">
                                            <input type="file" name="banner" class="form-control" 
                                                   accept="image/*" onchange="previewImage(this, 'bannerPreview')">
                                            <div class="form-text">الحجم الموصى به: 1200x300 بكسل</div>
                                            <div id="bannerPreview" class="image-preview mt-2"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="step-actions mt-4 d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary prev-step" data-prev="1">
                                        <i class="la la-arrow-right me-1"></i> السابق
                                    </button>
                                    <button type="button" class="btn btn-primary next-step" data-next="3">
                                        التالي <i class="la la-arrow-left ms-1"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- الخطوة 3: المستندات والعنوان -->
                            <div class="form-step step-3">
                                <div class="step-header mb-4">
                                    <h4 class="step-title text-primary">
                                        <i class="la la-file-alt me-2"></i>المستندات والعنوان
                                    </h4>
                                    <p class="step-subtitle text-muted">أدخل معلومات الرخصة والعنوان</p>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">رقم الرخصة التجارية</label>
                                        <input type="text" name="business_license_number" class="form-control" 
                                               value="{{ old('business_license_number') }}" placeholder="رقم الرخصة">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">مستند رسمي</label>
                                        <input type="file" name="document_path" class="form-control" 
                                               accept=".pdf,.jpg,.jpeg,.png">
                                        <div class="form-text">PDF, JPG, PNG - الحد الأقصى: 5MB</div>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <h6 class="border-bottom pb-2">عنوان المتجر</h6>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">الدولة</label>
                                        <input type="text" name="country" class="form-control" value="{{ old('country') }}" placeholder="الدولة">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">المحافظة / الولاية</label>
                                        <input type="text" name="state" class="form-control" value="{{ old('state') }}" placeholder="المحافظة">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">المدينة</label>
                                        <input type="text" name="city" class="form-control" value="{{ old('city') }}" placeholder="المدينة">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">الشارع / العنوان التفصيلي</label>
                                        <input type="text" name="street" class="form-control" value="{{ old('street') }}" placeholder="اسم الشارع والمنطقة">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">الرمز البريدي</label>
                                        <input type="text" name="zip_code" class="form-control" value="{{ old('zip_code') }}" placeholder="الرمز البريدي">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">صور إضافية للمتجر</label>
                                        <input type="file" name="additional_images[]" class="form-control" multiple accept="image/*">
                                        <div class="form-text">يمكنك رفع أكثر من صورة (اختياري)</div>
                                    </div>
                                </div>

                                <div class="step-actions mt-4 d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary prev-step" data-prev="2">
                                        <i class="la la-arrow-right me-1"></i> السابق
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="la la-paper-plane me-1"></i> إرسال الطلب
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.seller-register-area {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.custom-card {
    border-radius: 15px;
    overflow: hidden;
}

.registration-steps {
    position: relative;
}

.steps-progress {
    display: flex;
    justify-content: space-between;
    position: relative;
}

.steps-progress::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 10%;
    right: 10%;
    height: 2px;
    background: #dee2e6;
    z-index: 1;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    flex: 1;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #dee2e6;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-bottom: 8px;
    border: 3px solid white;
    transition: all 0.3s ease;
}

.step.active .step-number {
    background: #0d6efd;
    color: white;
    transform: scale(1.1);
}

.step-label {
    font-size: 0.85rem;
    color: #6c757d;
    text-align: center;
}

.step.active .step-label {
    color: #0d6efd;
    font-weight: 600;
}

.form-step {
    display: none;
    animation: fadeIn 0.5s ease-in;
}

.form-step.active {
    display: block;
}

.step-header {
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 1rem;
}

.step-title {
    font-weight: 700;
}

.step-subtitle {
    font-size: 0.9rem;
}

.form-label {
    margin-bottom: 0.5rem;
}

.input-group-text {
    background: #f8f9fa !important;
    border-color: #dee2e6;
}

.file-upload-wrapper {
    position: relative;
}

.image-preview {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.preview-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #dee2e6;
}

.step-actions {
    padding-top: 1.5rem;
    border-top: 1px solid #e9ecef;
}

.btn {
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 768px) {
    .steps-progress::before {
        left: 5%;
        right: 5%;
    }
    
    .step-label {
        font-size: 0.75rem;
    }
    
    .step-number {
        width: 35px;
        height: 35px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // التنقل بين الخطوات
    const nextButtons = document.querySelectorAll('.next-step');
    const prevButtons = document.querySelectorAll('.prev-step');
    const steps = document.querySelectorAll('.form-step');
    const stepIndicators = document.querySelectorAll('.step');

    nextButtons.forEach(button => {
        button.addEventListener('click', function() {
            const currentStep = this.closest('.form-step');
            const nextStepNum = this.getAttribute('data-next');
            const nextStep = document.querySelector(`.step-${nextStepNum}`);
            
            if (validateStep(currentStep)) {
                currentStep.classList.remove('active');
                nextStep.classList.add('active');
                updateStepProgress(nextStepNum);
            }
        });
    });

    prevButtons.forEach(button => {
        button.addEventListener('click', function() {
            const currentStep = this.closest('.form-step');
            const prevStepNum = this.getAttribute('data-prev');
            const prevStep = document.querySelector(`.step-${prevStepNum}`);
            
            currentStep.classList.remove('active');
            prevStep.classList.add('active');
            updateStepProgress(prevStepNum);
        });
    });

    function updateStepProgress(stepNum) {
        stepIndicators.forEach(step => {
            step.classList.remove('active');
            if (parseInt(step.getAttribute('data-step')) <= parseInt(stepNum)) {
                step.classList.add('active');
            }
        });
    }

    function validateStep(step) {
        const inputs = step.querySelectorAll('input[required], select[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            showToast('يرجى ملء جميع الحقول المطلوبة', 'error');
        }

        return isValid;
    }

    function showToast(message, type = 'info') {
        // يمكنك إضافة مكتبة Toast هنا أو استخدام alert بسيط
        alert(message);
    }
});

function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = '';

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'preview-image';
            preview.appendChild(img);
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection