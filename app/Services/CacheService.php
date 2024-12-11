<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    public static function updateCache($key, $value)
    {
        $cachCreate = Cache::get($key, false);

        if ($cachCreate) {
            Cache::delete($key);
        }

        Cache::put($key, $value, 3600);
    }
}
