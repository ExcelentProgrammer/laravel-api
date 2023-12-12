<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

trait BaseController
{
    function success(string $message = "", mixed $data = null, int $code = 200, ...$meta): JsonResponse
    {
        $response = [
            "success" => true,
            "message" => $message,
            "data" => $data,
            "code" => $code,
            ...$meta
        ];

        return Response::json($response, $code);
    }

    protected function error(string $message = '', array|object $data = [], int $code = 403): JsonResponse
    {
        return Response::json([
            'status' => false,
            'message' => $message,
            "data" => $data,
            "code" => $code
        ], $code);
    }

}
