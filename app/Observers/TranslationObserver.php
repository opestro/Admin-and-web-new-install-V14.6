<?php

namespace App\Observers;

use App\Models\Translation;

class TranslationObserver
{
    /**
     * Handle the Translation "created" event.
     */
    public function created(Translation $translation): void
    {
        $translation->flushCache();
    }

    /**
     * Handle the Translation "updated" event.
     */
    public function updated(Translation $translation): void
    {
        $translation->flushCache();
    }

    /**
     * Handle the Translation "deleted" event.
     */
    public function deleted(Translation $translation): void
    {
        $translation->flushCache();
    }

    /**
     * Handle the Translation "restored" event.
     */
    public function restored(Translation $translation): void
    {
        $translation->flushCache();
    }

    /**
     * Handle the Translation "force deleted" event.
     */
    public function forceDeleted(Translation $translation): void
    {
        $translation->flushCache();
    }
}
