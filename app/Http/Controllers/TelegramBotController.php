<?php

namespace App\Http\Controllers;

use App\Services\Telegram\TelegramCommandService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramBotController extends Controller
{
    private TelegramCommandService $telegramCommandService;

    public function __construct(TelegramCommandService $telegramCommandService)
    {
        $this->telegramCommandService = $telegramCommandService;
    }

    public function handle(Request $request)
    {
        Log::info('Telegram Request:', $request->all());
        $commandClass = $this->telegramCommandService->getClass($request);
        if(empty($commandClass)){
            return false;
        }
        $commandClass->execute($request);
    }
}
