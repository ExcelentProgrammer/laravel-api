<?php

namespace App\Providers;

use Illuminate\Support\Env;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (Env::get("REDIRECT_HTTPS", false)) {
            $this->app['request']->server->set("HTTPS", true);
            URL::forceScheme("https");
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
