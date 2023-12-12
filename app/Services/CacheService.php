<?php

namespace App\Services;

use Illuminate\Support\Env;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;

class CacheService
{
    static function remember($func, $request = null, $key = null): mixed
    {
        $key = $key ?? $request->getRequestUri() ?? "cache";

        if (Env::get("CACHE_ENABLED", true)) {
            return Cache::remember($key, Date::now()->addMinutes(Env::get("CACHE_TIME", 1)), $func);
        }
        return call_user_func($func);
    }

}
