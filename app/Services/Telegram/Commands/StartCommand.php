<?php

namespace App\Services\Telegram\Commands;

use App\Helpers\TelegramBotHelper;

class StartCommand
{
    public static function handel($request)
    {
        $message = strval($request->input('message.text'));
        if ($message == '/start') {
            return true;
        }
        return false;
    }

    public function execute($request)
    {
        $chatId = $request->input('message.chat.id');
        $message = 'Tilni tanlang / Выберите язык:';

        $inlineKeyboard = [
            'inline_keyboard' => [
                [
                    ['text' => '🇷🇺 Русский', 'callback_data' => 'lang_ru'],
                    ['text' => '🇺🇿 O‘zbek', 'callback_data' => 'lang_uz'],
                ],
            ],
        ];

        TelegramBotHelper::inlineKeyboardAndMessage($chatId, $message, $inlineKeyboard);
    }
}
