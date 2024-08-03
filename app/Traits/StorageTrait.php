<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Storage;

trait StorageTrait
{
    public function storage(): MorphMany
    {
        return $this->morphMany(\App\Models\Storage::class, 'data');
    }
    protected function storageLink($path, $data, $type): string|array
    {
        if ($type == 's3' && $this->storageConnectionCheck() == 's3') {
            $fullPath = ltrim($path . '/' . $data, '/');
            if ($this->fileCheck(disk: 's3', path: $fullPath) && !empty($data)) {
                return [
                    'key' => $data,
                    'path' => Storage::disk('s3')->url($fullPath),
                    'status' => 200,
                ];
            }
        } else {
            if ($this->fileCheck(disk: 'public', path: $path . '/' . $data) && !empty($data)) {
                return [
                    'key' => $data,
                    'path' => dynamicStorage('storage/app/public/' . $path . '/' . $data),
                    'status' => 200,
                ];
            }
        }
        return [
            'key' => $data,
            'path' => null,
            'status' => 404,
        ];
    }

    private function fileCheck($disk, $path): bool
    {
        try{
            return Storage::disk($disk)->exists($path);
        }catch (\Exception $exception){
            return false;
        }
    }

    protected function storageConnectionCheck(): string
    {
        return config('filesystems.disks.default') ?? 'public';
    }
}
