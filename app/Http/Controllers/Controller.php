<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BController;

class Controller extends BController
{
    use AuthorizesRequests, ValidatesRequests, BaseController;
}
