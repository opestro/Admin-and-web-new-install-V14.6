<?php

namespace App\Observers;

use App\Models\BusinessSetting;

class BusinessSettingsObserver
{
    /**
     * Handle the BusinessSettings "created" event.
     */
    public function created(BusinessSetting $businessSettings): void
    {
        $businessSettings->flushCache();
    }

    /**
     * Handle the BusinessSettings "updated" event.
     */
    public function updated(BusinessSetting $businessSettings): void
    {
        $businessSettings->flushCache();
    }

    /**
     * Handle the BusinessSettings "deleted" event.
     */
    public function deleted(BusinessSetting $businessSettings): void
    {
        $businessSettings->flushCache();
    }

    /**
     * Handle the BusinessSettings "restored" event.
     */
    public function restored(BusinessSetting $businessSettings): void
    {
        //
    }

    /**
     * Handle the BusinessSettings "force deleted" event.
     */
    public function forceDeleted(BusinessSetting $businessSettings): void
    {
        //
    }
}
