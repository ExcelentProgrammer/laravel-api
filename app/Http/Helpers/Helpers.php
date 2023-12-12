<?php

namespace App\Http\Helpers;


use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use JetBrains\PhpStorm\NoReturn;

class Helpers
{
    #[NoReturn] static function dd($data, $stop = true): void
    {
        echo "\n=====================\n";
        print_r($data);
        echo "\n=====================\n";

        if ($stop)
            exit();
    }

    static function log($data): void
    {
        print_r(json_encode($data));
        $file = fopen(App::basePath() . "/storage/logs/autopost.txt", "a");
        fwrite($file, json_encode($data) . "\n");
        fclose($file);
    }

    static function currentLocale(): int|string
    {
        return array_flip(Config::get("app.locales"))[App::getLocale()];
    }
}
