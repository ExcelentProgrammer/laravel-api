<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('v1')->name('api::')->group(function () {
    Route::get('home', fn (Request $request) => $request->user())->name('home');
});
