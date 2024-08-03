<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
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
        $storage = config('filesystems.disks.default') ?? 'public';

        if (!is_null($image)) {
            if (!$this->checkFileExists($dir)['status']) {
                Storage::disk($storage)->makeDirectory($dir);
            }

            $isOriginalImage = in_array($image->getClientOriginalExtension(), ['gif', 'svg']);
            if ($isOriginalImage) {
                $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $image->getClientOriginalExtension();
                Storage::disk($storage)->put($dir . $imageName, file_get_contents($image));
            } else {
                $image_webp = Image::make($image)->encode($format);
                $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
                Storage::disk($storage)->put($dir . $imageName, $image_webp);
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
        $storage = config('filesystems.disks.default') ?? 'public';
        if (!is_null($file)) {
            $fileName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
            if (!$this->checkFileExists($dir)['status']) {
                Storage::disk($storage)->makeDirectory($dir);
            }
            Storage::disk($storage)->put($dir . $fileName, file_get_contents($file));
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
        if ($this->checkFileExists(filePath: $dir . $oldImage)['status']) {
            Storage::disk($this->checkFileExists(filePath: $dir . $oldImage)['disk'])->delete($dir . $oldImage);
        }
        return $fileType == 'file' ? $this->fileUpload($dir, $format, $image) : $this->upload($dir, $format, $image);
    }

    /**
     * @param string $filePath
     * @return array
     */
    protected function  delete(string $filePath): array
    {
        if ($this->checkFileExists(filePath: $filePath)['status']) {
            Storage::disk($this->checkFileExists(filePath: $filePath)['disk'])->delete($filePath);
        }
        return [
            'success' => 1,
            'message' => translate('Removed_successfully')
        ];
    }

    public function setStorageConnectionEnvironment(): void
    {
        $storageConnectionType = getWebConfig(name: 'storage_connection_type') ?? 'public';
        Config::set('filesystems.disks.default', $storageConnectionType);
        $storageConnectionS3Credential = getWebConfig(name: 'storage_connection_s3_credential');
        if ($storageConnectionType == 's3' && !empty($storageConnectionS3Credential)) {
            Config::set('filesystems.disks.' . $storageConnectionType, $storageConnectionS3Credential);
        }
    }

    private function checkFileExists(string $filePath): array
    {
        if (Storage::disk('public')->exists($filePath)) {
            return [
                'status' => true,
                'disk' => 'public'
            ];
        } elseif (config('filesystems.disks.default') == 's3' && Storage::disk('s3')->exists($filePath)) {
            return [
                'status' => true,
                'disk' => 's3'
            ];
        } else {
            return [
                'status' => false,
                'disk' => config('filesystems.disks.default') ?? 'public'
            ];
        }
    }
}
