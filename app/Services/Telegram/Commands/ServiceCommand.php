<?php

namespace App\Services\Telegram\Commands;

use App\Helpers\TelegramBotHelper;

class ServiceCommand
{
    public static function handel($request)
    {
        $message = explode('_', strval($request->input('message.text')));

        if (count($message) === 2 && $message[0] === 'service') {
            return true;
        }
        return false;
    }


    public function execute($request, $language = 'lang_uz')
    {
        $chatId = $request->input('callback_query.message.chat.id');
        $messageId = $request->input('callback_query.message.id');

        $messageUz = 'Hizmat turlarini tanlang:';
        $messageRu = 'Выберите типы услуг:';

        $inlineKeyboardRu = [
            'inline_keyboard' => [
                [
                    ['text' => 'Генеральная уборка', 'callback_data' => 'service_1'],
                    ['text' => 'Уборка после свадьба', 'callback_data' => 'service_2'],
                ],
                [
                    ['text' => 'Повседневная уборка', 'callback_data' => 'service_3'],
                    ['text' => 'Уборка офиса', 'callback_data' => 'service_4'],
                ]
            ],
        ];

        $inlineKeyboardUz = [
            'inline_keyboard' => [
                [
                    ['text' => 'Umumiy tozalash', 'callback_data' => 'service_1'],
                    ['text' => 'To`ydan keyin tozalash', 'callback_data' => 'service_2'],
                ],
                [
                    ['text' => 'Kundalik tozalash', 'callback_data' => 'service_3'],
                    ['text' => 'Ofis tozalash', 'callback_data' => 'service_4'],
                ],
            ],
        ];

        $message = $messageUz;
        $inlineKeyboard = $inlineKeyboardUz;

        if (strval($language) === 'lang_ru') {
            $message = $messageRu;
            $inlineKeyboard = $inlineKeyboardRu;
        }

        TelegramBotHelper::deleteMessage($chatId, $messageId);
        TelegramBotHelper::inlineKeyboardAndMessage($chatId, $message, $inlineKeyboard);
    }
}
