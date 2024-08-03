<?php

namespace App\Listeners;

use App\Events\OrderStatusEvent;
use App\Traits\PushNotificationTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderStatusListener
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
    public function handle(OrderStatusEvent $event): void
    {
        $this->sendNotification($event);
    }

    private function sendNotification(OrderStatusEvent $event):void{
        $key = $event->key;
        $type = $event->type;
        $order = $event->order;
        $this->sendOrderNotification(key: $key, type: $type, order: $order);
    }
}
