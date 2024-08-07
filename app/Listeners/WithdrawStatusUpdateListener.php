<?php

namespace App\Listeners;

use App\Events\WithdrawStatusUpdateEvent;
use App\Traits\PushNotificationTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class WithdrawStatusUpdateListener
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
    public function handle(WithdrawStatusUpdateEvent $event): void
    {
        $this->sendNotification($event);
    }
    public function sendNotification($event): void
    {
        $key = $event->key;
        $type = $event->type;
        $lang = $event->lang;
        $status = $event->status;
        $fcmToken = $event->fcmToken;
        $this->withdrawStatusUpdateNotification(key: $key, type: $type, lang: $lang, status: $status,fcmToken: $fcmToken);
    }
}
