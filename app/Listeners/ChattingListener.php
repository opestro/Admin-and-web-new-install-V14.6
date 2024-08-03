<?php

namespace App\Listeners;

use App\Events\ChattingEvent;
use App\Traits\PushNotificationTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ChattingListener
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
    public function handle(ChattingEvent $event): void
    {
        $this->sendNotification($event);
    }

    private function sendNotification(ChattingEvent $event):void{
        $key = $event->key;
        $type = $event->type;
        $userData = $event->userData;
        $messageForm = $event->messageForm;
        $this->chattingNotification(key: $key, type: $type, userData: $userData, messageForm: $messageForm);
    }
}
