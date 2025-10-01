<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SellerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\Coupons\CouponController;
use App\Http\Controllers\Admin\Coupons\CouponUsageController;
use App\Http\Controllers\Admin\CustomerAddressController;
use App\Http\Controllers\Admin\Messages\ChatController;
use App\Http\Controllers\Admin\Messages\MessageController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\Payment\PaymentController;
use App\Http\Controllers\Admin\Payment\PaymentOptionController;
use App\Http\Controllers\Admin\Payment\StorePaymentMethodController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ReviewHelpfulController;
use App\Http\Controllers\Admin\WishlistController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SellerRequestController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Customer\CustomerOrderController;
use App\Http\Controllers\Customer\NotificationController;
use App\Http\Controllers\Customer\SupportController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Seller\ProfileController;
use App\Http\Controllers\Seller\StoreSettingsController;
use App\Http\Middleware\CheckUserType;




// ==========================
// الصفحات العامة / العملاء
// ==========================
Route::prefix('/')->name('front.')->group(function () {
    // الصفحة الرئيسية
    Route::get('/', [HomeController::class, 'index'])->name('home');
    
    // صفحة السلة
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'addToCart'])->name('add');
        Route::put('/update', [CartController::class, 'updateCart'])->name('update');
        Route::delete('/remove', [CartController::class, 'removeFromCart'])->name('remove');
        Route::delete('/clear', [CartController::class, 'clearCart'])->name('clear');
        Route::delete('/clear-store/{storeId}', [CartController::class, 'clearStoreCart'])->name('clear.store');
        Route::get('/count', [CartController::class, 'getCartCount'])->name('count');
        Route::get('/summary', [CartController::class, 'getCartSummary'])->name('summary');
    });

    // صفحة المنتجات والتقييمات
    Route::get('/products', [App\Http\Controllers\Front\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [App\Http\Controllers\Front\ProductController::class, 'show'])->name('products.show');
    
    // تقييمات المنتجات
    Route::post('/products/{product}/review', [App\Http\Controllers\Front\ProductController::class, 'storeReview'])->name('products.review.store');
Route::post('/review/{review}/helpful', [App\Http\Controllers\Front\ProductController::class, 'toggleHelpful'])->name('front.review.helpful');    
    // المفضلة
    Route::post('/wishlist/add', [App\Http\Controllers\Front\ProductController::class, 'addToWishlist'])->name('wishlist.add');
    Route::post('/wishlist/remove', [App\Http\Controllers\Front\ProductController::class, 'removeFromWishlist'])->name('wishlist.remove');
    Route::get('/wishlist/check/{productId}', [App\Http\Controllers\Front\ProductController::class, 'checkWishlistStatus'])->name('wishlist.check');

    // صفحة اتمام الشراء
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::get('/store/{storeId}', [CheckoutController::class, 'storeCheckout'])->name('store');
        Route::post('/process', [CheckoutController::class, 'processCheckout'])->name('process');
        Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
        Route::get('/failed', [CheckoutController::class, 'failed'])->name('failed');
        Route::post('/validate-coupon', [CheckoutController::class, 'validateCoupon'])->name('validateCoupon');
        Route::get('/debug/coupons', [CheckoutController::class, 'debugCoupons']);
    });

    Route::prefix('stores')->name('stores.')->group(function () {
        Route::get('/{store}', [App\Http\Controllers\Front\StoreController::class, 'show'])->name('show');
        Route::get('/{store}/products', [App\Http\Controllers\Front\StoreController::class, 'products'])->name('products');
Route::get('/', [App\Http\Controllers\Front\StoreController::class, 'index'])->name('all');
    });
});




// ==========================
// تسجيل الدخول والخروج
// ==========================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
// ==========================
//انشاء حساب عميل
// ==========================
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register.submit');

Route::get('/seller/register-store', [SellerRequestController::class, 'showForm'])
    ->name('seller.registerStore.form');

Route::post('/seller/register-store', [SellerRequestController::class, 'store'])
    ->name('seller.registerStore.submit');

// ==========================
// مسارات الأدمن
// ==========================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'checkUserType:2'])->group(function () {
    // لوحة التحكم الرئيسية
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // إدارة المستخدمين
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    // تعديل البروفايل
Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
     Route::put('/profile/password', [UserController::class, 'updatePassword'])->name('profile.updatePassword');
});


    Route::resource('sellers', SellerController::class);
    Route::resource('customers', CustomerController::class);

    // إدارة المتاجر
    Route::resource('stores', StoreController::class);
    Route::get('/store-phones', [StoreController::class, 'phones'])->name('store.phones');
    Route::post('stores/phones/save', [StoreController::class, 'savePhones'])->name('stores.phones.save');
    Route::delete('stores/phones/{phone}', [StoreController::class, 'deletePhone'])->name('stores.phones.delete');

    Route::get('/store-addresses', [StoreController::class, 'addresses'])->name('store.addresses');
    Route::post('stores/addresses/save', [StoreController::class, 'saveAddresses'])->name('stores.addresses.save');
    Route::delete('stores/addresses/{address}', [StoreController::class, 'deleteAddress'])->name('stores.addresses.delete');

    Route::get('stores/{store}/details', [StoreController::class, 'show'])->name('stores.show');

    // إدارة التصنيفات
    Route::resource('categories', CategoryController::class);

    // المنتجات
    Route::get('products/images', [ProductController::class, 'imagesIndex'])->name('products.images.index');
    Route::post('products/images/save', [ProductController::class, 'saveImages'])->name('products.images.save');
    Route::delete('products/images/{image}', [ProductController::class, 'deleteImage'])->name('products.images.delete');

    Route::get('products/variants', [ProductController::class, 'variantsIndex'])->name('products.variants.index');
    Route::post('products/variants/save', [ProductController::class, 'saveVariants'])->name('products.variants.save');
    Route::delete('products/variants/{variant}', [ProductController::class, 'deleteVariant'])->name('products.variants.delete');

    Route::resource('products', ProductController::class);
    Route::get('products/{product}/details', [ProductController::class, 'show'])->name('products.show');

    // إدارة التقييمات
    Route::resource('reviews', ReviewController::class);

    // التقييمات المفيدة
    Route::resource('review-helpful', ReviewHelpfulController::class);

    // قوائم المفضلة
    Route::resource('wishlists', WishlistController::class);



    // عناصر الطلبات
    Route::get('orders/items', [OrderController::class, 'itemsIndex'])->name('orders.items.index');
    Route::post('orders/items/save', [OrderController::class, 'saveOrderItems'])->name('orders.items.save');
    Route::delete('orders/items/{item}', [OrderController::class, 'deleteOrderItem'])->name('orders.items.delete');

    // عناوين الطلبات
    Route::get('orders/addresses', [OrderController::class, 'addressesIndex'])->name('orders.addresses.index');
    Route::post('orders/addresses/save', [OrderController::class, 'saveOrderAddresses'])->name('orders.addresses.save');
    Route::delete('orders/addresses/{address}', [OrderController::class, 'deleteOrderAddress'])->name('orders.addresses.delete');


    // الطلبات
    Route::resource('orders', OrderController::class);

    // عناوين العملاء
    Route::resource('customer-addresses', CustomerAddressController::class);

    Route::get('/orders/{order}/payment-data', [PaymentController::class, 'getOrderData'])
        ->name('orders.payment-data');

    Route::resource('payments', PaymentController::class);
    Route::resource('store-payment-methods', StorePaymentMethodController::class);
    Route::resource('payment-options', PaymentOptionController::class);

    Route::resource('shippings', \App\Http\Controllers\Admin\ShippingController::class);

    Route::resource('coupons', CouponController::class);
    Route::resource('coupon-usage', CouponUsageController::class);

    Route::resource('support-tickets', \App\Http\Controllers\Admin\Messages\SupportTicketController::class);
    Route::resource('ticket-messages', \App\Http\Controllers\Admin\Messages\TicketMessageController::class);
    Route::resource('chats', \App\Http\Controllers\Admin\Messages\ChatController::class);
    Route::get('chats/{chat}/messages', [ChatController::class, 'fetchMessages'])->name('chats.messages.fetch');
    Route::post('chats/messages', [ChatController::class, 'store'])->name('chats.store');

    Route::resource('messages', \App\Http\Controllers\Admin\Messages\MessageController::class);
        Route::post('messages/mark-read', [MessageController::class, 'markAsRead'])->name('messages.markRead');


    Route::resource('notifications', \App\Http\Controllers\Admin\Messages\NotificationController::class);

    Route::post('notifications/mark-all-read', [\App\Http\Controllers\Admin\Messages\NotificationController::class, 'markAllRead'])->name('admin.notifications.markAllRead');

    Route::get('statistics/sales', [\App\Http\Controllers\Admin\StatisticsController::class, 'sales'])
        ->name('admin.statistics.sales');

    Route::get('statistics/users', [\App\Http\Controllers\Admin\StatisticsController::class, 'users'])
        ->name('statistics.users');
    Route::get('statistics/products', [\App\Http\Controllers\Admin\StatisticsController::class, 'products'])
        ->name('statistics.products');

        
    Route::get('/seller-requests', [\App\Http\Controllers\Admin\SellerRequestController::class, 'index'])
        ->name('seller-requests.index');

    Route::get('/seller-requests/{sellerRequest}', [\App\Http\Controllers\Admin\SellerRequestController::class, 'show'])
        ->name('seller-requests.show');

Route::post('/seller-requests/{sellerRequest}/approve', [\App\Http\Controllers\Admin\SellerRequestController::class, 'approve'])
        ->name('seller-requests.approve');

Route::post('/seller-requests/{sellerRequest}/reject', [\App\Http\Controllers\Admin\SellerRequestController::class, 'reject'])
    ->name('seller-requests.reject');


});

// ==========================
// مسارات البائع (Seller)
// ==========================
Route::prefix('seller')->name('seller.')->middleware(['auth', 'checkUserType:1'])->group(function () {
    // لوحة تحكم البائع
    Route::get('/dashboard', [\App\Http\Controllers\Seller\DashboardController::class, 'index'])
        ->name('dashboard');


     // صور المنتجات الخاصة بالبائع
    Route::get('products/images', [\App\Http\Controllers\Seller\ProductController::class, 'imagesIndex'])->name('products.images.index');
    Route::post('products/images/save', [\App\Http\Controllers\Seller\ProductController::class, 'saveImages'])->name('products.images.save');
    Route::delete('products/images/{image}', [\App\Http\Controllers\Seller\ProductController::class, 'deleteImage'])->name('products.images.delete');

    // متغيرات المنتجات الخاصة بالبائع
    Route::get('products/variants', [\App\Http\Controllers\Seller\ProductController::class, 'variantsIndex'])->name('products.variants.index');
    Route::post('products/variants/save', [\App\Http\Controllers\Seller\ProductController::class, 'saveVariants'])->name('products.variants.save');
    Route::delete('products/variants/{variant}', [\App\Http\Controllers\Seller\ProductController::class, 'deleteVariant'])->name('products.variants.delete');
    
   // المنتجات
    Route::resource('products', \App\Http\Controllers\Seller\ProductController::class);

    // الطلبات الخاصة بالبائع
    Route::get('orders', [\App\Http\Controllers\Seller\SellerOrderController::class, 'index'])
        ->name('orders.index');
        // تفاصيل الطلب
Route::get('orders/{order}', [\App\Http\Controllers\Seller\SellerOrderController::class, 'show'])
    ->name('orders.show');

// تحديث حالة الطلب أو حالة الدفع
    Route::patch('orders/{order}/update-status', [\App\Http\Controllers\Seller\SellerOrderController::class, 'updateStatus'])
        ->name('orders.updateStatus');


        // المدفوعات
    Route::get('payments', [\App\Http\Controllers\Seller\Payment\PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/{id}', [\App\Http\Controllers\Seller\Payment\PaymentController::class, 'show'])->name('payments.show');
    
    // طرق الدفع الخاصة بالمتجر
     Route::get('store-payment-methods', [\App\Http\Controllers\Seller\Payment\StorePaymentMethodController::class, 'index'])->name('storePaymentMethods.index');
    Route::get('store-payment-methods/create', [\App\Http\Controllers\Seller\Payment\StorePaymentMethodController::class, 'create'])->name('storePaymentMethods.create');
    Route::post('store-payment-methods', [\App\Http\Controllers\Seller\Payment\StorePaymentMethodController::class, 'store'])->name('storePaymentMethods.store');
    Route::get('store-payment-methods/{id}/edit', [\App\Http\Controllers\Seller\Payment\StorePaymentMethodController::class, 'edit'])->name('storePaymentMethods.edit');
    Route::put('store-payment-methods/{id}', [\App\Http\Controllers\Seller\Payment\StorePaymentMethodController::class, 'update'])->name('storePaymentMethods.update');
    Route::delete('store-payment-methods/{id}', [\App\Http\Controllers\Seller\Payment\StorePaymentMethodController::class, 'destroy'])->name('storePaymentMethods.destroy');

    Route::resource('coupons', \App\Http\Controllers\Seller\CouponController::class);
    // التقييمات
    Route::get('reviews', [\App\Http\Controllers\Seller\SellerReviewController::class, 'index'])->name('reviews.index');
    Route::get('review-helpful', [\App\Http\Controllers\Seller\ReviewHelpfulController::class, 'index'])->name('review-helpful.index');
    Route::get('wishlists', [\App\Http\Controllers\Seller\SellerWishlistController::class, 'index'])->name('wishlists.index');
Route::delete('wishlists/{id}', [\App\Http\Controllers\Seller\SellerWishlistController::class, 'destroy'])->name('seller.wishlists.destroy');


// تقارير المبيعات
Route::get('seller/statistics/sales', [\App\Http\Controllers\Seller\StatisticsController::class, 'sales'])
    ->name('seller.statistics.sales');

// تقارير المستخدمين
Route::get('seller/statistics/users', [\App\Http\Controllers\Seller\StatisticsController::class, 'users'])
    ->name('seller.statistics.users');

// تقارير المنتجات
Route::get('seller/statistics/products', [\App\Http\Controllers\Seller\StatisticsController::class, 'products'])
    ->name('seller.statistics.products');

    Route::resource('notifications', \App\Http\Controllers\Seller\NotificationController::class);

    Route::post('notifications/mark-all-read', [\App\Http\Controllers\Seller\NotificationController::class, 'markAllRead'])->name('seller.notifications.markAllRead');

     Route::get('/store-settings', [\App\Http\Controllers\Seller\StoreSettingsController::class, 'edit'])->name('seller.store.edit');
    Route::put('/store-settings', [\App\Http\Controllers\Seller\StoreSettingsController::class, 'update'])->name('seller.store.update');
    Route::post('/store-settings/address', [\App\Http\Controllers\Seller\StoreSettingsController::class, 'addAddress'])->name('seller.store.addAddress');
    Route::post('/store-settings/phone', [\App\Http\Controllers\Seller\StoreSettingsController::class, 'addPhone'])->name('seller.store.addPhone');

    // الدعم الفني للبائعين
Route::prefix('seller/support')->name('seller.support.')->middleware('auth')->group(function () {
    Route::get('/', [\App\Http\Controllers\Seller\SupportTicketController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\Seller\SupportTicketController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\Seller\SupportTicketController::class, 'store'])->name('store');
    Route::get('/{ticket}', [\App\Http\Controllers\Seller\SupportTicketController::class, 'show'])->name('show');
    Route::post('/{ticket}/close', [\App\Http\Controllers\Seller\SupportTicketController::class, 'close'])->name('close');
    Route::post('/{ticket}/messages', [\App\Http\Controllers\Seller\TicketMessageController::class, 'store'])->name('messages.store');
});

Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');


});


// مسارات العميل
Route::prefix('customer')->name('customer.')->middleware(['auth', 'checkUserType:0'])->group(function () {
    // لوحة التحكم
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    
    // طلبات العميل
    Route::get('/orders', [\App\Http\Controllers\Customer\CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\Customer\CustomerOrderController::class, 'show'])->name('orders.show');
    
    // إجراءات على الطلبات
    Route::post('/orders/{order}/cancel', [\App\Http\Controllers\Customer\CustomerOrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/update-address', [\App\Http\Controllers\Customer\CustomerOrderController::class, 'updateShippingAddress'])->name('orders.update-address');
    Route::post('/orders/{order}/add-note', [\App\Http\Controllers\Customer\CustomerOrderController::class, 'addNote'])->name('orders.add-note');

    //عناويني
    Route::resource('addresses', \App\Http\Controllers\Customer\CustomerAddressController::class);

    // المفضلة
    Route::get('/wishlist', [\App\Http\Controllers\Customer\WishlistController::class, 'index'])->name('wishlist.index');
    Route::delete('/wishlist/{wishlist}', [\App\Http\Controllers\Customer\WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // التقييمات
Route::get('/reviews', [\App\Http\Controllers\Customer\ReviewController::class, 'index'])->name('reviews.index');
Route::delete('/reviews/{review}', [\App\Http\Controllers\Customer\ReviewController::class, 'destroy'])->name('reviews.destroy');

// إضافة هذه المسارات داخل group العميل
Route::prefix('support')->name('support.')->group(function () {
    Route::get('/tickets', [SupportController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [SupportController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [SupportController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [SupportController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/message', [SupportController::class, 'sendMessage'])->name('tickets.message');
    Route::post('/tickets/{ticket}/close', [SupportController::class, 'closeTicket'])->name('tickets.close');
});

Route::prefix('profile')->name('profile.')->group(function () {
   // بروفايل العميل
Route::get('/edit', [\App\Http\Controllers\Customer\ProfileController::class, 'edit'])->name('edit');
Route::put('/update', [\App\Http\Controllers\Customer\ProfileController::class, 'update'])->name('update');
Route::put('/update-password', [\App\Http\Controllers\Customer\ProfileController::class, 'updatePassword'])->name('updatePassword');

});

Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Customer\NotificationController::class, 'index'])->name('index');
        Route::post('/{notification}/read', [\App\Http\Controllers\Customer\NotificationController::class, 'markAsRead'])->name('markAsRead');
        Route::post('/mark-all-read', [\App\Http\Controllers\Customer\NotificationController::class, 'markAllAsRead'])->name('markAllRead');
        Route::delete('/{notification}', [\App\Http\Controllers\Customer\NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/clear/read', [\App\Http\Controllers\Customer\NotificationController::class, 'clearRead'])->name('clearRead');
        Route::get('/unread-count', [\App\Http\Controllers\Customer\NotificationController::class, 'getUnreadCount'])->name('unreadCount');
    });

});