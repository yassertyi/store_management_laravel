<!-- Info Section -->
<section class="info-area info-bg padding-top-50px padding-bottom-50px text-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="icon-box">
                    <div class="info-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <div class="info-content">
                        <h4 class="info__title">{{ $settings['shipping_title'] }}</h4>
                        <p class="info__desc">
                            {{ $settings['shipping_description'] }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="icon-box margin-top-50px">
                    <div class="info-icon">
                        <i class="fas fa-undo-alt"></i>
                    </div>
                    <div class="info-content">
                        <h4 class="info__title">{{ $settings['return_title'] }}</h4>
                        <p class="info__desc">
                            {{ $settings['return_description'] }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="icon-box">
                    <div class="info-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="info-content">
                        <h4 class="info__title">{{ $settings['payment_title'] }}</h4>
                        <p class="info__desc">
                            {{ $settings['payment_description'] }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>