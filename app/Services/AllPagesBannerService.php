<?php

namespace App\Services;

use App\Traits\FileManagerTrait;

class AllPagesBannerService
{
    use FileManagerTrait;

    public function getProcessedData(object $request, object $banner = null): array
    {
        if ($banner) {
            $oldImage = json_decode($banner['value'])->image ?? '';
            $imageName = $request->file('image') ? $this->update(dir:'banner/', oldImage:$oldImage, format: 'webp', image: $request->file('image')) : $oldImage;
        }else {
            $imageName = $this->upload(dir:'banner/', format: 'webp', image: $request->file('image'));
        }

        return [
            'type' => $request['type'],
            'value' => json_encode([
                'status' => 0,
                'image' => $imageName,
            ]),
            'created_at' => now()
        ];

    }

    public function deleteImage(string $image): bool
    {
        $this->delete(filePath: "/banner/" . $image);
        return true;
    }

}
