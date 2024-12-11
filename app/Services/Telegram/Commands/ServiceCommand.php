<?php

namespace App\Services\Telegram\Commands;

use App\Enums\ServiceEnum;
use App\Helpers\TelegramBotHelper;
use App\Services\CacheService;
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
        $service = strval($request->input('callback_query.data'));

        $language = Cache::get("language_$chatId", 'lang_uz');

        $messageUz = 'Vaxtni tanlang:';
        $messageRu = 'Выберите время:';

        $inlineKeyboard = CalendarCommand::sendCalendar();

        $message = $messageUz;


        if (strval($language) === 'lang_ru') {
            $message = $messageRu;
        }


        TelegramBotHelper::deleteMessage($chatId, $messageId);
        $response = TelegramBotHelper::inlineKeyboardAndMessage($chatId, $message, $inlineKeyboard);

        if($response){
            CacheService::updateCache("service_$chatId", ServiceEnum::getService($service, $language));
        }

    }

}
