<?php

namespace App\Services\Telegram\Commands;

use App\Helpers\TelegramBotHelper;
use Illuminate\Support\Facades\Cache;

class ServicesCommand
{
    public static function handel($request)
    {
        $message = strval($request->input('message.text'));

        if ($message == '/categories') {
            return true;
        }
        return false;
    }


    public function execute($request)
    {
        $chatId = $request->input('message.chat.id');

        $messageId = $request->input('message.message_id');

        $this->getServices($chatId, $messageId);
    }

    public function getServices($chatId, $messageId)
    {
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

        $message = 'Hizmat turlarini tanlang:';
        $cachLanguage = Cache::get("language_$chatId", 'lang_uz');

        $inlineKeyboard = $inlineKeyboardUz;

        if (strval($cachLanguage) === 'lang_ru') {
            $message = 'Выберите типы услуг:';
            $inlineKeyboard = $inlineKeyboardRu;
        }

        // TelegramBotHelper::deleteMessage($chatId, $messageId);
        $response = TelegramBotHelper::inlineKeyboardAndMessage($chatId, $message, $inlineKeyboard);
        return $response;
    }
}
