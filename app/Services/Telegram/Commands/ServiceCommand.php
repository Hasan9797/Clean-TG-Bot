<?php

namespace App\Services\Telegram\Commands;

use App\Enums\ServiceEnum;
use App\Helpers\TelegramBotHelper;
use App\Services\CacheService;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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

        $message = 'Vaxtni tanlang:';

        try {
            $inlineKeyboard = CalendarCommand::sendCalendar();

            if (strval($language) === 'lang_ru') {
                $message = 'Выберите время:';
            }

            $service = ServiceEnum::getService(strval($service), $language);
            CacheService::updateCache("service_$chatId", $service);

            TelegramBotHelper::deleteMessage($chatId, $messageId);
            TelegramBotHelper::inlineKeyboardAndMessage($chatId, $message, $inlineKeyboard);
        } catch (\Throwable $th) {
            Log::error('Error: ' . $th->getMessage());
            TelegramBotHelper::sendMessage(6900325674, 'ServiceCommand da Xatolik: ' . $th->getMessage());
            return false;
        }
    }
}
