<?php

namespace App\Http\Middleware;

use App\Services\CacheService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class CacheMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Env::get("CACHE_ENABLED", true)) {
            return CacheService::remember(func: function () use ($next, $request) {
                return $next($request);
            }, key: $this->getKey($request));
        }
        return $next($request);
    }

    function getKey(Request $request): string
    {
        return md5($request->getRequestUri() . "|" . $request->getMethod() . "|" . App::getLocale());
    }
}
