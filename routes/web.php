<?php

use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

Route::get('/', function () {
    return view('welcome');
});


Route::get('setwebhook', function () {
    Telegram::setWebhook(['url' => env('TELEGRAM_WEBHOOK_URL')]);
    return view('welcome');
});
