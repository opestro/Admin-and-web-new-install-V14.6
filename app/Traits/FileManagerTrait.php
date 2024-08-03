<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait FileManagerTrait
{
    /**
     * upload method working for image
     * @param string $dir
     * @param string $format
     * @param $image
     * @return string
     */
    protected function upload(string $dir, string $format, $image = null): string
    {
        if (!is_null($image)) {
            $isOriginalImage = in_array($image->getClientOriginalExtension(), ['gif', 'svg']);
            if($isOriginalImage){
                $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $image->getClientOriginalExtension();
            }else{
                $image_webp =  Image::make($image)->encode($format);
                $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
            }

            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
            }

            if($isOriginalImage) {
                Storage::disk('public')->put($dir . $imageName, file_get_contents($image));
            }else{
                Storage::disk('public')->put($dir . $imageName, $image_webp);
                $image_webp->destroy();
            }
        } else {
            $imageName = 'def.png';
        }

        return $imageName;
    }

    /**
     * @param string $dir
     * @param string $format
     * @param $file
     * @return string
     */
    public function fileUpload(string $dir, string $format, $file = null): string
    {
        if (!is_null($file)) {
            $fileName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
            }
            Storage::disk('public')->put($dir . $fileName, file_get_contents($file));
        } else {
            $fileName = 'def.png';
        }

        return $fileName;
    }

    /**
     * @param string $dir
     * @param $oldImage
     * @param string $format
     * @param $image
     * @param string $fileType image/file
     * @return string
     */
    public function update(string $dir, $oldImage, string $format, $image, string $fileType = 'image'): string
    {
        if (Storage::disk('public')->exists($dir . $oldImage)) {
            Storage::disk('public')->delete($dir . $oldImage);
        }

        return $fileType == 'file' ? $this->fileUpload($dir, $format, $image) : $this->upload($dir, $format, $image);
    }

    /**
     * @param string $filePath
     * @return array
     */
    protected function  delete(string $filePath): array
    {
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
        return [
            'success' => 1,
            'message' => translate('Removed_successfully')
        ];
    }
}
