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
                Keyboard::inlineButton(['text' => 'Yanvar', 'callback_data' => 'month:01']),
                Keyboard::inlineButton(['text' => 'Fevral', 'callback_data' => 'month:02']),
                Keyboard::inlineButton([
                    'text' => 'Mart',
                    'callback_data' => 'month:03'
                ]),
            ])
            ->row([
                Keyboard::inlineButton(['text' => 'Aprel', 'callback_data' => 'month:04']),
                Keyboard::inlineButton(['text' => 'May', 'callback_data' => 'month:05']),
                Keyboard::inlineButton([
                    'text' => 'Iyun',
                    'callback_data' => 'month:06'
                ]),
            ])
            ->row([
                Keyboard::inlineButton(['text' => '<<', 'callback_data' => 'prev_year']),
                Keyboard::inlineButton(['text' => 'Yilni tanlash', 'callback_data' => 'select_year']),
                Keyboard::inlineButton(['text' => '>>', 'callback_data' => 'next_year']),
            ]);


        $message = $messageUz;


        if (strval($language) === 'lang_ru') {
            $message = $messageRu;
        }

        TelegramBotHelper::deleteMessage($chatId, $messageId);
        TelegramBotHelper::inlineKeyboardAndMessage($chatId, $message, $keyboard);
    }
}
