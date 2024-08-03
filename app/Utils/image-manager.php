<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageManager
{
    public static function upload(string $dir, string $format, $image, $file_type = 'image')
    {
        if ($image != null) {
            if(in_array($image->getClientOriginalExtension(), ['gif', 'svg'])){
                $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $image->getClientOriginalExtension();
            }else{
                $image_webp =  Image::make($image)->encode($format, 90);
                $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
            }

            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
            }

            if(in_array($image->getClientOriginalExtension(), ['gif', 'svg'])) {
                Storage::disk('public')->put($dir . $imageName, file_get_contents($image));
            }else{
                Storage::disk('public')->put($dir . $imageName, $image_webp);
                $image_webp->destroy();
            }

        } else {
            $imageName = 'def.webp';
        }

        return $imageName;
    }

    public static function file_upload(string $dir, string $format, $file = null)
    {
        if ($file != null) {
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

    public static function update(string $dir, $old_image, string $format, $image, $file_type = 'image')
    {
        if (Storage::disk('public')->exists($dir . $old_image)) {
            Storage::disk('public')->delete($dir . $old_image);
        }

        $imageName = $file_type == 'file' ? ImageManager::file_upload($dir, $format, $image) : ImageManager::upload($dir, $format, $image);

        return $imageName;
    }

    public static function delete($full_path)
    {
        if (Storage::disk('public')->exists($full_path)) {
            Storage::disk('public')->delete($full_path);
        }

        return [
            'success' => 1,
            'message' => 'Removed successfully !'
        ];

    }
}
