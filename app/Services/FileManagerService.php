<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Madnest\Madzipper\Facades\Madzipper;

class FileManagerService
{

    public function uploadImages(object $request): bool
    {
        if ($request->hasfile('images')) {
            $images = $request->file('images');
            foreach ($images as $image) {
                $name = $image->getClientOriginalName();
                Storage::disk('local')->put($request['path'] . '/' . $name, file_get_contents($image));
            }
        }
        if ($request->hasfile('file')) {
            Madzipper::make($request->file('file'))->extractTo('storage/app/' . $request['path']);
        }
        return true;
    }

    private function getFileName($path): bool|string
    {
        $temp = explode('/', $path);
        return end($temp);
    }

    private function getFileExtension($name): bool|string
    {
        $temp = explode('.', $name);
        return end($temp);
    }

    private function getPathForDatabase($fullPath): bool|string
    {
        $temp = explode('/', $fullPath, 3);
        return end($temp);
    }

    public function formatFileAndFolders($files, $type): array
    {
        $data = [];
        foreach ($files as $file) {
            $name = self::getFileName($file);
            $ext = self::getFileExtension($name);
            $path = '';
            if ($type == 'file') {
                $path = $file;
            }
            if ($type == 'folder') {
                $path = $file;
            }
            if (in_array($ext, ['jpg', 'png', 'jpeg', 'gif', 'bmp', 'tif', 'tiff', 'webp']) || $type == 'folder')
                $data[] = [
                    'name' => $name,
                    'path' => $path,
                    'db_path' => self::getPathForDatabase($file),
                    'type' => $type
                ];
        }
        return $data;
    }
}
