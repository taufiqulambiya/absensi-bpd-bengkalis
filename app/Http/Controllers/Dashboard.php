<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Cuti;
use App\Models\DinasLuar;
use App\Models\Izin;
use App\Models\JamKerja;
use App\Models\User;
use Illuminate\Http\Request;

class Dashboard extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $user_sess = session('user');
        $user_level = $user_sess->level;

        if ($user_level == 'admin') {
            $data = [
                'absensi_count' => Absensi::all()->count(),
                'izin_count' => Izin::all()->count(),
                'izin_pending' => Izin::where('status', 'accepted_kabid')->count(),
                'cuti_count' => Cuti::all()->count(),
                'cuti_pending' => Cuti::where('status', 'accepted_kabid')->count(),
                'dinas_luar_count' => DinasLuar::all()->count(),
                'jam_kerja_count' => JamKerja::all()->count(),
                'pegawai_count' => User::all()->count(),
            ];
            return view('panel.admin.dashboard.dashboard', $data);
        }

        if ($user_level == 'kabid') {
            $jabatan = $user_sess->jabatan;
            $izin = Izin::with(['user' => function ($item) use ($jabatan) {
                return $item->where('jabatan', $jabatan);
            }])->get()->filter(function ($x) {
                return $x->user;
            });
            $cuti = Cuti::with(['user' => function ($item) use ($jabatan) {
                return $item->where('jabatan', $jabatan);
            }])->get()->filter(function ($x) {
                return $x->user;
            });
            $data = [
                'izin_count' => $izin->count(),
                'izin_pending' => $izin->where('status', 'pending')->count(),
                'cuti_count' => $cuti->count(),
                'cuti_pending' => $cuti->where('status', 'pending')->count(),
                'pegawai_count' => User::where('jabatan', $jabatan)->count(),
            ];
            return view('panel.kabid.dashboard.dashboard', $data);
        }

        if ($user_level == 'pegawai') {
            $data = [
                'absensi_count' => Absensi::where('id_user', $user_sess->id)->count(),
                'izin_count' => Izin::where('id_user', $user_sess->id)->count(),
                'cuti_count' => Cuti::where('id_user', $user_sess->id)->count(),
                'dinas_luar_count' => DinasLuar::where('id_user', $user_sess->id)->count(),
            ];
            return view('panel.pegawai.dashboard.dashboard', $data);
        }
        
        if ($user_level == 'atasan') {
            $data = [
                'absensi_count' => Absensi::all()->count(),
                'izin_count' => Izin::all()->count(),
                'izin_pending' => Izin::where('status', 'accepted_admin')->count(),
                'cuti_count' => Izin::all()->count(),
                'cuti_pending' => Cuti::where('status', 'accepted_admin')->count(),
                'dinas_luar_count' => Izin::all()->count(),
                'jam_kerja_count' => JamKerja::all()->count(),
                'pegawai_count' => User::all()->count(),
                'dinas_luar_count' => DinasLuar::all()->count(),
            ];
            return view('panel.pimpinan.dashboard.dashboard', $data);
        }
    }

    // public function index(
}
