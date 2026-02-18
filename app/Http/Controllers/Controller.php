<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController  // Наследуем от BaseController
{
    use AuthorizesRequests, ValidatesRequests;  // Добавляем трейты
}