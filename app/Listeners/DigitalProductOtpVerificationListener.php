<?php

namespace App\Listeners;
use App\Events\DigitalProductOtpVerificationEvent;
use App\Traits\EmailTemplateTrait;

class DigitalProductOtpVerificationListener
{
    use EmailTemplateTrait;
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
    public function handle(DigitalProductOtpVerificationEvent $event): void
    {
        $this->sendMail($event);
    }

    private function sendMail(DigitalProductOtpVerificationEvent $event):void{
        $email = $event->email;
        $data = $event->data;
        $this->sendingMail(sendMailTo: $email,userType: $data['userType'],templateName: $data['templateName'],data: $data);
    }
}
