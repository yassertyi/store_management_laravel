<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Seller\ProfileController;
use App\Http\Controllers\Seller\StoreSettingsController;


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
    // في routes/seller.php
    Route::get('/statistics/users', [\App\Http\Controllers\Seller\StatisticsController::class, 'usersSimple'])
        ->name('seller.statistics.users');

    Route::get('/statistics/users/data', [\App\Http\Controllers\Seller\StatisticsController::class, 'getUsersData'])
        ->name('seller.statistics.users.data');
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