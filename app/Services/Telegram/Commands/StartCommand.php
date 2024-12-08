<?php

namespace App\Services\Telegram\Commands;

use App\Helpers\TelegramBotHelper;

class StartCommand
{
    public static function handel($request)
    {
        $message = $request->input('message.text');
        if ($message == '/start') {
            return true;
        }
        return false;
    }

    public static function execute($chatId)
    {
        $message = 'Welcome to the Anvar Jigga Clean Service chatbot!.👋\n Choose from categories:';
        $inlineKeyboard = [];

        TelegramBotHelper::inlineKeyboardAndMessage($chatId, $message, $inlineKeyboard);
    }
}
