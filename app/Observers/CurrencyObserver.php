<?php

namespace App\Observers;

use App\Models\Currency;

class CurrencyObserver
{
    /**
     * Handle the currency "created" event.
     */
    public function created(Currency $currency): void
    {
        $currency->flushCache();
    }

    /**
     * Handle the currency "updated" event.
     */
    public function updated(Currency $currency): void
    {
        $currency->flushCache();
    }

    /**
     * Handle the currency "deleted" event.
     */
    public function deleted(Currency $currency): void
    {
        $currency->flushCache();
    }

    /**
     * Handle the currency "restored" event.
     */
    public function restored(Currency $currency): void
    {
        $currency->flushCache();
    }

    /**
     * Handle the currency "force deleted" event.
     */
    public function forceDeleted(currency $currency): void
    {
        $currency->flushCache();
    }
}
