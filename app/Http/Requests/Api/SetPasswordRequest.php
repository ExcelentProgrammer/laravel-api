<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class SetPasswordRequest extends FormRequest
{

    use BaseRequest;

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            "password" => ['required', "min:8", "max:255"]
        ];
    }
}
