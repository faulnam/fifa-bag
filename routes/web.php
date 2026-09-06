<?php

use App\Http\Controllers\Account\AddressController;
use App\Http\Controllers\Account\OrderController as AccountOrderController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CollectionController as AdminCollectionController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\HeroSlideController as AdminHeroSlideController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductImageController as AdminProductImageController;
use App\Http\Controllers\Admin\ProductVariantController as AdminProductVariantController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\StoreLocationController as AdminStoreLocationController;
use App\Http\Controllers\Admin\SubscriberController as AdminSubscriberController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\Webhook\BiteshipController as BiteshipWebhookController;
use App\Http\Controllers\Webhook\MidtransController as MidtransWebhookController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Storefront Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Collections & Public Catalog
Route::get('/men', [CollectionController::class, 'show'])->defaults('slug', 'men')->name('categories.men');
Route::get('/women', [CollectionController::class, 'show'])->defaults('slug', 'women')->name('categories.women');
Route::get('/sale', [CollectionController::class, 'show'])->defaults('slug', 'sale')->name('collections.sale');
Route::get('/collections/{slug}', [CollectionController::class, 'show'])->name('collections.show');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/products/{product}/reviews', [ProductReviewController::class, 'store'])->name('products.reviews.store')->middleware(['auth', 'customer', 'throttle:5,1']);

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/data', [CartController::class, 'data'])->name('cart.data');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/items/{item}', [CartController::class, 'update'])->name('cart.items.update');
Route::delete('/cart/items/{item}', [CartController::class, 'destroy'])->name('cart.items.destroy');

// Wishlist Public / Auth-Aware Routes
Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::get('/wishlist/ids', [WishlistController::class, 'ids'])->name('wishlist.ids');

// Biteship Area Autocomplete Route
Route::get('/shipping/areas', [AddressController::class, 'searchAreas'])->name('shipping.areas')->middleware('throttle:60,1');

// Checkout Routes (Multi-step + Midtrans Snap Integration)
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/rates', [CheckoutController::class, 'calculateRates'])->name('checkout.rates')->middleware('throttle:30,1');
Route::post('/checkout/save-shipping', [CheckoutController::class, 'saveShipping'])->name('checkout.save-shipping')->middleware('throttle:30,1');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process')->middleware('throttle:15,1');

// Order Confirmation & Payment Retry Routes
Route::get('/order/{order_number}/success', [CheckoutController::class, 'success'])->name('orders.success');
Route::post('/order/{order}/retry-payment', [CheckoutController::class, 'retryPayment'])->name('orders.retry-payment')->middleware('throttle:15,1');
Route::post('/order/{order}/simulate-payment', [CheckoutController::class, 'simulatePayment'])->name('orders.simulate-payment');

// Webhook Routes (CSRF Excluded)
Route::post('/webhooks/midtrans', [MidtransWebhookController::class, 'handle'])->name('webhooks.midtrans');
Route::post('/webhooks/biteship', [BiteshipWebhookController::class, 'handle'])->name('webhooks.biteship');

// CMS Public Pages, Stores, Blog & Newsletter
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/search/live', [SearchController::class, 'live'])->name('search.live');
Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');
Route::get('/stores', [StoreController::class, 'index'])->name('stores.index');
Route::get('/journal', [BlogController::class, 'index'])->name('blog.index');
Route::get('/journal/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe')->middleware('throttle:10,1');

/*
|--------------------------------------------------------------------------
| Customer Authentication Routes
|--------------------------------------------------------------------------
*/

// Demo Quick Login & Reset Actions
Route::post('/demo/quick-login', [\App\Http\Controllers\DemoController::class, 'quickLogin'])->name('demo.quick-login');
Route::post('/demo/reset', [\App\Http\Controllers\DemoController::class, 'reset'])->name('demo.reset')->middleware('auth');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Customer Account Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'customer'])->prefix('account')->name('account.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Account\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');

    // Customer Profile & Security
    Route::get('/profile', [\App\Http\Controllers\Account\ProfileController::class, 'edit'])->name('profile');
    Route::match(['put', 'patch'], '/profile', [\App\Http\Controllers\Account\ProfileController::class, 'update'])->name('profile.update');
    Route::match(['put', 'patch'], '/profile/password', [\App\Http\Controllers\Account\ProfileController::class, 'updatePassword'])->name('profile.password');

    // Customer Address CRUD & Default setter
    Route::resource('addresses', AddressController::class)->except(['show']);
    Route::patch('addresses/{address}/default', [AddressController::class, 'setDefault'])->name('addresses.default');

    // Customer Orders & Live Tracking
    Route::get('/orders', [AccountOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AccountOrderController::class, 'show'])->name('orders.show');

    // Customer Reviews List
    Route::get('/reviews', [\App\Http\Controllers\Account\ReviewController::class, 'index'])->name('reviews.index');
});

/*
|--------------------------------------------------------------------------
| Admin Authentication & Management Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->middleware('throttle:10,1');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout')->middleware('auth');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');

        // Categories CRUD
        Route::resource('categories', AdminCategoryController::class)->except(['show']);

        // Collections CRUD
        Route::resource('collections', AdminCollectionController::class)->except(['show']);

        // Products CRUD
        Route::resource('products', AdminProductController::class)->except(['show']);

        // Nested Product Variants
        Route::post('products/{product}/variants', [AdminProductVariantController::class, 'store'])->name('products.variants.store');
        Route::put('products/{product}/variants/{variant}', [AdminProductVariantController::class, 'update'])->name('products.variants.update');
        Route::delete('products/{product}/variants/{variant}', [AdminProductVariantController::class, 'destroy'])->name('products.variants.destroy');

        // Nested Product Images
        Route::post('products/{product}/images', [AdminProductImageController::class, 'store'])->name('products.images.store');
        Route::patch('products/{product}/images/{image}/primary', [AdminProductImageController::class, 'setPrimary'])->name('products.images.primary');
        Route::post('products/{product}/images/reorder', [AdminProductImageController::class, 'reorder'])->name('products.images.reorder');
        Route::delete('products/{product}/images/{image}', [AdminProductImageController::class, 'destroy'])->name('products.images.destroy');

        // Orders Management & Biteship Fulfillment
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
        Route::patch('orders/{order}/notes', [AdminOrderController::class, 'updateNotes'])->name('orders.notes');
        Route::post('orders/{order}/process-shipping', [AdminOrderController::class, 'processShipping'])->name('orders.process-shipping');
        Route::post('orders/{order}/request-pickup', [AdminOrderController::class, 'requestPickup'])->name('orders.request-pickup');

        // Coupons CRUD
        Route::resource('coupons', AdminCouponController::class)->except(['show']);
        Route::patch('coupons/{coupon}/toggle', [AdminCouponController::class, 'toggle'])->name('coupons.toggle');

        // Reviews Moderation
        Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::patch('reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
        Route::patch('reviews/{review}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
        Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

        // CMS: Hero Slides, Pages, Blog, Stores
        Route::resource('hero-slides', AdminHeroSlideController::class)->except(['show']);
        Route::resource('pages', AdminPageController::class)->except(['show']);
        Route::resource('blog', AdminBlogPostController::class)->except(['show']);
        Route::resource('stores', AdminStoreLocationController::class)->except(['show']);

        // Site Settings (Grouped)
        Route::get('settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [AdminSettingController::class, 'update'])->name('settings.update');

        // Subscribers & Export CSV
        Route::get('subscribers', [AdminSubscriberController::class, 'index'])->name('subscribers.index');
        Route::get('subscribers/export', [AdminSubscriberController::class, 'exportCsv'])->name('subscribers.export');
        Route::delete('subscribers/{subscriber}', [AdminSubscriberController::class, 'destroy'])->name('subscribers.destroy');
    });
});
