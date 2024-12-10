<?php

namespace App\Services\Telegram\Commands;

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

        $cachLanguage = Cache::get("language_$chatId");

        if ($cachLanguage) {
            Cache::delete("language_$chatId");
        }

        Cache::put("language_$chatId", $language, 7200); // 2 soatga saqlash.

        (new ContactCommand())->execute($request, $language);
    }
}
