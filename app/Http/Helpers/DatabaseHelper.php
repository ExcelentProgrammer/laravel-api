<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\DB;

class DatabaseHelper
{
    static function getRandomId($table): int
    {
        return DB::select("SELECT CEIL(RANDOM() * (SELECT MAX(id) FROM $table)) AS random_id")[0]->random_id;
    }
}
