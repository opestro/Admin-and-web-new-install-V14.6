<?php

namespace App\Services;

use App\Traits\FileManagerTrait;

class NotificationService
{
    use FileManagerTrait;
    public function getNotificationAddData(object $request):array
    {
        $image = $request['image'] ? $this->upload(dir:'notification/', format: 'webp',image:  $request->file('image')) : '';
        return [
            'title' => $request['title'],
            'description' => $request['description'],
            'image' => $image,
            'notification_count' => 1
        ];
    }
    public function getNotificationUpdateData(object $request,string|null $notificationImage):array
    {
        $image = $request['image'] ? $this->update(dir:'notification/', oldImage: $notificationImage, format: 'webp',image:  $request->file('image')) : $notificationImage;
        return [
            'title' => $request['title'],
            'description' => $request['description'],
            'image' => $image,
        ];
    }
}
