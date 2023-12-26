<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeResource extends JsonResource
{
    use BaseResource;


    public function toArray(Request $request): array
    {
        return [
            "full_name" => $this->full_name,
            "phone" => $this->phone,
            "created_at"=>$this->created_at->format("d.m.Y H:i")
        ];
    }
}
