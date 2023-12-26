<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseRequest;
use App\Rules\PhoneRule;
use Illuminate\Foundation\Http\FormRequest;

class ConfirmRequest extends FormRequest
{

    use BaseRequest;

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            "phone" => ["required", new PhoneRule(), "integer"],
            "code" => ['required', 'integer', "max:9999"]
        ];
    }
}
