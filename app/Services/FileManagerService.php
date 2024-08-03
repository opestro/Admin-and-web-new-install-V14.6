<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Madnest\Madzipper\Facades\Madzipper;
use ZipArchive;
class FileManagerService
{

    public function uploadImages(object $request): bool
    {
        $storage = $request['storage'];
        if ($request->hasfile('images')) {
            $images = $request->file('images');
            foreach ($images as $image) {
                $name = $image->getClientOriginalName();
                if($storage == 's3'){
                    Storage::disk($storage)->putFileAs($request->path, $image, $name);
                }else{
                    Storage::disk('local')->put($request['path'] . '/' . $name, file_get_contents($image));
                }

            }
        }
        if ($request->hasfile('file')) {
            $file = $request->file('file');
            if ($storage === 's3') {
                $zip = new ZipArchive;
                if ($zip->open($file->path()) === true) {
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $stat = $zip->statIndex($i);

                        if (!$stat['name'] || $this->shouldSkip($stat['name'])) {
                            continue;
                        }
                        $filename = $stat['name'];
                        $fileContent = $zip->getFromIndex($i);
                        $format = pathinfo($filename, PATHINFO_EXTENSION);
                        $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
                        $s3 = Storage::disk('s3');
                        $s3Path = $request->path . '/' . $imageName;
                        $s3->put($s3Path, $fileContent, 'public');
                    }
                    $zip->close();
                }
            }else{
                Madzipper::make($request->file('file'))->extractTo('storage/app/' . $request['path']);
            }
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
            $name = $this->getFileName($file);
            $ext = $this->getFileExtension($name);
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
                    'db_path' => $this->getPathForDatabase($file),
                    'type' => $type
                ];
        }
        return $data;
    }

    private function shouldSkip($filename) {
        $skipFiles = [
            '__MACOSX/', // Skip macOS metadata files
            '.DS_Store', // Skip .DS_Store files
            'Thumbs.db', // Skip Thumbs.db files (Windows)
            // Add more conditions as needed
        ];

        foreach ($skipFiles as $skipFile) {
            if (strpos($filename, $skipFile) === 0) {
                return true;
            }
        }

        return false;
    }

}
