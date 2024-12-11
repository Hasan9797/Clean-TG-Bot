<?php

use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/users', [SiteController::class, 'users'])->name(name: 'users');

Route::get('/login', [SiteController::class, 'login'])->name(name: 'login');
Route::post('/login-form', [SiteController::class, 'loginForm'])->name(name: 'login-form');
Route::get('/logout', [SiteController::class, 'logout'])->name(name: 'logout');


Route::get('setwebhook', function () {
    $response = Telegram::setWebhook(['url' => env('TELEGRAM_WEBHOOK_URL')]);
    return response()->json($response);
});
