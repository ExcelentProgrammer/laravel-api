<?php

namespace App\Http\Helpers;

class Helper
{
    /**
     * @param ...$roles
     * @return string
     * User rollarini middlewarega tayyorlab berish uchun
     */
    static function roles(...$roles): string
    {
        return "role:" . implode(",", $roles);
    }

    /**
     * @param array $data
     * @return array
     * Null elementlarni olib tashlash uchun
     */
    static function removeNullData(array $data): array
    {
        return array_filter($data, function ($arr) {
            return $arr !== null;
        });
    }

    static function count($data): array
    {
        $response = [];

        $count = 1;
        foreach ($data as $key => $datum) {
            if (isset($data[$key + 1]) and $datum == $data[$key + 1]) {
                $count += 1;
            } else {
                $response[] = [
                    "data" => $datum,
                    "count" => $count
                ];
                $count = 1;
            }
        }
        return $response;
    }
}
