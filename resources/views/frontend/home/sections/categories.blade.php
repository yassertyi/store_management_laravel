<!-- Categories Section -->
<section class="category-area section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading text-center">
                    <h2 class="sec__title line-height-55">تصفح حسب الفئات</h2>
                </div>
            </div>
        </div>

        <div class="row padding-top-50px">
            <div class="col-lg-12">
                <div class="category-carousel carousel-action">
                    @foreach($categories as $category)
                        <div class="category-item text-center">
                            <div class="category-img">
                                <img src="{{ $category->image ? asset('storage/' . $category->image) : 'https://via.placeholder.com/500x300' }}" 
                                     alt="{{ $category->name }}" />
                            </div>
                            <div class="category-content">
                                <h3 class="category__title">{{ $category->name }}</h3>
                                <p class="category__meta">{{ $category->products_count }} منتج</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
