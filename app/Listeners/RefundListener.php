<?php

namespace App\Listeners;

use App\Events\RefundEvent;
use App\Traits\PushNotificationTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RefundListener
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
    public function handle(RefundEvent $event): void
    {
        $this->sendNotification($event);
    }

    private function sendNotification(RefundEvent $event):void{
        $status = $event->status;
        $order = $event->order;

        if ($order['seller_is'] == 'seller') {
            if ($status != 'rejected' && $status != 'refunded') {
                $key = 'refund_request_status_changed_by_admin';
            } elseif ($status == 'rejected') {
                $key = 'refund_request_canceled_message';
            }elseif ($status == 'refund_request') {
                $key = 'refund_request_message';
            } else {
                $key = 'order_refunded_message';
            }
            $this->sendOrderNotification(key: $key, type: 'seller', order: $order);
        }

        if ($status == 'refunded') {
            $this->sendOrderNotification(key: 'order_refunded_message', type: 'customer', order: $order);
        } elseif ($status == 'rejected') {
            $this->sendOrderNotification(key: 'refund_request_canceled_message', type: 'customer', order: $order);
        }
    }
}
