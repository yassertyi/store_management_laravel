<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Customer\CustomerOrderController;
use App\Http\Controllers\Customer\NotificationController;
use App\Http\Controllers\Customer\SupportController;
use App\Http\Middleware\CheckUserType;


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

    // المدفوعات
    Route::get('payments', [\App\Http\Controllers\Customer\Payment\PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/{id}', [\App\Http\Controllers\Customer\Payment\PaymentController::class, 'show'])->name('payments.show');
});
