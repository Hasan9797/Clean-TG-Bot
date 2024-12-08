<?php

namespace App\Services\Telegram\Commands;

use App\Helpers\TelegramBotHelper;

class CategoriesCommand
{
    public static function handel()
    {
        return 'Categories';
    }


    public static function categoryCommand($chatId)
    {
        $message = 'Welcome to the Anvar Jigga Clean Service chatbot!.👋\n Choose from categories:';
        $inlineKeyboard = [];

        TelegramBotHelper::inlineKeyboardAndMessage($chatId, $message, $inlineKeyboard);
    }
}
