<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\Absensi as AdminAbsensi;
use App\Models\Absensi as ModelsAbsensi;
use App\Models\Cuti;
use App\Models\DinasLuar;
use App\Models\Izin;
use App\Models\JamKerja;
use App\Models\Settings;
use App\Models\User;
use Carbon\Carbon;
use DragonCode\Support\Facades\Helpers\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Absensi extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sess_user = $request->session()->get('user');
        $user = User::find($sess_user->id);
        $user_level = $sess_user->level;

        if ($user_level == 'admin') {
            $admin_absensi = new AdminAbsensi();
            return $admin_absensi->index();
        }

        if ($user_level == 'pegawai') {
            $has_cuti = Cuti::where('id_user', $user->id)
                ->where('status', 'accepted_pimpinan')
                ->get()
                ->filter(function ($x) {
                    $tanggal = explode(',', $x->tanggal);
                    $found = array_filter($tanggal, function ($y) {
                        $current_date = date('Y-m-d');
                        return $current_date === $y;
                    });
                    return count($found) > 0;
                });
            $has_izin = Izin::where(['id_user' => $user->id])
                ->where('tgl_mulai', '<=', date('Y-m-d'))
                ->where('tgl_selesai', '>=', date('Y-m-d'))
                ->where('status', 'accepted_pimpinan')
                ->get()
                ->each(function ($x) {
                    $x->tgl_mulai = Carbon::parse($x->tgl_mulai)->format('d/m/Y');
                    $x->tgl_selesai = Carbon::parse($x->tgl_selesai)->format('d/m/Y');
                })
                ->first();
            $dinas_luar = DinasLuar::where('id_user', $user->id)->where('mulai', '>=', date('Y-m-d'))->where('selesai', '>=', date('Y-m-d'))->get();
            $has_dinas = $dinas_luar->count() > 0;
            $shift = JamKerja::where('status', 'aktif')
                ->get()
                ->each(function ($x) {
                    $x->formatted = Carbon::parse($x->mulai)->format('H:i') . ' - ' . Carbon::parse($x->selesai)->format('H:i \W\I\B');
                    $current_time = Carbon::now();
                    $mulai = Carbon::parse($x->mulai);
                    $selesai = Carbon::parse($x->selesai);
                    $x->is_absen_time = $current_time->between($mulai, $selesai);
                })->filter(function($x){
                    $days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat'];
                    $days_used = explode(', ', $x->days);
                    $current_day_index = date('w') - 1;
                    $current_day = $days[$current_day_index];

                    return array_search($current_day, $days_used);
                });
            $jam_kerja = null;
            if ($shift->count() > 0) {
                $jam_kerja =$shift->first();
            }
            $absensi = ModelsAbsensi::with('shift')
                ->where('id_user', $user->id)
                ->get()
                ->each(function ($x) {
                    $x->has_keluar = Carbon::parse($x->waktu_keluar)->isMidnight() == false;
                    $x->terlewat = date('Y-m-d') > $x->tanggal;
                })
                ->last();
            $current_absensi = ModelsAbsensi::with('shift')
                ->where(['id_user' => $user->id, 'tanggal' => date('Y-m-d')])->get()
                ->each(function ($x) {
                    $x->formatted_shift = Carbon::parse($x->shift->mulai)->format('H:i') . ' - ' . Carbon::parse($x->shift->selesai)->format('H:i \W\I\B');
                })
                ->first();

            $data = [
                'setting' => Settings::first(),
                'user' => $user,
                'jam_kerja' => $jam_kerja,
                'absensi' => $absensi,
                'has_izin' => $has_izin,
                'has_cuti' => $has_cuti->last(),
                'has_dinas' => $has_dinas,
                'not_allowed' => Arr::flatten($has_cuti->map(function ($item) {
                    return explode(',', $item->tanggal);
                })),
                'current_absensi' => $current_absensi,
            ];
        }

        return view('panel.pegawai.absensi.absensi', $data);
    }

    public function store(Request $request)
    {
        if (!empty($request->dok_masuk)) {
            $image = str_replace('data:image/png;base64,', '', $request->dok_masuk);
            $image = str_replace(' ', '+', $image);
            $imageName = fake('id-ID')->uuid() . '.' . 'png';
            $post = $request->post();
            $post['dok_masuk'] = $imageName;
            Storage::disk('public')->put('uploads/' . $imageName, base64_decode($image));
            $post['status'] = 'hadir';
            $result = ModelsAbsensi::create($post);
            if ($result) {
                return response()->json([
                    'data' => $post,
                    'success' => 'Rekam absen berhasil.',
                ]);
            } else {
                return response()->json([
                    'data' => $post,
                    'error' => 'Rekam absen gagal.',
                ]);
            }
        }
    }

    public function update(Request $request, $id)
    {
        if (!empty($request->dok_keluar)) {
            $image = str_replace('data:image/png;base64,', '', $request->dok_keluar);
            $image = str_replace(' ', '+', $image);
            $imageName = fake('id-ID')->uuid() . '.' . 'png';
            $post = $request->post();
            $post['dok_keluar'] = $imageName;
            Storage::disk('public')->put('uploads/' . $imageName, base64_decode($image));
            $post['status'] = 'hadir';
            $data = ModelsAbsensi::find($id);
            $result = $data->update($post);
            if ($result) {
                return response()->json([
                    'data' => $post,
                    'success' => 'Rekam absen berhasil.',
                ]);
            } else {
                return response()->json([
                    'data' => $post,
                    'error' => 'Rekam absen gagal.',
                ]);
            }
        }
    }

    public function show(Request $request, $id)
    {
        $absensi = ModelsAbsensi::with('user')->find($id);
        $absensi->has_out = false;
        if ($absensi) {
            $absensi->has_out = Carbon::parse($absensi->waktu_keluar)->isMidnight() == false;
        }
        return view('panel.admin.absensi.detail', compact('absensi'));
    }

    public function riwayat()
    {
        $user_id = session('user')->id;
        $data = [
            'absensi' => ModelsAbsensi::with('user')->where('id_user', $user_id)
                ->orderBy('tanggal', 'desc')
                ->get()
                ->each(function ($x) {
                    $jam_kerja = JamKerja::where('status', 'aktif')->first();
                    $x->tanggal = Carbon::parse($x->tanggal)->format('d/m/Y');
                    $x->jam_kerja = $jam_kerja ? Carbon::parse($jam_kerja->mulai)->format('H:i') . ' - ' . Carbon::parse($jam_kerja->selesai)->format('H:i \W\I\B') : '-';
                    $x->waktu_masuk = Carbon::parse($x->waktu_masuk)->format('H:i \W\I\B');
                    $x->waktu_keluar = Carbon::parse($x->waktu_keluar)->format('H:i \W\I\B');
                    $x->total_jam = Carbon::parse($x->waktu_masuk)->diff(Carbon::parse($x->waktu_keluar))->h . ' Jam';
                    $x->bukti_url = '#';
                }),
        ];
        return view('panel.pegawai.absensi.riwayat', $data);
    }
}
