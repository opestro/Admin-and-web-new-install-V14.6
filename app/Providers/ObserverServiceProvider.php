<?php

namespace App\Providers;

use App\Models\Product;
use App\Observers\BusinessSettingsObserver;
use App\Observers\CurrencyObserver;
use App\Observers\OrderObserver;
use App\Observers\ProductObserver;
use App\Observers\TranslationObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Product::observe([
//             ProductObserver::class,
//             OrderObserver::class,
//             BusinessSettingsObserver::class,
//             CurrencyObserver::class,
//             TranslationObserver::class
        ]);
    }
}
