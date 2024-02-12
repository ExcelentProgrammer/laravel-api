<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseRequest;
use App\Rules\PhoneRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{

    use BaseRequest;

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            "phone" => ['required', new PhoneRule(), "integer", "unique:users,phone"],
            "name" => ['required', 'string'],
            "password" => ['required', 'string', 'min:8', 'confirmed']
        ];
    }
}
