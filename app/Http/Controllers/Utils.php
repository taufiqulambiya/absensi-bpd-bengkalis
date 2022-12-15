<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Utils extends Controller
{
    //
    public function download($file)
    {
        dd(Storage::get($file));
    }
}
