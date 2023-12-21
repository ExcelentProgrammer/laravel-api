<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @property array|mixed $except
 */
trait BaseResource
{


    static function paginate(object $data): array
    {
        return [
            "data" => parent::collection($data),
            "meta" => [
                "count" => $data->count(),
                "currentPage" => $data->currentPage(),
                "previousPageUrl" => $data->previousPageUrl(),
                "nextPageUrl" => $data->nextPageUrl(),
                "total" => $data->total(),
                "perPage" => $data->perPage(),
                "lastPage"=>$data->lastPage()
            ]
        ];
    }


}
