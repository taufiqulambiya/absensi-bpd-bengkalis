<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Uploads extends Controller
{
    public function store(Request $request)
    {
        return response()->json($request->file());
    }
}
