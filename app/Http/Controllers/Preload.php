<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Preload extends Controller
{
    public function __invoke()
    {
        $user = session('user');
        if (empty($user)) {
            return redirect()->route('auth');
        }
        $allowed = $user->level == 'admin';
        return view('preload.preload', compact('allowed'));
    }

    public function attributes()
    {
        return [
            'jatah_cuti_ctln' => 'Cuti Diluar Tanggungan Negara',
        ];
    }

    public function store(Request $request)
    {
        $rules = array_reverse([
            'jatah_cuti_tahunan' => 'numeric|min:5|required',
            'jatah_cuti_besar' => 'numeric|min:5|required',
            'jatah_cuti_melahirkan' => 'numeric|min:5|required',
            'jatah_cuti_penting' => 'numeric|min:5|required',
            'jatah_cuti_ctln' => 'numeric|min:5|required',
            'base_lat' => 'numeric|required',
            'base_long' => 'numeric|required',
        ]);
        $validator = Validator::make($request->all(), $rules, [], getCustomAttributes('setting'));
        $validated = $validator->validate();
        if ($validated) {
            $setting = Settings::first();
            if (empty($setting)) {
                Settings::create($request->post());
            } else {
                $setting->update($request->post());
            }
            return redirect()->route('auth')->with('success', 'Konfigurasi sistem berhasil');
        } else {
            return redirect()->route('auth')->with('error', 'Konfigurasi sistem gagal');
        }
    }
}
