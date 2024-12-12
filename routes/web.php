<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;


Route::get('/login', [AuthController::class, 'login'])->name(name: 'login');
Route::post('/login-form', [AuthController::class, 'loginForm'])->name(name: 'login-form');

Route::get('/', [SiteController::class, 'index'])->name('home');

Route::get('/users', [UserController::class, 'index'])->name(name: 'users');
Route::get('/users/create', [UserController::class, 'create'])->name('user/create');
Route::post('/users/store', [UserController::class, 'store'])->name('user/store');

Route::get('/logout', [AuthController::class, 'logout'])->name(name: 'logout');


Route::get('setwebhook', function () {
    $response = Telegram::setWebhook(['url' => env('TELEGRAM_WEBHOOK_URL')]);
    return response()->json($response);
});
