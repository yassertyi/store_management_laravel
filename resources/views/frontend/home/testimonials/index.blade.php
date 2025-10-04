@extends('frontend.home.layouts.master')

@section('title', 'آراء العملاء - متجرنا')

@section('content')
    <!-- صفحة جميع آراء العملاء -->
    <section class="testimonials-page-area section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-heading text-center mb-5">
                        <h1 class="sec__title">آراء عملائنا الكرام</h1>
                        <p class="sec__desc">ما يقوله عملاؤنا عن تجربتهم مع متجرنا</p>
                    </div>
                </div>
            </div>

            <!-- عرض رسائل النجاح والخطأ -->
            @if (session('success'))
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="la la-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="la la-exclamation-circle me-2"></i>
                            <strong>حدث خطأ!</strong> يرجى مراجعة البيانات المدخلة.
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <!-- قسم عرض الآراء -->
                <div class="col-lg-8">
                    <div class="testimonials-list">
                        @if ($testimonials->count() > 0)
                            @foreach ($testimonials as $testimonial)
                                <div class="testimonial-card mb-4">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body">
                                            <div class="testi-content">
                                                <div class="ratings mb-3">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i
                                                            class="fas fa-star{{ $i <= $testimonial->rating ? ' text-warning' : ' text-light' }}"></i>
                                                    @endfor
                                                    <small class="text-muted ms-2">({{ $testimonial->rating }}/5)</small>
                                                </div>
                                                <p class="testi-text">{{ $testimonial->content }}</p>
                                            </div>
                                            <div class="testi-author d-flex align-items-center mt-4">
                                                <div class="author-img me-3">
                                                    @if ($testimonial->customer_image)
                                                        <img src="{{ asset('storage/' . $testimonial->customer_image) }}"
                                                            alt="{{ $testimonial->customer_name }}" class="rounded-circle"
                                                            width="60" height="60" style="object-fit: cover;">
                                                    @else
                                                        <div class="author-placeholder rounded-circle d-flex align-items-center justify-content-center bg-light"
                                                            style="width: 60px; height: 60px; border: 2px solid #e9ecef;">
                                                            <i class="la la-user text-muted" style="font-size: 24px;"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="author-info">
                                                    <h5 class="author-name mb-1">{{ $testimonial->customer_name }}</h5>
                                                    @if ($testimonial->location)
                                                        <span class="author-location text-muted">
                                                            <i
                                                                class="la la-map-marker me-1"></i>{{ $testimonial->location }}
                                                        </span>
                                                    @endif
                                                    <div class="testi-date text-muted small mt-1">
                                                        {{ $testimonial->created_at->diffForHumans() }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- التقسيم (Pagination) -->
                            <div class="row mt-5">
                                <div class="col-lg-12">
                                    {{ $testimonials->links() }}
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="empty-state">
                                    <i class="la la-comments text-muted" style="font-size: 80px;"></i>
                                    <h4 class="mt-3">لا توجد آراء حالياً</h4>
                                    <p class="text-muted">كن أول من يشاركنا رأيه وتجربته</p>
                                    <a href="#testimonialForm" class="theme-btn theme-btn-small mt-3">
                                        <i class="la la-plus me-2"></i>أضف أول رأي
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- قسم إضافة رأي جديد -->
                <div class="col-lg-4">
                    <div class="add-testimonial-sidebar">
                        <div class="card sticky-top" style="top: 100px;">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="la la-plus me-2"></i>أضف رأيك</h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-4">شاركنا بتجربتك مع متجرنا وساعد الآخرين في اتخاذ قرارهم</p>

                                <form id="testimonialForm" action="{{ route('front.testimonials.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="input-box mb-3">
                                        <label class="label-text">اسمك الكريم <span class="text-danger">*</span></label>
                                        <input type="text" name="customer_name"
                                            class="form-control @error('customer_name') is-invalid @enderror"
                                            value="{{ old('customer_name') }}" placeholder="أدخل اسمك" required>
                                        @error('customer_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="input-box mb-3">
                                        <label class="label-text">المدينة</label>
                                        <input type="text" name="location"
                                            class="form-control @error('location') is-invalid @enderror"
                                            value="{{ old('location') }}" placeholder="مثال: صنعاء, عدن ">
                                        @error('location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="input-box mb-3">
                                        <label class="label-text">صورة شخصية (اختياري)</label>
                                        <input type="file" name="customer_image"
                                            class="form-control @error('customer_image') is-invalid @enderror"
                                            accept="image/jpeg,image/png,image/jpg">
                                        <small class="form-text text-muted">الحجم الأقصى: 2MB</small>
                                        @error('customer_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="input-box mb-3">
                                        <label class="label-text">التقييم <span class="text-danger">*</span></label>
                                        <div class="star-rating-container">
                                            <div class="stars-wrapper" id="starsWrapper">
                                                <!-- النجوم سيتم إضافتها بواسطة JavaScript -->
                                            </div>
                                            <input type="hidden" name="rating" id="selectedRating"
                                                value="{{ old('rating', 5) }}" required>
                                            <div class="rating-feedback text-center mt-2">
                                                <small class="text-muted" id="ratingText">التقييم: 5/5 - ممتاز</small>
                                                @error('rating')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="input-box mb-4">
                                        <label class="label-text">رأيك وتقييمك <span class="text-danger">*</span></label>
                                        <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="4"
                                            placeholder="اكتب رأيك عن تجربتك مع متجرنا..." required>{{ old('content') }}</textarea>
                                        @error('content')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="theme-btn w-100">
                                        <i class="la la-paper-plane me-2"></i>إضافة الرأي
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .testimonials-page-area {
            background: #f8f9fa;
            min-height: 100vh;
        }

        .testimonial-card .card {
            border-radius: 15px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .testimonial-card .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }

        /* تنسيق نجوم التقييم */
        .star-rating-container {
            direction: ltr;
            text-align: center;
        }

        .stars-wrapper {
            display: flex;
            justify-content: center;
            gap: 10px;
            padding: 15px 0;
            font-size: 0; /* لإزالة المسافات بين النجوم */
        }

        .star {
            cursor: pointer;
            font-size: 40px;
            color: #ddd;
            transition: all 0.2s ease-in-out;
            display: inline-block;
            line-height: 1;
        }

        .star:hover {
            transform: scale(1.2);
        }

        .star.active {
            color: #ffc107;
            transform: scale(1.1);
        }

        .star.hover {
            color: #ffc107;
        }

        .rating-feedback {
            margin-top: 15px;
        }

        .rating-text {
            font-weight: 600;
            color: #666;
            font-size: 14px;
        }

        .input-box .label-text {
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
            color: #333;
            font-size: 14px;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 10px 15px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .empty-state {
            padding: 3rem 1rem;
        }

        .testi-text {
            line-height: 1.8;
            color: #555;
            font-size: 16px;
        }

        .author-name {
            color: #333;
            font-weight: 600;
        }

        .pagination {
            justify-content: center;
        }

        .sticky-top {
            z-index: 1;
        }

        @media (max-width: 991.98px) {
            .add-testimonial-sidebar {
                margin-top: 3rem;
            }

            .sticky-top {
                position: relative !important;
                top: 0 !important;
            }

            .star {
                font-size: 35px;
            }
        }

        @media (max-width: 576px) {
            .star {
                font-size: 30px;
            }
        }

        /* تأثير النبض */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .pulse {
            animation: pulse 0.3s ease-in-out;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // إنشاء نظام النجوم التفاعلي
            function createStarRatingSystem() {
                const starsWrapper = document.getElementById('starsWrapper');
                const selectedRatingInput = document.getElementById('selectedRating');
                const ratingText = document.getElementById('ratingText');
                
                // تنظيف المحتوى القديم
                starsWrapper.innerHTML = '';
                
                // القيمة الافتراضية
                let currentRating = parseInt(selectedRatingInput.value) || 5;
                
                // رسائل التقييم
                const ratingMessages = {
                    1: 'سيء - 1/5',
                    2: 'مقبول - 2/5',
                    3: 'جيد - 3/5',
                    4: 'جيد جداً - 4/5',
                    5: 'ممتاز - 5/5'
                };

                // إنشاء النجوم من 1 إلى 5
                for (let i = 1; i <= 5; i++) {
                    const star = document.createElement('span');
                    star.className = 'star';
                    star.innerHTML = '★';
                    star.dataset.rating = i;
                    star.title = `${i} نجوم`;

                    // تحديث المظهر بناءً على التقييم الحالي
                    if (i <= currentRating) {
                        star.classList.add('active');
                    }

                    // حدث النقر على النجم
                    star.addEventListener('click', function() {
                        const rating = parseInt(this.dataset.rating);
                        currentRating = rating;
                        selectedRatingInput.value = rating;
                        updateStars();
                        updateRatingText();
                        
                        // تأثير النبض
                        starsWrapper.classList.add('pulse');
                        setTimeout(() => {
                            starsWrapper.classList.remove('pulse');
                        }, 300);
                    });

                    // حدث التمرير فوق النجم
                    star.addEventListener('mouseenter', function() {
                        const hoverRating = parseInt(this.dataset.rating);
                        highlightStars(hoverRating);
                    });

                    // حدث مغادرة النجوم
                    starsWrapper.addEventListener('mouseleave', function() {
                        updateStars();
                    });

                    starsWrapper.appendChild(star);
                }

                // تحديث مظهر النجوم
                function updateStars() {
                    const stars = document.querySelectorAll('#starsWrapper .star');
                    stars.forEach(star => {
                        const starRating = parseInt(star.dataset.rating);
                        star.classList.remove('active', 'hover');
                        if (starRating <= currentRating) {
                            star.classList.add('active');
                        }
                    });
                }

                // إبراز النجوم عند التمرير
                function highlightStars(rating) {
                    const stars = document.querySelectorAll('#starsWrapper .star');
                    stars.forEach(star => {
                        const starRating = parseInt(star.dataset.rating);
                        star.classList.remove('hover');
                        if (starRating <= rating) {
                            star.classList.add('hover');
                        }
                    });
                }

                // تحديث نص التقييم
                function updateRatingText() {
                    ratingText.textContent = ratingMessages[currentRating] || `التقييم: ${currentRating}/5`;
                }

                // التهيئة الأولية
                updateRatingText();
            }

            // تشغيل نظام النجوم بعد تحميل الصفحة
            createStarRatingSystem();

            // التحقق من صحة النموذج
            const form = document.getElementById('testimonialForm');
            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;
                let hasEmptyFields = false;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                        hasEmptyFields = true;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                // التحقق من التقييم
                const rating = document.getElementById('selectedRating').value;
                if (!rating || rating < 1 || rating > 5) {
                    document.getElementById('ratingText').classList.add('text-danger');
                    isValid = false;
                } else {
                    document.getElementById('ratingText').classList.remove('text-danger');
                }

                if (!isValid) {
                    e.preventDefault();

                    // إظهار رسالة تنبيه
                    if (hasEmptyFields) {
                        alert('يرجى ملء جميع الحقول المطلوبة');
                    }

                    // التمرير إلى أول حقل به خطأ
                    const firstError = form.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    } else {
                        // إذا كان الخطأ في التقييم، التمرير إلى النجوم
                        document.getElementById('starsWrapper').scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                }
            });

            // إزالة حالة الخطأ عند الكتابة
            form.querySelectorAll('input, textarea').forEach(field => {
                field.addEventListener('input', function() {
                    if (this.value.trim()) {
                        this.classList.remove('is-invalid');
                    }
                });
            });

            // التمرير السلس للنموذج عند وجود أخطاء
            @if ($errors->any())
                setTimeout(function() {
                    const formElement = document.getElementById('testimonialForm');
                    if (formElement) {
                        formElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                }, 100);
            @endif
        });
    </script>
@endsection