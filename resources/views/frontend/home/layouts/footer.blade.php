<!-- ================================
       START FOOTER AREA
================================= -->
<section class="footer-area section-bg padding-top-100px padding-bottom-30px">
  <div class="container">
    <div class="row">
      <div class="col-lg-3 responsive-column">
        <div class="footer-item">
          <div class="footer-logo padding-bottom-30px">
            <a href="{{ route('front.home') }}" class="foot__logo">
              <img src="{{ asset('static/images/logo.png') }}" alt="logo" />
            </a>
          </div>
          <!-- end logo -->
          <p class="footer__desc">
            {{ $settings['footer_description'] }}
          </p>
          <ul class="list-items pt-3">
            <li>
              {{ $settings['footer_address'] }}
            </li>
            <li>{{ $settings['footer_phone'] }}</li>
            <li><a href="mailto:{{ $settings['footer_email'] }}">{{ $settings['footer_email'] }}</a></li>
          </ul>
        </div>
        <!-- end footer-item -->
      </div>
      <!-- end col-lg-3 -->
      <div class="col-lg-3 responsive-column">
        <div class="footer-item">
          <h4 class="title curve-shape pb-3 margin-bottom-20px" data-text="curvs">
            المتجر
          </h4>
          <ul class="list-items list--items">
            @foreach($storeLinks as $link)
              <li><a href="{{ $link->url }}">{{ $link->title }}</a></li>
            @endforeach
          </ul>
        </div>
        <!-- end footer-item -->
      </div>
      <!-- end col-lg-3 -->
      <div class="col-lg-3 responsive-column">
        <div class="footer-item">
          <h4 class="title curve-shape pb-3 margin-bottom-20px" data-text="curvs">
            خدمة العملاء
          </h4>
          <ul class="list-items list--items">
            @foreach($customerServiceLinks as $link)
              <li><a href="{{ $link->url }}">{{ $link->title }}</a></li>
            @endforeach
          </ul>
        </div>
        <!-- end footer-item -->
      </div>
      <!-- end col-lg-3 -->
      <div class="col-lg-3 responsive-column">
        <div class="footer-item">
          <h4 class="title curve-shape pb-3 margin-bottom-20px" data-text="curvs">
            وسائل التواصل
          </h4>
          <div class="footer-social-box">
            <ul class="social-profile">
              @foreach($socialMedia as $social)
                <li>
                  <a href="{{ $social->url }}" target="_blank">
                    <i class="{{ $social->icon_class }}"></i>
                  </a>
                </li>
              @endforeach
            </ul>
          </div>
          <div class="payment-methods pt-4">
            <h4 class="title pb-2">طرق الدفع</h4>
            <img src="{{ asset('static/images/air-france.png') }}" alt="payment methods" />
          </div>
        </div>
        <!-- end footer-item -->
      </div>
      <!-- end col-lg-3 -->
    </div>
    <!-- end row -->
    <div class="row align-items-center">
      <div class="col-lg-8">
        <div class="term-box footer-item">
          <ul class="list-items list--items d-flex align-items-center">
            @foreach($informationLinks as $link)
              <li><a href="{{ $link->url }}">{{ $link->title }}</a></li>
            @endforeach
          </ul>
        </div>
      </div>
      <!-- end col-lg-8 -->
      <div class="col-lg-4">
        <div class="copy-right padding-top-30px">
          <p class="copy__desc">
            {!! $settings['copyright_text'] !!}
          </p>
        </div>
        <!-- end copy-right -->
      </div>
      <!-- end col-lg-4 -->
    </div>
    <!-- end row -->
  </div>
  <!-- end container -->
</section>
<!-- end footer-area -->
<!-- ================================
       START FOOTER AREA
================================= -->