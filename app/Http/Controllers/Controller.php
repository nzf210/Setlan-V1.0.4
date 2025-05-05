<?php

namespace App\Http\Controllers;


define('TAHUN', 'sometimes|integer|exists:tahun,tahun');
define('LONG_STR', 'required|string|max:65535');
define('SHORT_STR', 'required|string|max:255');
define('REQ_NUM', 'required|numeric');
abstract class Controller
{
    //
}
