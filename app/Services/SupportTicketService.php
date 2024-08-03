<?php

namespace App\Services;

use App\Traits\FileManagerTrait;

class SupportTicketService
{
    use FileManagerTrait;

    public function getAddData(object $request): array
    {
        $images = [] ;
        if ($request->file('image')) {
            foreach ($request->image as $key => $imageFile) {
                $image_name = $this->upload(dir:'support-ticket/', format: 'webp', image: $imageFile);
                $images[] = $image_name;
            }
        }

        return [
            'admin_message' => $request['replay'],
            'admin_id' => $request['adminId'],
            'support_ticket_id' => $request['id'],
            'attachment' => json_encode($images),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

}
