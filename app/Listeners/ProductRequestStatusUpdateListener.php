<?php

namespace App\Listeners;

use App\Events\ProductRequestStatusUpdateEvent;
use App\Traits\PushNotificationTrait;

class ProductRequestStatusUpdateListener
{
    use PushNotificationTrait;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ProductRequestStatusUpdateEvent $event): void
    {
        $this->sendNotification($event);
    }

    private function sendNotification(ProductRequestStatusUpdateEvent $event):void{
        $key = $event->key;
        $type = $event->type;
        $lang = $event->lang;
        $fcmToken = $event->fcmToken;
        $this->productRequestStatusUpdateNotification(key: $key, type: $type, lang: $lang,fcmToken: $fcmToken);
    }
}
