<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\Content\BrandController;
use App\Http\Controllers\Admin\Content\FooterLinkController;
use App\Http\Controllers\Admin\Content\SettingController;
use App\Http\Controllers\Admin\Content\SocialMediaController;
use App\Http\Controllers\Admin\Content\TestimonialController;
use App\Http\Controllers\Admin\Coupons\CouponController;
use App\Http\Controllers\Admin\Coupons\CouponUsageController;
use App\Http\Controllers\Admin\CustomerAddressController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Messages\ChatController;
use App\Http\Controllers\Admin\Messages\MessageController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\Payment\PaymentController;
use App\Http\Controllers\Admin\Payment\PaymentOptionController;
use App\Http\Controllers\Admin\Payment\StorePaymentMethodController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ReviewHelpfulController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\UserActivityController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WishlistController;
use Illuminate\Support\Facades\Route;



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

    // ==========================
    // إدارة المحتوى الديناميكي
    // ==========================
    Route::prefix('content')->name('content.')->group(function () {
        // الإعدادات العامة
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::get('/settings/create', [SettingController::class, 'create'])->name('settings.create');
        Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
        Route::get('/settings/{setting}/edit', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings/{setting}', [SettingController::class, 'update'])->name('settings.update');
        Route::delete('/settings/{setting}', [SettingController::class, 'destroy'])->name('settings.destroy');
        Route::post('/settings/bulk-update', [SettingController::class, 'bulkUpdate'])->name('settings.bulk-update');

        // العلامات التجارية
        Route::resource('brands', BrandController::class);
        Route::post('brands/{brand}/toggle-status', [BrandController::class, 'toggleStatus'])->name('brands.toggle-status');

        // آراء العملاء
        Route::resource('testimonials', TestimonialController::class);
        Route::post('testimonials/{testimonial}/toggle-status', [TestimonialController::class, 'toggleStatus'])->name('testimonials.toggle-status');

        // روابط الفوتر
        Route::resource('footer-links', FooterLinkController::class);
        Route::post('footer-links/{footerLink}/toggle-status', [FooterLinkController::class, 'toggleStatus'])->name('footer-links.toggle-status');

        // وسائل التواصل
        Route::resource('social-media', SocialMediaController::class);
        Route::post('social-media/{socialMedia}/toggle-status', [SocialMediaController::class, 'toggleStatus'])->name('social-media.toggle-status');
    });

    // مسارات نشاطات المستخدمين
    Route::get('/user-activities', [UserActivityController::class, 'index'])->name('user-activities.index');
    Route::get('/user-activities/{id}', [UserActivityController::class, 'show'])->name('user-activities.show');
    Route::delete('/user-activities/{id}', [UserActivityController::class, 'destroy'])->name('user-activities.destroy');
    Route::post('/user-activities/clear-old', [UserActivityController::class, 'clearOldActivities'])->name('user-activities.clear-old');
});
