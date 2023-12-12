<?php

namespace App\Http\Helpers;

class DateHelper
{
    static function getDate($date): string
    {
        return $date->format("d M H:i");
    }
}



