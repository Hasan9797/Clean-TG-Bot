<?php

namespace App\Services\Telegram\Commands;

use App\Helpers\TelegramBotHelper;
use Illuminate\Support\Facades\Cache;

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
        $language = Cache::get("language_$chatId", 'lang_uz');

        $messageUz = 'Vaxtni tanlang:';
        $messageRu = 'Выберите время:';

        // Sanalar uchun inline keyboard

        $inlineKeyboard = CalendarCommand::sendCalendar();

        $message = $messageUz;


        if (strval($language) === 'lang_ru') {
            $message = $messageRu;
        }

        TelegramBotHelper::deleteMessage($chatId, $messageId);
        TelegramBotHelper::inlineKeyboardAndMessage($chatId, $message, $inlineKeyboard);
    }

}
