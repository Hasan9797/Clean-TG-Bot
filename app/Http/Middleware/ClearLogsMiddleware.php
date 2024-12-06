<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\File;

class ClearLogsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Laravel log faylining yo‘lini aniqlash
        $logFilePath = storage_path('logs/laravel.log');

        // Log fayl mavjudligini tekshirish va uni tozalash
        if (File::exists($logFilePath)) {
            File::put($logFilePath, ''); // Faylni tozalash
        }

        return $next($request);
    }
}
