<?php
/*
|--------------------------------------------------------------------------
| Install Routes
|--------------------------------------------------------------------------
| This route is responsible for handling the installation process
*/

use App\Http\Controllers\UpdateController;
use Illuminate\Support\Facades\Route;

Route::controller(UpdateController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('update-system', 'updateSoftware')->name('update-system');
});

Route::fallback(function () {
    return redirect('/');
});
