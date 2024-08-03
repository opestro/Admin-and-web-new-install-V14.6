<?php

namespace App\Listeners;

use App\Events\OrderReceivedNotifySellerEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class OrderReceivedNotifySellerListener
{
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
    public function handle(OrderReceivedNotifySellerEvent $event): void
    {
        $this->sendMail($event);
    }

    private function sendMail(OrderReceivedNotifySellerEvent $event):void{
        $orderId = $event->orderId;
        $email = $event->email;
        try{
            Mail::to($email)->send(new \App\Mail\OrderReceivedNotifySeller($orderId));
        }catch(\Exception $exception) {
            info($exception);
        }
    }
}
