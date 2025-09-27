@extends('frontend.home.layouts.master')

@section('title', 'السلة')
@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4a6cf7;
            --primary-dark: #3a5ce5;
            --secondary: #6c757d;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --light: #f8f9fa;
            --dark: #343a40;
            --gray: #6c757d;
            --light-gray: #e9ecef;
            --border-radius: 8px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Tajawal', sans-serif;
        }

        body {
            background-color: #f5f7fb;
            color: var(--dark);
            line-height: 1.6;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* ترويسة الموقع */
        header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            list-style: none;
        }

        .nav-links li {
            margin-left: 25px;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            transition: var(--transition);
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .header-actions {
            display: flex;
            align-items: center;
        }

        .nav-btn {
            position: relative;
            margin-left: 15px;
        }

        .theme-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: var(--light);
            border-radius: 50%;
            color: var(--dark);
            text-decoration: none;
            transition: var(--transition);
            position: relative;
        }

        .theme-btn:hover {
            background-color: var(--primary);
            color: white;
        }

        .cart-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--danger);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }

        /* محتوى السلة */
        .cart-page {
            padding: 40px 0;
        }

        .page-title {
            font-size: 28px;
            margin-bottom: 30px;
            color: var(--dark);
            font-weight: 700;
            text-align: center;
        }

        .cart-container {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 30px;
        }

        @media (max-width: 992px) {
            .cart-container {
                grid-template-columns: 1fr;
            }
        }

        .cart-items {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 25px;
        }

        .cart-item {
            display: flex;
            padding: 20px 0;
            border-bottom: 1px solid var(--light-gray);
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 120px;
            height: 120px;
            border-radius: var(--border-radius);
            overflow: hidden;
            margin-left: 20px;
            flex-shrink: 0;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .item-details {
            flex: 1;
        }

        .item-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .item-seller {
            color: var(--gray);
            font-size: 14px;
            margin-bottom: 10px;
        }

        .item-price {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .item-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            border: 1px solid var(--light-gray);
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        .quantity-btn {
            background: var(--light);
            border: none;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .quantity-btn:hover {
            background: var(--primary);
            color: white;
        }

        .quantity-input {
            width: 50px;
            height: 35px;
            border: none;
            text-align: center;
            font-weight: 600;
            background: white;
        }

        .item-remove {
            color: var(--danger);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: var(--transition);
            padding: 5px 10px;
            border-radius: var(--border-radius);
        }

        .item-remove:hover {
            background: var(--danger);
            color: white;
        }

        /* ملخص الطلب */
        .order-summary {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 25px;
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .summary-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--light-gray);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .summary-total {
            font-size: 18px;
            font-weight: 700;
            margin-top: 10px;
            padding-top: 15px;
            border-top: 1px solid var(--light-gray);
        }

        .coupon-section {
            margin: 20px 0;
            padding: 15px;
            background: var(--light);
            border-radius: var(--border-radius);
        }

        .coupon-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .coupon-form {
            display: flex;
        }

        .coupon-input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid var(--light-gray);
            border-radius: var(--border-radius) 0 0 var(--border-radius);
            outline: none;
        }

        .coupon-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0 15px;
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
            cursor: pointer;
            transition: var(--transition);
        }

        .coupon-btn:hover {
            background: var(--primary-dark);
        }

        .checkout-btn {
            display: block;
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            padding: 15px;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
            text-decoration: none;
            margin-top: 20px;
        }

        .checkout-btn:hover {
            background: var(--primary-dark);
        }

        .continue-shopping {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .empty-cart {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-cart i {
            font-size: 80px;
            color: var(--light-gray);
            margin-bottom: 20px;
        }

        .empty-cart h3 {
            font-size: 24px;
            margin-bottom: 15px;
            color: var(--dark);
        }

        .empty-cart p {
            color: var(--gray);
            margin-bottom: 30px;
        }

        /* تذييل الصفحة */
        footer {
            background: var(--dark);
            color: white;
            padding: 50px 0 20px;
            margin-top: 50px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            margin-bottom: 40px;
        }

        @media (max-width: 768px) {
            .footer-content {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .footer-column h3 {
            font-size: 18px;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-column h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 40px;
            height: 2px;
            background: var(--primary);
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: #b0b7c3;
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: white;
            padding-right: 5px;
        }

        .copyright {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #b0b7c3;
            font-size: 14px;
        }

        /* رسائل التنبيه */
        .alert {
            padding: 12px 15px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert i {
            margin-left: 10px;
        }

        /* تأثيرات للعناصر التفاعلية */
        .pulse {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endsection
@section('content')
<section class="cart-page">
        <div class="container">
            <h1 class="page-title">سلة التسوق</h1>
            
            <!-- رسالة نجاح إضافة منتج -->
            <div class="alert alert-success fade-in">
                <i class="fas fa-check-circle"></i>
                تم تحديث الكمية بنجاح!
            </div>
            
            <div class="cart-container">
                <!-- عناصر السلة -->
                <div class="cart-items">
                    <div class="cart-item fade-in">
                        <div class="item-image">
                            <img src="https://via.placeholder.com/300" alt="منتج">
                        </div>
                        <div class="item-details">
                            <h3 class="item-title">سماعات لاسلكية عالية الجودة</h3>
                            <p class="item-seller">من متجر: التقنية المتطورة</p>
                            <div class="item-price">250.00 ر.س</div>
                            <div class="item-actions">
                                <div class="quantity-control">
                                    <button class="quantity-btn" onclick="updateQuantity(this, -1)">-</button>
                                    <input type="number" class="quantity-input" value="1" min="1" max="10">
                                    <button class="quantity-btn" onclick="updateQuantity(this, 1)">+</button>
                                </div>
                                <button class="item-remove" onclick="removeItem(this)">
                                    <i class="fas fa-trash"></i> حذف
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="cart-item fade-in">
                        <div class="item-image">
                            <img src="https://via.placeholder.com/300" alt="منتج">
                        </div>
                        <div class="item-details">
                            <h3 class="item-title">هاتف ذكي بشاشة 6.5 بوصة</h3>
                            <p class="item-seller">من متجر: الجوالات المميزة</p>
                            <div class="item-price">1,899.00 ر.س</div>
                            <div class="item-actions">
                                <div class="quantity-control">
                                    <button class="quantity-btn" onclick="updateQuantity(this, -1)">-</button>
                                    <input type="number" class="quantity-input" value="1" min="1" max="10">
                                    <button class="quantity-btn" onclick="updateQuantity(this, 1)">+</button>
                                </div>
                                <button class="item-remove" onclick="removeItem(this)">
                                    <i class="fas fa-trash"></i> حذف
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="cart-item fade-in">
                        <div class="item-image">
                            <img src="https://via.placeholder.com/300" alt="منتج">
                        </div>
                        <div class="item-details">
                            <h3 class="item-title">ساعة ذكية متطورة</h3>
                            <p class="item-seller">من متجر: التقنية المتطورة</p>
                            <div class="item-price">599.00 ر.س</div>
                            <div class="item-actions">
                                <div class="quantity-control">
                                    <button class="quantity-btn" onclick="updateQuantity(this, -1)">-</button>
                                    <input type="number" class="quantity-input" value="1" min="1" max="10">
                                    <button class="quantity-btn" onclick="updateQuantity(this, 1)">+</button>
                                </div>
                                <button class="item-remove" onclick="removeItem(this)">
                                    <i class="fas fa-trash"></i> حذف
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- ملخص الطلب -->
                <div class="order-summary fade-in">
                    <h2 class="summary-title">ملخص الطلب</h2>
                    
                    <div class="summary-row">
                        <span>المجموع الفرعي</span>
                        <span>2,748.00 ر.س</span>
                    </div>
                    
                    <div class="summary-row">
                        <span>تكلفة الشحن</span>
                        <span>25.00 ر.س</span>
                    </div>
                    
                    <div class="summary-row">
                        <span>الخصم</span>
                        <span class="text-success">-50.00 ر.س</span>
                    </div>
                    
                    <div class="summary-row summary-total">
                        <span>المجموع الكلي</span>
                        <span>2,723.00 ر.س</span>
                    </div>
                    
                    <div class="coupon-section">
                        <h3 class="coupon-title">هل لديك كود خصم؟</h3>
                        <div class="coupon-form">
                            <input type="text" class="coupon-input" placeholder="أدخل الكود هنا">
                            <button class="coupon-btn">تطبيق</button>
                        </div>
                    </div>
                    
                    <a href="checkout.html" class="checkout-btn pulse">
                        <i class="fas fa-lock"></i> اتمام الشراء
                    </a>
                    
                    <a href="products.html" class="continue-shopping">
                        <i class="fas fa-arrow-right"></i> مواصلة التسوق
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
<script>
        // تحديث كمية المنتج
        function updateQuantity(button, change) {
            const input = button.parentElement.querySelector('.quantity-input');
            let value = parseInt(input.value) + change;
            
            if (value < 1) value = 1;
            if (value > 10) value = 10;
            
            input.value = value;
            
            // عرض رسالة التحديث
            showAlert('تم تحديث الكمية بنجاح!', 'success');
            
            // تحديث السعر الإجمالي (في تطبيق حقيقي سيتم حساب السعر من الخلفية)
            updateTotalPrice();
        }
        
        // إزالة منتج من السلة
        function removeItem(button) {
            const item = button.closest('.cart-item');
            item.style.animation = 'fadeOut 0.3s ease';
            
            setTimeout(() => {
                item.remove();
                updateCartCount();
                updateTotalPrice();
                
                // إذا لم يعد هناك منتجات، عرض رسالة السلة الفارغة
                if (document.querySelectorAll('.cart-item').length === 0) {
                    showEmptyCart();
                }
            }, 300);
            
            showAlert('تم حذف المنتج من السلة', 'danger');
        }
        
        // تحديث عدد العناصر في السلة
        function updateCartCount() {
            const cartCount = document.querySelector('.cart-count');
            const itemsCount = document.querySelectorAll('.cart-item').length;
            cartCount.textContent = itemsCount;
            
            if (itemsCount === 0) {
                cartCount.style.display = 'none';
            } else {
                cartCount.style.display = 'flex';
            }
        }
        
        // تحديث السعر الإجمالي (محاكاة)
        function updateTotalPrice() {
            // في تطبيق حقيقي، سيتم حساب هذا من الخلفية
            console.log('تم تحديث السعر الإجمالي');
        }
        
        // عرض رسالة تنبيه
        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} fade-in`;
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
                ${message}
            `;
            
            const cartItems = document.querySelector('.cart-items');
            cartItems.insertBefore(alertDiv, cartItems.firstChild);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        }
        
        // عرض رسالة السلة الفارغة
        function showEmptyCart() {
            const cartItems = document.querySelector('.cart-items');
            cartItems.innerHTML = `
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>سلة التسوق فارغة</h3>
                    <p>لم تقم بإضافة أي منتجات إلى سلة التسوق بعد</p>
                    <a href="products.html" class="checkout-btn">تصفح المنتجات</a>
                </div>
            `;
        }
        
        // تطبيق كوبون الخصم (محاكاة)
        document.querySelector('.coupon-btn').addEventListener('click', function() {
            const couponInput = document.querySelector('.coupon-input');
            const couponCode = couponInput.value.trim();
            
            if (couponCode === '') {
                showAlert('يرجى إدخال كود الخصم', 'danger');
                return;
            }
            
            // محاكاة التحقق من الكوبون
            if (couponCode === 'خصم10') {
                showAlert('تم تطبيق كود الخصم بنجاح!', 'success');
                couponInput.value = '';
            } else {
                showAlert('كود الخصم غير صالح', 'danger');
            }
        });
    </script>
    @endsection