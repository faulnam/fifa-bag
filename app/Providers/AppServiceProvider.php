<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (
            isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'
            || request()->header('x-forwarded-proto') === 'https'
            || str_contains(request()->header('host') ?? '', 'ngrok')
            || str_contains(request()->getHttpHost(), 'ngrok')
        ) {
            URL::forceScheme('https');
        }

        // Register DemoActivityObserver for automatic 10-minute demo rollback
        $observer = \App\Observers\DemoActivityObserver::class;
        $trackedModels = [
            \App\Models\Product::class,
            \App\Models\ProductVariant::class,
            \App\Models\ProductImage::class,
            \App\Models\Category::class,
            \App\Models\Collection::class,
            \App\Models\BlogPost::class,
            \App\Models\HeroSlide::class,
            \App\Models\Page::class,
            \App\Models\StoreLocation::class,
            \App\Models\Coupon::class,
            \App\Models\Review::class,
            \App\Models\NewsletterSubscriber::class,
            \App\Models\SiteSetting::class,
            \App\Models\Order::class,
            \App\Models\OrderItem::class,
            \App\Models\Address::class,
            \App\Models\Shipment::class,
        ];

        foreach ($trackedModels as $modelClass) {
            if (class_exists($modelClass)) {
                $modelClass::observe($observer);
            }
        }
    }
}
