<?php

namespace App\Services\Telegram\Commands;

use App\Helpers\TelegramBotHelper;
use Telegram\Bot\Keyboard\Keyboard;

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
        $messageId = $request->input('callback_query.message.message_id');
        $language = $request->input('language') ?? 'lang_uz';

        $messageUz = 'Vaxtni tanlang:';
        $messageRu = 'Выберите время:';

        // Sanalar uchun inline keyboard
        $keyboard = Keyboard::make()
        ->inline()
        ->row([
            Keyboard::inlineButton(['text' => '2024-12-01', 'callback_data' => '2024-12-01']),
            Keyboard::inlineButton(['text' => '2024-12-02', 'callback_data' => '2024-12-02'])
        ])
        ->row([
            Keyboard::inlineButton(['text' => '2024-12-03', 'callback_data' => '2024-12-03']),
            Keyboard::inlineButton(['text' => '2024-12-04', 'callback_data' => '2024-12-04'])
        ]);


        $message = $messageUz;


        if (strval($language) === 'lang_ru') {
            $message = $messageRu;
        }

        TelegramBotHelper::deleteMessage($chatId, $messageId);
        TelegramBotHelper::inlineKeyboardAndMessage($chatId, $message, $keyboard);
    }
}
