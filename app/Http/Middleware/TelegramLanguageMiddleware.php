<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class TelegramLanguageMiddleware
{
    public function handle($request, Closure $next)
    {
        // Telegram so‘rovlaridan chat_id olish
        $chatId = $request->input('message.chat.id')
            ?? $request->input('callback_query.message.chat.id');

        if (!$chatId) {
            return $next($request); // Agar chat_id bo‘lmasa, davom etadi.
        }

        $language = Cache::get("language_$chatId");

        if (!$language) {
            $language = 'uz'; // Default til: 'uz'.
        }

        // Request obyektiga tilni o‘rnating
        $request->merge(['language' => $language]);

        return $next($request);
    }
}
