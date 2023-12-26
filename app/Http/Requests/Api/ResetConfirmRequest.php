<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseRequest;
use App\Rules\PhoneRule;
use Illuminate\Foundation\Http\FormRequest;

class ResetConfirmRequest extends FormRequest
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
            "code" => ['required', "min:4", "max:4"],
            "password" => ['required', "min:8", "max:255"]
        ];
    }
}
