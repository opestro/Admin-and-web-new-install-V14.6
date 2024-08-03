<?php

namespace App\Listeners;

use App\Events\DigitalProductDownloadEvent;
use App\Mail\DigitalProductDownloadMail;
use App\Traits\EmailTemplateTrait;
use Illuminate\Support\Facades\Mail;

class DigitalProductDownloadListener
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
    public function handle(DigitalProductDownloadEvent $event): void
    {
        $this->sendMail($event);
    }

    private function sendMail(DigitalProductDownloadEvent $event):void{
        $email = $event->email;
        $data = $event->data;
        $this->sendingMail(sendMailTo: $email,userType: $data['userType'],templateName: $data['templateName'],data: $data);
    }
}
