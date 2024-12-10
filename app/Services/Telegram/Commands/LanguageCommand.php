<?php

namespace App\Services\Telegram\Commands;

use App\Helpers\TelegramBotHelper;
use Illuminate\Support\Facades\Cache;

class LanguageCommand
{
    public static function handel($request)
    {
        $message = strval($request->input('callback_query.data'));
        $languages = ['lang_ru', 'lang_uz'];

        return in_array($message, $languages, true);
    }

    public function execute($request)
    {
        $chatId = $request->input('callback_query.message.chat.id');
        $language = $request->input('callback_query.data');
        $messageId = $request->input('callback_query.message.message_id');

        $cachLanguage = Cache::get("language_$chatId");

        if ($cachLanguage) {
            Cache::delete("language_$chatId");
        }

        Cache::put("language_$chatId", $language, 7200);

        $message = 'Iltimos, telefon raqamingizni yuboring yoki quyidagi tugma orqali o\'zingizning kontaktni yuboring:';
        $messageRu = 'Пожалуйста, отправьте свой номер телефона или отправьте контактную информацию, используя кнопку ниже:';

        if (strval($language) === 'lang_ru') {
            $message = $messageRu;
        }

        // TelegramBotHelper::deleteMessage($chatId, $messageId);
        TelegramBotHelper::sendPhoneRequest($chatId, $message);
    }
}
