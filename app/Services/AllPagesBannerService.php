<?php

namespace App\Services;

use App\Traits\FileManagerTrait;

class AllPagesBannerService
{
    use FileManagerTrait;

    public function getProcessedData(object $request, object $banner = null): array
    {
        if ($banner) {
            $image = json_decode($banner['value'])->image;
            $oldImage = $image['image_name'] ?? $image;
            $imageName = $request->file('image') ? $this->update(dir:'banner/', oldImage:$oldImage, format: 'webp', image: $request->file('image')) : $oldImage;
        }else {
            $imageName = $this->upload(dir:'banner/', format: 'webp', image: $request->file('image'));
        }
        $imageArray = [
            'image_name' => $imageName,
            'storage' => config('filesystems.disks.default') ?? 'public',
        ];

        return [
            'type' => $request['type'],
            'value' => json_encode([
                'status' => 0,
                'image' => $imageArray,
            ]),
            'created_at' => now()
        ];

    }

    public function deleteImage(string|array $image): bool
    {
        $this->delete(filePath: "/banner/" . ($image['image_name'] ?? $image));
        return true;
    }

}
