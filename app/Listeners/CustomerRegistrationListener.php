<?php

namespace App\Listeners;

use App\Events\CustomerRegistrationEvent;
use App\Mail\CustomerRegistrationMail;
use App\Traits\EmailTemplateTrait;
use Illuminate\Support\Facades\Mail;

class CustomerRegistrationListener
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
    public function handle(CustomerRegistrationEvent $event): void
    {
        $this->sendMail($event);
    }

    private function sendMail(CustomerRegistrationEvent $event):void{
        $email = $event->email;
        $data = $event->data;
        $this->sendingMail(sendMailTo: $email,userType: $data['userType'],templateName: $data['templateName'],data: $data);

    }
}
