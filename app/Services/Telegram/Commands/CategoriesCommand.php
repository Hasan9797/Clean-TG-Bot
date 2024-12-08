<?php

namespace App\Services\Telegram\Commands;

use App\Helpers\TelegramBotHelper;

class CategoriesCommand
{
    public static function handel($request)
    {
        $message = strval($request->input('message.text'));

        if ($message == '/categories') {
            return true;
        }
        return false;
    }


    public static function execute($request, $language = 'lang_uz')
    {
        $chatId = $request->input('message.chat.id')
            ?? $request->input('callback_query.message.chat.id');

        $messageUz = 'Hizmat turlarini tanlang:';
        $messageRu = 'Выберите типы услуг:';

        $inlineKeyboardRu = [
            'inline_keyboard' => [
                [
                    ['text' => 'Генеральная уборка', 'callback_data' => 'service_1'],
                    ['text' => 'Повседневная уборка', 'callback_data' => 'service_2'],
                    ['text' => 'Уборка офиса', 'callback_data' => 'service_3'],
                ],
            ],
        ];

        $inlineKeyboardUz = [
            'inline_keyboard' => [
                [
                    ['text' => 'Генеральная уборка', 'callback_data' => 'service_1'],
                    ['text' => 'Повседневная уборка', 'callback_data' => 'service_2'],
                    ['text' => 'Уборка офиса', 'callback_data' => 'service_3'],
                ],
            ],
        ];

        $message = $messageUz;
        $inlineKeyboard = $inlineKeyboardUz;

        if (strval($language) === 'lang_ru') {
            $message = $messageRu;
            $inlineKeyboard = $inlineKeyboardRu;
        }

        TelegramBotHelper::inlineKeyboardAndMessage($chatId, $message, $inlineKeyboard);
    }
}
