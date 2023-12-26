<?php


use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::post("register", [AuthController::class, "register"]);
Route::post("login", [AuthController::class, "login"]);
Route::post("reset", [AuthController::class, "reset"]);
Route::post("resetConfirm", [AuthController::class, "resetConfirm"]);
Route::post("setPassword", [AuthController::class, "setPassword"]);
Route::post("confirm", [AuthController::class, "confirm"]);
Route::get("me", [AuthController::class, "me"]);
Route::post("update", [AuthController::class, "update"]);
