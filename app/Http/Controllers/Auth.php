<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class Auth extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {  
        return view('auth.login');
    }

    public function index(Request $request)
    {
        $sess_user = $request->session()->get('user');
        if(!empty($sess_user)) {
            return redirect('panel/dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nip' => 'required|numeric',
            'password' => 'required',
        ], [
            'required' => ':Attribute tidak boleh kosong.',
            'numeric' => ':ATTRIBUTE harus berupa angka.'
        ]);

        $user = User::where(["nip" => $request->post('nip')])->first();
        if($user) {
            $is_password_correct = password_verify($request->post('password'), $user->password);

            if ($is_password_correct) {
                $request->session()->put('user', $user);
                return redirect()->route('dashboard');
            } else {
                return redirect()->back()->with('error', 'Password salah.');
            }
        } else {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }
    }

    public function logout(Request $request)
    {
        $request->session()->forget('user');
        return redirect('auth');
    }
}
