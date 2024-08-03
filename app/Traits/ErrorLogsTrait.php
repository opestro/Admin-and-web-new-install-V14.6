<?php

namespace App\Traits;

use App\Models\ErrorLogs;

trait ErrorLogsTrait
{
    protected function storeErrorLogsUrl(string $url, int $statusCode): mixed
    {
        $errorLog = ErrorLogs::where('url', $url)->first();
        if(!$this->checkUrl(url:$url)) {
            return null;
        }
        if ($errorLog) {
            ErrorLogs::where('url', $url)->increment('hit_counts');
        } else {
            ErrorLogs::create([
                'status_code' => $statusCode,
                'url' => $url,
                'hit_counts' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $errorLog = ErrorLogs::where('url', $url)->first();
        }
        return $errorLog;
    }
    private function checkUrl($url):mixed
    {
        $skipPatterns = [
            url('/').'/storage/',
            url('/').'/public/',
            url('/').'/vendor/',
            url('/').'/resources/',
            url('/').'/favicon.ico',
            url('/').'/undefined',
            url('/').'/new-notification',
        ];
        foreach ($skipPatterns as $pattern) {
            if (strpos($url, $pattern) === 0) {
                return false;
            }
        }
        return true;

    }
}
