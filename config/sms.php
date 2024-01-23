<?php


use Illuminate\Support\Env;

return [
    "api_url" => Env::get("SMS_DOMAIN", "notify.eskiz.uz/api/"),
    "login" => Env::get("SMS_USER", "admin@gmail.com"),
    "sender" => Env::get("SMS_SENDER", "me"),
    "password" => Env::get("SMS_PASSWORD"),
    "token" => Env::get("SMS_TOKEN"),
];
