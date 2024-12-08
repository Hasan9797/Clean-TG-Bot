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

    public function execute($request)
    {
        $chatId = $request->input('message.chat.id');
        $message = 'Welcome to the Anvar Jigga Clean Service chatbot!.👋\n Choose from categories:';
        $inlineKeyboard = [];

        // TelegramBotHelper::inlineKeyboardAndMessage($chatId, $message, $inlineKeyboard);
        TelegramBotHelper::sendMessage($chatId, $message);
    }
}
