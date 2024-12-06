<?php

namespace App\Services\Telegram\Commands;

use App\Helpers\TelegramBotHelper;

class StratCommand
{
    public function startCommand($chatId, $message, $inlineKeyboard)
    {
        TelegramBotHelper::inlineKeyboardAndMessage($chatId, $message, $inlineKeyboard);
    }
}

