<?php

namespace App\Listeners;

use App\Events\VendorRegistrationEvent;
use App\Mail\VendorRegistrationMail;
use App\Traits\EmailTemplateTrait;
use Illuminate\Support\Facades\Mail;

class VendorRegistrationListener
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
    public function handle(VendorRegistrationEvent $event): void
    {
        $this->sendMail($event);
    }

    private function sendMail(VendorRegistrationEvent $event):void{
        $email = $event->email;
        $data = $event->data;
        $this->sendingMail(sendMailTo: $email,userType: $data['userType'],templateName: $data['templateName'],data: $data);
    }
}
