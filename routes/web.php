<?php

use App\Events\MessageSend;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});


Route::get("test", function () {
    event(new MessageSend("salom"));
    return Response::json(['success' => true]);
});
