<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TelegramLanguageMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            $chatId = $request->input('message.chat.id')
                ?? $request->input('callback_query.message.chat.id');

            if (!$chatId) {
                Log::info('Chat ID not found in request.');
                return $next($request);
            }

            $language = Cache::get("language_$chatId", 'uz'); // Default to 'uz'

            $request->merge(['language' => $language]);

            Log::info("Language set for Chat ID $chatId: $language");

            return $next($request);
        } catch (\Exception $e) {
            Log::error('Middleware error: ' . $e->getMessage());
            throw $e; // Optional: Yoki qayta ishlashni davom ettirish uchun $next chaqiriladi
        }
    }
}
