<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

Route::get('/', [DashboardController::class, 'home'])->name('home');
Route::get('/users', [DashboardController::class, 'users'])->name(name: 'users');

Route::get('/login', [DashboardController::class, 'login'])->name(name: 'login');
Route::post('/login-form', [DashboardController::class, 'loginForm'])->name(name: 'login-form');
Route::get('/logout', [DashboardController::class, 'logout'])->name(name: 'logout');


Route::get('setwebhook', function () {
    $response = Telegram::setWebhook(['url' => env('TELEGRAM_WEBHOOK_URL')]);
    return response()->json($response);
});
