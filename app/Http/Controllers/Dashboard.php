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
            $count_by = ['accepted_kabid', 'accepted_admin', 'accepted_pimpinan'];
            $data = [
                'absensi_count' => Absensi::all()->count(),
                'izin_count' => Izin::whereIn('status', $count_by)->count(),
                'izin_pending' => Izin::where('status', 'accepted_kabid')->count(),
                'cuti_count' => Cuti::whereIn('status', $count_by)->count(),
                'cuti_pending' => Cuti::where('status', 'accepted_kabid')->count(),
                'dinas_luar_count' => DinasLuar::all()->count(),
                'jam_kerja_count' => JamKerja::all()->count(),
                'pegawai_count' => User::all()->count(),
            ];
            return view('panel.admin.dashboard.dashboard', $data);
        }

        if ($user_level == 'kabid') {
            $user = session('user');
            $bidang = $user->bidang;
            $izin = Izin::with('user')->whereHas('user', function ($query) use ($bidang) {
                $query->where('bidang', $bidang);
            });
            $cuti = Cuti::with('user')->whereHas('user', function ($query) use ($bidang) {
                $query->where('bidang', $bidang);
            });
           
            $data = [
                'izin_count' => $izin->count(),
                'izin_pending' => $izin->where('status', 'pending')->count(),
                'cuti_count' => $cuti->count(),
                'cuti_pending' => $cuti->where('status', 'pending')->count(),
                'pegawai_count' => User::where('bidang', $bidang)->count(),
            ];
            return view('panel.kabid.dashboard.dashboard', $data);
        }

        if ($user_level == 'pegawai') {
            $user_id = $user_sess->id;
            $last_izin = Izin::lastPengajuan($user_id);
            $last_cuti = Cuti::lastPengajuan($user_id);
            $data = [
                'absensi_count' => Absensi::where('id_user', $user_sess->id)->count(),
                'izin_count' => Izin::where('id_user', $user_sess->id)->count(),
                'cuti_count' => Cuti::where('id_user', $user_sess->id)->count(),
                'dinas_luar_count' => DinasLuar::where('id_user', $user_sess->id)->count(),
                'last_izin' => $last_izin,
                'last_cuti' => $last_cuti,
            ];
            return view('panel.pegawai.dashboard.dashboard', $data);
        }
        
        if ($user_level == 'atasan') {
            $data = [
                'absensi_count' => Absensi::all()->count(),
                'izin_count' => Izin::all()->count(),
                'izin_pending' => Izin::where('status', 'accepted_admin')
                    ->where('tgl_mulai', '>=', date('Y-m-d'))
                    ->count(),
                'cuti_count' => Cuti::all()->count(),
                'cuti_pending' => Cuti::where('status', 'accepted_admin')
                    ->get()
                    ->filter(function($x) {
                        $dates = explode(',', $x->tanggal);
                        // return dates bigger than now, use some
                        return collect($dates)->some(function($date) {
                            return $date >= date('Y-m-d');
                        });
                    })
                    ->count(),
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
