<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

class ContactService
{
    public function getAddData(object $request): array
    {
        return [
            'name' => $request['name'],
            'email' => $request['email'],
            'mobile_number' => $request['mobile_number'],
            'subject' => $request['subject'],
            'message' => $request['message'],
        ];
    }
    public function getMailData(object $request, array $data, object $contact, string $companyName): array
    {
        Mail::send('email-templates.customer-message', $data, function ($message) use ($contact, $request, $companyName) {
            $message->to($contact['email'], $companyName)
                ->subject($request['subject']);
        });

        return [
            'reply' => json_encode([
                'subject' => $request['subject'],
                'body' => $request['mail_body']
            ])
        ];
    }

}
