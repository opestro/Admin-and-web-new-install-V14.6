<?php

namespace App\Services;

use App\Traits\FileManagerTrait;

class MostDemandedService
{
    use FileManagerTrait;

    public function getProcessedData(object $request, string $image = null): array
    {
        if ($image) {
            $imageName = $request->file('image') ? $this->update(dir:'most-demanded/', oldImage:$image, format: 'webp', image: $request->file('image')) : $image;
        }else {
            $imageName = $this->upload(dir:'most-demanded/', format: 'webp', image: $request->file('image'));
        }

        return [
            'product_id'=>$request['product_id'],
            'banner' => $imageName,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

}
