<?php

namespace App\Services;

use App\Enums\DaysWeekEnums;
use Carbon\CarbonInterface;

class BaseService
{
    public static function ifStringToArray($object): array
    {
        if (is_string($object) or is_integer($object)) {
            return [$object];
        } elseif (is_array($object)) {
            return $object;
        } else {
            throw new \InvalidArgumentException('Invalid input. The input must be either a string or an array.');
        }
    }

    static function isPhone($text): bool
    {
        return preg_match("/^(998)(90|91|92|93|94|95|96|97|98|99|33|88)[0-9]{7}$/", $text);
    }

    static function getDayNumber($day, $default = CarbonInterface::MONDAY)
    {

        return match ($day) {
            DaysWeekEnums::Monday => CarbonInterface::MONDAY,
            DaysWeekEnums::Friday => CarbonInterface::FRIDAY,
            DaysWeekEnums::Saturday => CarbonInterface::SATURDAY,
            DaysWeekEnums::Sunday => CarbonInterface::SUNDAY,
            DaysWeekEnums::Thursday => CarbonInterface::THURSDAY,
            DaysWeekEnums::Tuesday => CarbonInterface::TUESDAY,
            DaysWeekEnums::Wednesday => CarbonInterface::WEDNESDAY,
            default => $default,
        };
    }


}
