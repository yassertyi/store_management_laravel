<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SellerRequestController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\CheckoutController;
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
    Route::prefix('testimonials')->name('testimonials.')->group(function () {
        Route::get('/', [App\Http\Controllers\Front\TestimonialController::class, 'index'])->name('all');
        Route::post('/', [App\Http\Controllers\Front\TestimonialController::class, 'store'])->name('store');
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

