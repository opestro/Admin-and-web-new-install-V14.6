<?php

namespace App\Listeners;

use App\Events\CustomerStatusUpdateEvent;
use App\Traits\EmailTemplateTrait;

class CustomerStatusUpdateListener
{
    use EmailTemplateTrait;
    public function __construct()
    {
        //
    }

    public function handle(CustomerStatusUpdateEvent $event): void
    {
        $this->sendMail($event);
    }

    private function sendMail(CustomerStatusUpdateEvent $event):void{
        $email = $event->email;
        $data = $event->data;
        $this->sendingMail(sendMailTo: $email,userType: $data['userType'],templateName: $data['templateName'],data: $data);
    }
}
