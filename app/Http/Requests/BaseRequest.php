<?php

namespace App\Http\Requests;

use App\Http\Controllers\BaseController;
use App\Services\Base\BaseService;
use App\Services\Locale\LocaleService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait BaseRequest
{
    use BaseController;

    /**
     * @param Validator $validator
     * @return mixed
     * Validatsiyadan o'tmaganda json error response qaytarish uchun
     */
    function failedValidation(Validator $validator): mixed
    {
        $errors = [];
        foreach ($validator->errors()->toArray() as $key => $error) {
            $errors[$key] = $error[0];
        }

        throw new HttpResponseException($this->error(data: $errors));
    }

    function localeOnly(array|string $fields, array|string $add = []): array
    {
        $add = BaseService::ifStringToArray($add);
        return $this->only([...LocaleService::getLocaleFields($fields), ...$add]);
    }
}
