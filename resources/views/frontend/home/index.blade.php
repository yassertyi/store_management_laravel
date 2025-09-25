@extends('frontend.home.layouts.master')

@section('title', 'الصفحة الرئيسية')
@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
 <link rel="stylesheet" href="{{ asset('static/css/home.css') }}" />

@endsection
@section('content')
    @include('frontend.home.sections.hero')
    @include('frontend.home.sections.info')
    @include('frontend.home.sections.categories')
    @include('frontend.home.sections.products')
    @include('frontend.home.sections.offers')
    @include('frontend.home.sections.featured-products')
    @include('frontend.home.sections.testimonials')
    @include('frontend.home.sections.brands')
    @include('frontend.home.sections.newsletter')
@endsection

@section('scripts')
<script>
        // كلمات متغيرة في الهيرو
        document.addEventListener('DOMContentLoaded', function() {
            const words = document.querySelectorAll('.cd-words-wrapper b');
            let currentWord = 0;
            
            function rotateWords() {
                const current = words[currentWord];
                const next = words[(currentWord + 1) % words.length];
                
                // إخفاء الكلمة الحالية
                current.classList.remove('is-visible');
                current.classList.add('is-hidden');
                
                // إظهار الكلمة التالية
                next.classList.remove('is-hidden');
                next.classList.add('is-visible');
                
                currentWord = (currentWord + 1) % words.length;
            }
            
            // بدء التدوير كل 3 ثوان
            setInterval(rotateWords, 3000);
        });
        
    </script>
    <script>
        // بيانات المنتجات (ستأتي من قاعدة البيانات في التطبيق الحقيقي)
        const products = [
            {
                id: 1,
                title: "سماعات رأس لاسلكية عالية الجودة",
                price: "299.00",
                compare_price: "350.00",
                image: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
                category: "إلكترونيات",
                is_new: true,
                on_sale: true,
                rating: 4.5,
                reviews: 120,
                description: "سماعات رأس لاسلكية بتقنية عالية تقدم صوتاً نقياً ومدة بطارية تصل إلى 30 ساعة. مثالية للاستخدام اليومي والسفر."
            },
            {
                id: 2,
                title: "ساعة ذكية متطورة بشاشة لمس",
                price: "450.00",
                compare_price: "",
                image: "https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
                category: "إلكترونيات",
                is_new: true,
                on_sale: false,
                rating: 4.8,
                reviews: 95,
                description: "ساعة ذكية متطورة مع شاشة لمس عالية الدقة، مقاومة للماء، وتتبع اللياقة البدنية والنوم."
            },
            {
                id: 3,
                title: "حذاء رياضي مريح للجري",
                price: "199.00",
                compare_price: "235.00",
                image: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
                category: "رياضية",
                is_new: false,
                on_sale: true,
                rating: 4.3,
                reviews: 210,
                description: "حذاء رياضي مريح مصمم خصيصاً للجري، يوفر دعماً ممتازاً للقدم ويقلل من تأثير الصدمات."
            },
            {
                id: 4,
                title: "هاتف ذكي بشاشة 6.5 بوصة",
                price: "3200.00",
                compare_price: "3550.00",
                image: "https://images.unsplash.com/photo-1485955900006-10f4d324d411?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
                category: "إلكترونيات",
                is_new: false,
                on_sale: true,
                rating: 4.7,
                reviews: 340,
                description: "هاتف ذكي حديث بشاشة 6.5 بوصة، كاميرا متطورة، وأداء عالي السرعة لتجربة استخدام متميزة."
            },
            {
                id: 5,
                title: "كاميرا رقمية عالية الدقة",
                price: "1200.00",
                compare_price: "",
                image: "https://images.unsplash.com/photo-1516035069371-29a1b244cc32?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
                category: "إلكترونيات",
                is_new: true,
                on_sale: false,
                rating: 4.6,
                reviews: 87,
                description: "كاميرا رقمية احترافية بدقة 24 ميجابكسل، مزودة بعدسة قابلة للتبديل وتقنية تثبيت الصورة."
            },
            {
                id: 6,
                title: "لوحة مفاتيح ميكانيكية إضاءة RGB",
                price: "350.00",
                compare_price: "400.00",
                image: "https://images.unsplash.com/photo-1541140532154-b024d705b90a?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
                category: "إلكترونيات",
                is_new: false,
                on_sale: true,
                rating: 4.4,
                reviews: 156,
                description: "لوحة مفاتيح ميكانيكية بإضاءة RGB قابلة للتخصيص، بمفاتيح مقاومة للانسكاب وعمر طويل."
            },
            {
                id: 7,
                title: "حقيبة ظهر مقاومة للماء",
                price: "180.00",
                compare_price: "",
                image: "https://images.unsplash.com/photo-1553062407-98eeb64c6a62?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
                category: "أزياء",
                is_new: true,
                on_sale: false,
                rating: 4.2,
                reviews: 92,
                description: "حقيبة ظهر عصرية مقاومة للماء، متعددة الجيوب، ومريحة للارتداء خلال التنقل أو السفر."
            },
            {
                id: 8,
                title: "سماعات أذن لاسلكية صغيرة",
                price: "220.00",
                compare_price: "260.00",
                image: "https://images.unsplash.com/photo-1590658165737-15a047b8b5e3?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
                category: "إلكترونيات",
                is_new: false,
                on_sale: true,
                rating: 4.5,
                reviews: 203,
                description: "سماعات أذن لاسلكية صغيرة الحجم، خفيفة الوزن، مع شحن سريع وعمر بطارية يصل إلى 8 ساعات."
            }
        ];

        // عناصر DOM
        const productsContainer = document.querySelector('.products-container');
        const filterTabs = document.querySelectorAll('.filter-tab');
        const viewButtons = document.querySelectorAll('.view-btn');
        const loadMoreButton = document.getElementById('load-more');
        const quickViewModal = document.getElementById('quickViewModal');
        const closeModalButton = document.querySelector('.close-modal');

        // حالة التطبيق
        let currentFilter = 'all';
        let currentView = 'grid';
        let displayedProducts = 4;

        // تهيئة الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            renderProducts();
            setupEventListeners();
        });

        // إعداد مستمعي الأحداث
        function setupEventListeners() {
            // علامات التصفية
            filterTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    filterTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    currentFilter = this.getAttribute('data-filter');
                    displayedProducts = 4;
                    renderProducts();
                });
            });

            // أزرار طريقة العرض
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    viewButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    currentView = this.getAttribute('data-view');
                    productsContainer.className = `row padding-top-50px products-container ${currentView}-view`;
                    renderProducts();
                });
            });

            // زر تحميل المزيد
            loadMoreButton.addEventListener('click', function() {
                displayedProducts += 4;
                renderProducts();
                
                // إخفاء الزر إذا تم عرض جميع المنتجات
                if (displayedProducts >= getFilteredProducts().length) {
                    loadMoreButton.style.display = 'none';
                }
            });

            // إغلاق النافذة المنبثقة
            closeModalButton.addEventListener('click', closeModal);
            quickViewModal.addEventListener('click', function(e) {
                if (e.target === quickViewModal) {
                    closeModal();
                }
            });

            // إضافة مستمعي الأحداث لأزرار الكمية
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('quantity-btn')) {
                    const input = e.target.parentElement.querySelector('.quantity-input');
                    let value = parseInt(input.value);
                    
                    if (e.target.classList.contains('plus')) {
                        input.value = value + 1;
                    } else if (e.target.classList.contains('minus') && value > 1) {
                        input.value = value - 1;
                    }
                }
                
                // معاينة سريعة
                if (e.target.classList.contains('quick-view-btn')) {
                    const productId = parseInt(e.target.getAttribute('data-product-id'));
                    openQuickView(productId);
                }
                
                // إضافة إلى المفضلة
                if (e.target.closest('.wishlist-btn')) {
                    const productId = parseInt(e.target.closest('.wishlist-btn').getAttribute('data-product-id'));
                    toggleWishlist(productId);
                }
            });
        }

        // تصفية المنتجات بناءً على التصنيف المحدد
        function getFilteredProducts() {
            if (currentFilter === 'all') return products;
            
            return products.filter(product => {
                if (currentFilter === 'new') return product.is_new;
                if (currentFilter === 'sale') return product.on_sale;
                if (currentFilter === 'bestseller') return product.rating >= 4.5;
                return true;
            });
        }

        // عرض المنتجات
        function renderProducts() {
            const filteredProducts = getFilteredProducts();
            const productsToShow = filteredProducts.slice(0, displayedProducts);
            
            productsContainer.innerHTML = '';
            
            productsToShow.forEach(product => {
                const productElement = createProductElement(product);
                productsContainer.appendChild(productElement);
            });
            
            // إظهار أو إخفاء زر "تحميل المزيد"
            loadMoreButton.style.display = displayedProducts >= filteredProducts.length ? 'none' : 'block';
        }

        // إنشاء عنصر منتج
        function createProductElement(product) {
            const discountPercent = product.compare_price 
                ? Math.round(((parseFloat(product.compare_price) - parseFloat(product.price)) / parseFloat(product.compare_price)) * 100)
                : 0;
            
            const isNew = product.is_new;
            const isOnSale = product.on_sale;
            
            // إنشاء تقييم النجوم
            const stars = generateStars(product.rating);
            
            const productDiv = document.createElement('div');
            productDiv.className = currentView === 'grid' ? 'col-lg-3 col-md-6' : 'col-lg-12';
            productDiv.innerHTML = `
                <div class="card-item product-card" data-product-id="${product.id}">
                    <div class="card-img">
                        <a href="#" class="d-block">
                            <img src="${product.image}" alt="${product.title}" />
                        </a>
                        ${isOnSale ? `<span class="badge">-${discountPercent}%</span>` : ''}
                        ${isNew && !isOnSale ? '<span class="badge new">جديد</span>' : ''}
                        <div class="card-actions">
                            <button class="action-btn wishlist-btn" data-product-id="${product.id}" title="إضافة إلى المفضلة">
                                <i class="far fa-heart"></i>
                            </button>
                            <button class="action-btn compare-btn" title="مقارنة المنتج">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                        </div>
                        <div class="quick-view">
                            <button class="quick-view-btn" data-product-id="${product.id}">معاينة سريعة</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">
                            <a href="#">${product.title}</a>
                        </h3>
                        <div class="card-meta">
                            <i class="fas fa-tag"></i> ${product.category}
                        </div>
                        <div class="card-rating">
                            <div class="rating-stars">
                                ${stars}
                            </div>
                            <span class="rating-count">(${product.reviews} تقييم)</span>
                        </div>
                        <div class="card-price">
                            <div class="price-container">
                                <span class="price__num">${product.price} ريال</span>
                                ${product.compare_price ? `<span class="before-price">${product.compare_price} ريال</span>` : ''}
                            </div>
                            <span class="stock-status in-stock">متوفر</span>
                        </div>
                        <div class="card-action">
                            <a href="#" class="btn-text"><i class="fas fa-shopping-cart"></i> أضف إلى السلة</a>
                            <a href="#" class="theme-btn">شراء الآن</a>
                        </div>
                    </div>
                </div>
            `;
            
            return productDiv;
        }

        // إنشاء تقييم النجوم
        function generateStars(rating) {
            let stars = '';
            const fullStars = Math.floor(rating);
            const hasHalfStar = rating % 1 >= 0.5;
            
            for (let i = 0; i < fullStars; i++) {
                stars += '<i class="fas fa-star"></i>';
            }
            
            if (hasHalfStar) {
                stars += '<i class="fas fa-star-half-alt"></i>';
            }
            
            const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
            for (let i = 0; i < emptyStars; i++) {
                stars += '<i class="far fa-star"></i>';
            }
            
            return stars;
        }

        // فتح نافذة المعاينة السريعة
        function openQuickView(productId) {
            const product = products.find(p => p.id === productId);
            if (!product) return;
            
            document.getElementById('modalProductImage').src = product.image;
            document.getElementById('modalProductTitle').textContent = product.title;
            document.getElementById('modalProductPrice').textContent = `${product.price} ريال`;
            
            if (product.compare_price) {
                document.getElementById('modalProductOldPrice').textContent = `${product.compare_price} ريال`;
            } else {
                document.getElementById('modalProductOldPrice').textContent = '';
            }
            
            document.getElementById('modalProductDescription').textContent = product.description;
            
            quickViewModal.style.display = 'flex';
        }

        // إغلاق نافذة المعاينة السريعة
        function closeModal() {
            quickViewModal.style.display = 'none';
        }

        // تبديل حالة المفضلة
        function toggleWishlist(productId) {
            const wishlistBtn = document.querySelector(`.wishlist-btn[data-product-id="${productId}"]`);
            const icon = wishlistBtn.querySelector('i');
            
            if (icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                wishlistBtn.style.color = '#e74c3c';
                showNotification('تمت إضافة المنتج إلى المفضلة');
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                wishlistBtn.style.color = '';
                showNotification('تمت إزالة المنتج من المفضلة');
            }
        }

        // عرض إشعار
        function showNotification(message) {
            // في التطبيق الحقيقي، يمكن استخدام مكتبة إشعارات
            alert(message);
        }
    </script>
@endsection