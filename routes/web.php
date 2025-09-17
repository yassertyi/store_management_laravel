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





// ==========================
// الصفحة الرئيسية (العملاء)
// ==========================
Route::get('/', [HomeController::class, 'index'])->name('home');

// ==========================
// تسجيل الدخول والخروج
// ==========================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/seller/register-store', [SellerRequestController::class, 'showForm'])
    ->name('seller.registerStore.form');

Route::post('/seller/register-store', [SellerRequestController::class, 'store'])
    ->name('seller.registerStore.submit');

// ==========================
// مسارات الأدمن
// ==========================
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
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
Route::prefix('seller')->name('seller.')->middleware('auth')->group(function () {
    // لوحة تحكم البائع
    Route::get('/dashboard', [\App\Http\Controllers\Seller\DashboardController::class, 'index'])
        ->name('dashboard');

    // // المنتجات
    // Route::resource('products', \App\Http\Controllers\Seller\ProductController::class);
    // Route::get('products/{product}/details', [\App\Http\Controllers\Seller\ProductController::class, 'show'])
    //     ->name('products.show');

    // // التصنيفات الخاصة بالبائع
    // Route::resource('categories', \App\Http\Controllers\Seller\CategoryController::class);

    // // الطلبات
    // Route::resource('orders', \App\Http\Controllers\Seller\OrderController::class);

    // // المدفوعات
    // Route::resource('payments', \App\Http\Controllers\Seller\PaymentController::class);

    // // الكوبونات
    // Route::resource('coupons', \App\Http\Controllers\Seller\CouponController::class);

    // // التقييمات
    // Route::resource('reviews', \App\Http\Controllers\Seller\ReviewController::class);

    // // العملاء
    // Route::resource('customers', \App\Http\Controllers\Seller\CustomerController::class);

    // // الإحصائيات
    // Route::get('/statistics', [\App\Http\Controllers\Seller\StatisticController::class, 'index'])
    //     ->name('statistics.index');

    // // الدعم الفني
    // Route::resource('support', \App\Http\Controllers\Seller\SupportController::class);

    // // إعدادات المتجر
    // Route::get('/settings', [\App\Http\Controllers\Seller\SettingController::class, 'index'])
    //     ->name('settings');

    
});
