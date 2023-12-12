<?php

namespace App\Http\Helpers;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;

class ExceptionHelper
{
    /**
     * @param $message
     * @param array|string|int|null $error
     * @param int $code
     * @return void
     * @throw HttpResponseException
     */
    static function sendError($message, array|string|int|null $error = null, int $code = 401): void
    {
        $response = [
            "success" => false,
            "message" => $message,
            "error" => $error,
            "code" => $code
        ];
        throw new HttpResponseException(Response::json($response, $code));
    }
}
