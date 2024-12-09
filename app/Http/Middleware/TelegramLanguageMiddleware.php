<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class TelegramLanguageMiddleware
{
    public function handle($request, Closure $next)
    {
        $chatId = $request->input('message.chat.id')
            ?? $request->input('callback_query.message.chat.id');

        if (!$chatId) {
            return $next($request);
        }

        $language = Cache::get("language_$chatId");

        if (!$language) {
            $language = 'uz';
        }

        $request->merge(['language' => $language]);

        return $next($request);
    }
}
