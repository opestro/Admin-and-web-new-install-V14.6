<?php

namespace App\Services;

use App\Traits\FileManagerTrait;

class VendorRegistrationSettingService
{
    use FileManagerTrait;
    public function getHeaderAndSellWithUsUpdateData(object $request,$image):array
    {
        return [
            'title' => $request['title'],
            'sub_title' => $request['sub_title'],
            'image' => $this->getImageDataProcess(request: $request,image:$image,requestImageName: 'image'),
        ];
    }
    public function getBusinessProcessUpdateData(object $request):array
    {
        return [
            'title' => $request['title'],
            'sub_title' => $request['sub_title'],
        ];
    }
    public function getBusinessProcessStepUpdateData(object $request,$businessProcessStep):array
    {
        $array = [];
        for($index=1;$index<=3;$index++){
            $image = (isset($businessProcessStep[$index-1]) ? $businessProcessStep[$index-1]?->image : null);
            $array[]=[
                'title' => $request['section_'.$index.'_title'],
                'description' => $request['section_'.$index.'_description'],
                'image' => $this->getImageDataProcess(request: $request,image: $image,requestImageName: 'section_'.$index.'_image'),
            ];
        }
        return $array;
    }
    protected function getImageDataProcess($request ,$image,$requestImageName):array
    {
        $storage = config('filesystems.disks.default') ?? 'public';
        $imageData = is_string($image) ? $image: $image?->image_name;
        if ($imageData) {
            $imageName = $request->file($requestImageName) ? $this->update(dir:'vendor-registration-setting/', oldImage: $imageData, format: 'webp', image: $request->file($requestImageName)) : $imageData;
            $storage = $request->file($requestImageName) ? $storage : ($image?->storage ?? $storage) ;
        }else {
            $imageName = $request->file($requestImageName) ? $this->upload(dir:'vendor-registration-setting/', format: 'webp', image: $request->file($requestImageName)) :null;
        }
        return [
            'image_name' => $imageName,
            'storage' =>  $storage
        ];
    }

    public function getVendorRegistrationReasonData(object $request):array
    {
        return [
            'title' => $request['title'],
            'description' => $request['description'],
            'priority' => $request['priority'],
            'status' => $request->get('status',0),
        ];
    }
    public function getDownloadVendorAppUpdateData(object $request,$image):array
    {
        return [
            'title' => $request['title'],
            'sub_title' => $request['sub_title'],
            'image' => $this->getImageDataProcess(request: $request,image:$image,requestImageName: 'image'),
            'download_google_app' => $request['download_google_app'],
            'download_google_app_status' => $request->get('download_google_app_status',0),
            'download_apple_app' => $request['download_apple_app'],
            'download_apple_app_status' => $request->get('download_apple_app_status',0),
        ];
    }
}
