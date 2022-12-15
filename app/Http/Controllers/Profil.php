<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class Profil extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $item = User::where('id', $request->session()->get('user')->id)->get()->first();
        return view('panel.profil.profil', $item);
    }
}
