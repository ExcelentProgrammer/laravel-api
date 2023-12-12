<?php

namespace App\Http\Helpers;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;

class WeatherHelper
{
    /**
     * @throws GuzzleException
     */
    static function getWeather($city)
    {
        $client = new Client();
        $response = $client->get(Config::get("weather.url") . "&appid=" . Env::get("WEATHER_KEY") . "&q=$city");
        return json_decode($response->getBody()->getContents());
    }
}
