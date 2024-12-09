<?php

use App\Http\Controllers\TelegramBotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    Log::info('This is a test log message!');
    return 'This is a test log message';
});

Route::post('telegram/webhook', [TelegramBotController::class, 'handle']);
