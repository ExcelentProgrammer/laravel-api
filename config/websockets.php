<?php

use BeyondCode\LaravelWebSockets\Dashboard\Http\Middleware\Authorize;

return [

    /*
     * Set a custom dashboard configuration
     */
    'dashboard' => [
        'port' => env('LARAVEL_WEBSOCKETS_PORT', 6001),
    ],

    /*
     * This package comes with multi tenancy out of the box. Here you can
     * configure the different apps that can use the webSockets server.
     *
     * Optionally you specify capacity so you can limit the maximum
     * concurrent connections for a specific app.
     *
     * Optionally you can disable client events so clients cannot send
     * messages to each other via the webSockets.
     */
    'apps' => [
        [
            'id' => env('PUSHER_APP_ID'),
            'name' => env('APP_NAME'),
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'path' => env('PUSHER_APP_PATH'),
            'capacity' => null,
            'enable_client_messages' => false,
            'enable_statistics' => true,
        ],
    ],


    'app_provider' => BeyondCode\LaravelWebSockets\Apps\ConfigAppProvider::class,


    'allowed_origins' => [
        //
    ],

    'max_request_size_in_kb' => 250,


    'path' => 'laravel-websockets',


    'middleware' => [
        'web',
        Authorize::class,
    ],

    'statistics' => [

        'model' => \BeyondCode\LaravelWebSockets\Statistics\Models\WebSocketsStatisticsEntry::class,

        /**
         * The Statistics Logger will, by default, handle the incoming statistics, store them
         * and then release them into the database on each interval defined below.
         */
        'logger' => BeyondCode\LaravelWebSockets\Statistics\Logger\HttpStatisticsLogger::class,


        'interval_in_seconds' => 60,


        'delete_statistics_older_than_days' => 60,


        'perform_dns_lookup' => false,
    ],


    'ssl' => [

        'local_cert' => env('LARAVEL_WEBSOCKETS_SSL_LOCAL_CERT', null),


        'local_pk' => env('LARAVEL_WEBSOCKETS_SSL_LOCAL_PK', null),


        'passphrase' => env('LARAVEL_WEBSOCKETS_SSL_PASSPHRASE', null),
    ],


    'channel_manager' => \BeyondCode\LaravelWebSockets\WebSockets\Channels\ChannelManagers\ArrayChannelManager::class,
];
