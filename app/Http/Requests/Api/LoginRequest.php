<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseRequest;
use App\Rules\PhoneRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{

    use BaseRequest;

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            "phone" => ['required', new PhoneRule(), "exists:users,phone"],
            "password" => ['required', "min:8", "max:255"]
        ];
    }
}
