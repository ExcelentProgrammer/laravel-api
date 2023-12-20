<?php

namespace App\Rules;

use App\Http\Helpers\ExceptionHelper;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^(998)(90|91|92|93|94|95|96|97|98|99|33|88)[0-9]{7}$/', $value)) {
            ExceptionHelper::sendError(__("phone:invalid"));
        }
    }
}
