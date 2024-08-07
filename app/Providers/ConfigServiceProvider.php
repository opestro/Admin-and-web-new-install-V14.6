<?php

namespace App\Providers;

use App\Models\BusinessSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        try {
            $timezone = BusinessSetting::where(['type' => 'timezone'])->first();
            if ($timezone) {
                Config::set('timezone', $timezone->value);
                date_default_timezone_set($timezone->value);
            }
        } catch (\Exception $ex) {}
    }
}
