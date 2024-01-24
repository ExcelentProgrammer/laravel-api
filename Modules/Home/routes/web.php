<?php

use Illuminate\Support\Facades\Route;
use Modules\Home\App\Http\Controllers\HomeController;

Route::group([], function () {
    Route::resource('home', HomeController::class)->names('home');
});
