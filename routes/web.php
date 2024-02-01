<?php

use App\Events\MessageSend;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});


Route::get("test", function () {
    MessageSend::broadcast("salom");
    return Response::json(['success' => true]);
});
