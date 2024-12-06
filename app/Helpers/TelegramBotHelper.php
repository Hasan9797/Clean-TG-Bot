<?php

namespace App\Helpers;

use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotHelper
{

    public static function inlineKeyboardAndMessage($chatId, $message, $inlineKeyboard)
    {
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $message,
            'reply_markup' => $inlineKeyboard,
        ]);
    }
}
