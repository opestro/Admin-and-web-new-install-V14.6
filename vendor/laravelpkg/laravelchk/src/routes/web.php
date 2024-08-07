<?php

/*Route::group(['namespace']);*/

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Laravelpkg\Laravelchk\Http\Controllers', 'middleware' => ['web']], function () {
    Route::any(base64_decode('ZG12Zg=='), 'LaravelchkController@'.base64_decode('ZG12Zg=='))->name(base64_decode('ZG12Zg=='));
    Route::get(base64_decode('YWN0aXZhdGlvbi1jaGVjaw=='), 'LaravelchkController@'.base64_decode('YWN0Y2g='))->name(base64_decode('YWN0aXZhdGlvbi1jaGVjaw=='));
});
