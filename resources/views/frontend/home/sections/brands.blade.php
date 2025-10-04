<!-- Brands Section -->
<section class="brands-area section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading text-center">
                    <h2 class="sec__title line-height-55">{{ $settings['brands_title'] }}</h2>
                </div>
            </div>
        </div>
        <div class="row padding-top-50px">
            <div class="col-lg-12">
                <div class="brands-carousel">
                    @foreach($brands as $brand)
                    <div class="brand-item">
                        @if($brand->logo)
                            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" />
                        @else
                            <div class="brand-placeholder">
                                <span>{{ $brand->name }}</span>
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>