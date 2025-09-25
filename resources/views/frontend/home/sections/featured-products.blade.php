<section class="featured-products-area section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-heading text-center">
                        <h2 class="sec__title line-height-55">منتجات مميزة</h2>
                        <p>اكتشف أفضل منتجاتنا المختارة بعناية</p>
                    </div>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="filter-tabs">
                        <div class="filter-tab active" data-filter="all">الكل</div>
                        <div class="filter-tab" data-filter="new">جديد</div>
                        <div class="filter-tab" data-filter="sale">عروض</div>
                        <div class="filter-tab" data-filter="bestseller">الأكثر مبيعاً</div>
                    </div>
                </div>
            </div>

            <!-- View Options -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="view-options">
                        <div class="view-btn active" data-view="grid">
                            <i class="fas fa-th"></i>
                        </div>
                        <div class="view-btn" data-view="list">
                            <i class="fas fa-list"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row padding-top-50px products-container">
                <!-- سيتم إضافة المنتجات ديناميكياً باستخدام JavaScript -->
            </div>

            <!-- Load More Button -->
            <div class="row">
                <div class="col-lg-12 text-center">
                    <button id="load-more" class="theme-btn">تحميل المزيد</button>
                </div>
            </div>
        </div>
    </section>
    <!-- ================================
    END FEATURED PRODUCTS AREA
    ================================= -->

    <!-- Quick View Modal -->
    <div class="modal" id="quickViewModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">معاينة سريعة</h3>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-image">
                    <img id="modalProductImage" src="" alt="Product Image">
                </div>
                <div class="modal-details">
                    <h2 id="modalProductTitle"></h2>
                    <div class="card-rating">
                        <div class="rating-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="rating-count">(125 تقييم)</span>
                    </div>
                    <div class="modal-price">
                        <span id="modalProductPrice"></span>
                        <span id="modalProductOldPrice" class="before-price"></span>
                    </div>
                    <p id="modalProductDescription" class="modal-description"></p>
                    <div class="stock-status in-stock">متوفر في المخزون</div>
                    <div class="modal-actions">
                        <div class="quantity-selector">
                            <button class="quantity-btn minus">-</button>
                            <input type="text" class="quantity-input" value="1" readonly>
                            <button class="quantity-btn plus">+</button>
                        </div>
                        <a href="#" class="theme-btn">أضف إلى السلة</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
