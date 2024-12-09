<?php

namespace App\Services\Telegram\Commands;

use App\Helpers\TelegramBotHelper;

class ServiceCommand
{
    public static function handel($request)
    {
        $message = explode('_', strval($request->input('callback_query.data')));

        if (count($message) === 2 && strval($message[0]) === 'service') {
            return true;
        }
        return false;
    }


    public function execute($request)
    {
        $chatId = $request->input('callback_query.message.chat.id');
        $messageId = $request->input('callback_query.message.id');
        $language = $request->input('language') ?? 'lang_uz';

        $messageUz = 'Vaxtni tanlang:';
        $messageRu = 'Выберите время:';

        $inlineKeyboard = [
            'inline_keyboard' => [
                [
                    ['text' => '1', 'callback_data' => 'day_1'],
                    ['text' => '2', 'callback_data' => 'day_2'],
                    ['text' => '3', 'callback_data' => 'day_3'],
                ],
                [
                    ['text' => '4', 'callback_data' => 'day_4'],
                    ['text' => '5', 'callback_data' => 'day_5'],
                    ['text' => '6', 'callback_data' => 'day_6'],
                ],
                [
                    ['text' => 'Next ➡️', 'callback_data' => 'select_month'],
                ],
            ],
        ];

        $message = $messageUz;


        if (strval($language) === 'lang_ru') {
            $message = $messageRu;
        }

        TelegramBotHelper::deleteMessage($chatId, $messageId);
        TelegramBotHelper::inlineKeyboardAndMessage($chatId, $message, $inlineKeyboard);
    }
}
