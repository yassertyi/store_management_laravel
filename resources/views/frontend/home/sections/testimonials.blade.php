<!-- Testimonials Section -->
<section class="testimonial-area section-padding section-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading text-center">
                    <h2 class="sec__title line-height-55">{{ $settings['testimonials_title'] ?? 'آراء عملائنا' }}</h2>
                    <p class="sec__desc pt-3">ما يقوله عملاؤنا عن تجربتهم مع متجرنا</p>
                </div>
            </div>
        </div>
        <div class="row padding-top-50px">
            <div class="col-lg-12">
                <div class="testimonial-carousel carousel-action">
                    @foreach($testimonials as $testimonial)
                    <div class="testimonial-card">
                        <div class="testi-desc-box">
                            <p class="testi__desc">{{ $testimonial->content }}</p>
                        </div>
                        <div class="author-content d-flex align-items-center">
                            <div class="author-img">
                                @if($testimonial->customer_image)
                                    <img src="{{ asset('storage/' . $testimonial->customer_image) }}" alt="{{ $testimonial->customer_name }}" />
                                @else
                                    <div class="author-placeholder">
                                        <i class="la la-user"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="author-bio">
                                <h4 class="author__title">{{ $testimonial->customer_name }}</h4>
                                <span class="author__meta">{{ $testimonial->location ?? 'غير محدد' }}</span>
                                <span class="ratings d-flex align-items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= $testimonial->rating ? ' text-warning' : '' }}"></i>
                                    @endfor
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- أزرار التحكم -->
        <div class="row mt-4">
            <div class="col-lg-12 text-center">
                <a href="{{ route('front.testimonials.all') }}" class="theme-btn theme-btn-transparent me-3">
                    <i class="la la-list me-2"></i>عرض جميع الآراء
                </a>                
            </div>
        </div>
    </div>
</section>